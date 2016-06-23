<?php

define('DEBUG', false);
ini_set('xdebug.var_display_max_depth', 5);

/**
 * Retourne la taille maximum d'upload en octets
 * Code from Drupal
 */
function file_upload_max_size() {
    static $max_size = -1;

    if ($max_size < 0) {
        // Start with post_max_size.
        $max_size = parse_size(ini_get('post_max_size'));

        // If upload_max_size is less, then reduce. Except if upload_max_size is
        // zero, which indicates no limit.
        $upload_max = parse_size(ini_get('upload_max_filesize'));
        if ($upload_max > 0 && $upload_max < $max_size) {
            $max_size = $upload_max;
        }
    }
    return $max_size;
}



/**
 * Convertit la taille formatée en octets
 * Code from Drupal
 */
function parse_size($size) {
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
    $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
    if ($unit) {
        // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    }
    else {
        return round($size);
    }
}



/**
 * Classe pour récupérer les erreur d'upload
 * Récupéré sur la documentation officielle PHP
 */
class UploadException extends Exception 
{ 
    public function __construct($code) { 
        $message = $this->codeToMessage($code); 
        parent::__construct($message, $code); 
    } 

    private function codeToMessage($code) 
    { 
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = 'Le fichier est trop volumineux (Erreur '.$code.')'; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = 'Le fichier est trop volumineux (Erreur '.$code.')';
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = 'Erreur lors de l\'envoi du fichier. Réessayez. (Erreur '.$code.')';
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = 'Aucun fichier n\'a été envoyé (Erreur '.$code.')';
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = 'Erreur serveur. Merci de nous en parler sur le forum. (Erreur '.$code.')';
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = 'Erreur serveur. Merci de nous en parler sur le forum. (Erreur '.$code.')';
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = 'Erreur serveur. Merci de nous en parler sur le forum. (Erreur '.$code.')';
                break; 

            default: 
                $message = 'Erreur inconnue. Merci de nous en parler sur le forum. (Code d\'erreur : '.$code.')';
                break; 
        } 
        return $message; 
    } 
}






/**
 * Convertit un objet en json lisible
 * Récupéré sur la documentation officielle PHP
 */
function json_readable_encode($in, $indent = 0, $from_array = false)
{
    $_myself = __FUNCTION__;
    $_escape = function ($str)
    {
        return preg_replace("!([\b\t\n\r\f\"\\'])!", "\\\\\\1", $str);
    };

    $out = '';

    foreach ($in as $key=>$value)
    {
        $out .= str_repeat("\t", $indent + 1);
        $out .= "\"".$_escape((string)$key)."\": ";

        if (is_object($value) || is_array($value))
        {
            $out .= "\n";
            $out .= $_myself($value, $indent + 1);
        }
        elseif (is_bool($value))
        {
            $out .= $value ? 'true' : 'false';
        }
        elseif (is_null($value))
        {
            $out .= 'null';
        }
        elseif (is_string($value))
        {
            $out .= "\"" . $_escape($value) ."\"";
        }
        else
        {
            $out .= $value;
        }

        $out .= ",\n";
    }

    if (!empty($out))
    {
        $out = substr($out, 0, -2);
    }

    $out = str_repeat("\t", $indent) . "{\n" . $out;
    $out .= "\n" . str_repeat("\t", $indent) . "}";

    return $out;
}


/**
 * Insère un array dans un autre à la position $position.
 * Code from PHP community manual
 * http://php.net/manual/fr/function.array-splice.php
 */
function array_insert (&$original, $position, $inserted) { 
    array_splice( $original, $position, 0, $inserted );
} 
