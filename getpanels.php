<?php require_once('include/functions.php');

// On parcours la liste des pannels à charger

if(isset($_GET['panels'])) {
    foreach($_GET['panels'] as $panel){
        $file = 'template/panels/'.$panel.'.php';
        if(file_exists($file)){
            require($file);
        }
    }
}

?>