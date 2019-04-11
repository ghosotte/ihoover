<?php
include_once './model/Manager.php';

$oManager = new Manager();
$row = 0;

try {
    if (($handle = fopen("input.txt", "r")) !== FALSE) {
        while (($aData = fgetcsv($handle, 50, " ")) !== FALSE) {
            if($row == 0) {
                $oManager->lectureGrille($aData);
            }
            if($row == 1) {
                $oManager->lecturePosition($aData);
            }
            if($row == 2) {
                $oManager->lectureCommandes($aData);
            }
            $row++;
        }
        fclose($handle);
    }

    echo $oManager->getPosition() . "\n";

} catch ( Exception $e ) {
    echo "Une erreur est survenue lors de la lecture du fichier : " . $e->getMessage() . "\n";
}

