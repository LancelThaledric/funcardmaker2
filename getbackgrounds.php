<?php require_once('include/functions.php');
// Putain ça m'a viré tout le fichier, je suis obligé de le réécrire... :'(

/** Entrée : $_GET
 *      'template' => le nom du template de la funcard
 *      'titles'   => un array contenant des paires {id => titre} indiquant le titre de la section
 *                    de chaque sous-répertoire de fonds (base, edgings, boxes, etc)
 */

function _dirIteration($path, $level, &$array, $dirname){
    $it = new FilesystemIterator($path);
    foreach($it as $item){
        $filename = $item->getFilename();
        $filepath = rtrim($path, '/').'/'.$filename;
        // On passe les noms contenant un slash (les dossiers et fichiers suffixés)
        if (strpos($filename, '-') !== false) continue;
        if($it->isDir()){
            $array[$filename] = [];
            _dirIteration($filepath, $level+1, $array[$filename], $filename);
        } else {
            $letter = basename($filename, '.png');
            $array[$letter] = rtrim($path, '/').'-thumb/'.$filename;
        }
    }
}

function letterComparison($a, $b){
    $sortstr = 'wubrgmacl';
    $ina = strpos($sortstr, $a);
    $inb = strpos($sortstr, $b);
    
    if($a == $b) return 0;
    if($ina !== false && $inb !== false) return $ina - $inb;
    if($ina !== false && $inb === false) return -1;
    if($ina === false && $inb !== false) return 1;
    // else, we sort aplhabetically
    return strcmp($a, $b);
}

function basePartComparison($a, $b){
    if($a == $b) return 0;
    $basePartArray = [
        'base' => 0,
        'edgings' => 1,
        'boxes' => 2
    ];
    
    $ina = isset($basePartArray[$a]);
    $inb = isset($basePartArray[$b]);
    
    if($ina !== false && $inb !== false) return $basePartArray[$a] - $basePartArray[$b];
    if($ina !== false && $inb === false) return -1;
    if($ina === false && $inb !== false) return 1;
    return strcmp($a, $b);
}

try{
    
    // Etape 1 : Récupérer les entrées.
    
    $templateName = false;
    if(isset($_GET['template'])) $templateName = $_GET['template'];
    if(!$templateName) throw new Exception('Il faut indiquer le template.');
    
    $titles = [];
    if(isset($_GET['titles'])) $titles = $_GET['titles'];
    if(!is_array($titles)) throw new Exception('Les titres ne sont pas un tableau');
    
    // Etape 2 : On ouvre le dossier des fonds et on remplit un array
    
    $pathname = 'resource/backgrounds/'.$templateName;
    $bgarray = [];
    
    _dirIteration($pathname, 0, $bgarray, '');
    
    // Etape 3, on trie l'array en question
    foreach(array_keys($bgarray) as $key){
        uksort($bgarray[$key], 'letterComparison');
    }
    
    // Etape 3 et demi : on trie l'array en fonction du type de base part
    uksort($bgarray, 'basePartComparison');
    
    //var_dump($bgarray);
    
    // Etape 4 : On affiche les arrays
    
    $first = true;
    
    foreach($bgarray as $key => $list){
        ?>
        <div class="fcm-selector" data-field="fcm-field-background-<?php echo $key; ?>">
            <div class="input-container largeforce">
                <?php
                echo '<h3 class="has-panel-top">';

                if(isset($titles[$key])) echo $titles[$key];
                else echo $key;

                echo '</h3>';
                ?>

                <div class="fcm-panel-top-toolbar">
                    <button class="fcm-toggle-button fcm-toggle-duoselector">
                        <i class="fa fa-adjust fa-fw"></i>
                        &nbsp; Hybride
                    </button>
                </div>
                
            </div>

            <input type="hidden" class="fcm-selector-field"
                   name="fcm-field-background-<?php echo $key; ?>"
                   id="fcm-field-background-<?php echo $key; ?>"
                   value="" />
            <div class="fcm-selector-inner" id="fcm-<?php echo $key; ?>-grid">

                <?php
                foreach($list as $letter => $path){
                    ?>
                    <button class="fcm-selector-button" data-value="<?php echo $letter; ?>">
                        <img src="<?php echo $path; ?>" alt="<?php echo $letter; ?>" />   
                    </button>
                    <?php
                }
        
                if(!$first){
                    ?>
                    <button class="fcm-selector-button" data-value="">
                          <i class="fa fa-times"></i>
                    </button>
                    <?php
                }
                ?>

            </div>
        </div>
            
        <?php
        
        $first = false;
    }
    
} catch (Exception $e) { // Gestion d'erreur
    echo $e->getMessage();
    echo 'Une erreur s\'est produite durant l\'obtension des fonds.';
    exit();
}

