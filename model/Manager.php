<?php

class Manager
{
    //Définition de la grille
    var $iNombreX, $iNombreY;

    //Position courante
    var $iPositionX, $iPositionY, $sOrientation;

    /**
     * Définition de la grille
     * @param $aData array
     * @throws Exception
     */
    public function lectureGrille($aData) {
        if(count($aData) != 2) {
            throw new Exception("2 coordonnees attendues pour la definition de la grille");
        }
        if(!ctype_digit($aData[0]) || !ctype_digit($aData[1])) {
            throw new Exception("Coordonnee attendue au format : int");
        }
        $this->iNombreX = $aData[0];
        $this->iNombreY = $aData[1];
    }

    /**
     * Lecture de la position initiale
     * @param $aData array
     * @throws Exception
     */
    public function lecturePosition($aData) {
        if(count($aData) != 3) {
            throw new Exception("3 coordonnees attendues pour le positionnement de l'aspirateur");
        }
        if(!ctype_digit($aData[0]) || !ctype_digit($aData[1])) {
            throw new Exception("Coordonnee attendue au format : int");
        }
        if(!in_array($aData[2], array('N', 'W', 'E', 'S'))) {
            throw new Exception("Orientation non reconnue");
        }
        if(!$this->isDeplacementPossible($aData[0], $aData[1])) {
            throw new Exception("Placement impossible a ces coordonnees");
        }
        $this->iPositionX = $aData[0];
        $this->iPositionY = $aData[1];
        $this->sOrientation = $aData[2];
    }

    /**
     * Lecture d'une suite de commande
     * @param $aData array
     */
    public function lectureCommandes($aData) {
        $sListeCommandes = $aData[0];

        for ($i=0; $i<strlen($sListeCommandes); $i++) {
            $sCommande = $sListeCommandes[$i];
            try {
                $this->deplacement($sCommande);
            } catch ( Exception $e ) {
                echo "Deplacement " . ($i+1) . " non possible : " . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * Récupère la position courante de l'aspirateur
     * @return string Position (X Y Orientation)
     */
    public function getPosition() {
        return $this->iPositionX . " " .  $this->iPositionY . " " . $this->sOrientation;
    }

    /**
     * Demande de déplacement
     * @param $sCommande Commande demandée
     * @throws Exception
     */
    private function deplacement($sCommande) {
        if(in_array($sCommande, array('D', 'G'))) {
            $this->sOrientation = $this->getNextOrientation($sCommande);
        } else if($sCommande == "A") {
            //Détermine les nouvelles coordonnees selon la position
            $iNewPositionX = $this->iPositionX;
            $iNewPositionY = $this->iPositionY;

            if($this->sOrientation == "N") {
                $iNewPositionY++;
            }
            else if($this->sOrientation == "E") {
                $iNewPositionX++;
            }
            else if($this->sOrientation == "W") {
                $iNewPositionX--;
            }
            else if($this->sOrientation == "S") {
                $iNewPositionY--;
            }
            if(!$this->isDeplacementPossible($iNewPositionX,$iNewPositionY)) {
                throw new Exception("Placement impossible a ces coordonnees");
            }
            $this->iPositionX = $iNewPositionX;
            $this->iPositionY = $iNewPositionY;

        }else{
            throw new Exception("Deplacement non reconnu : " . $sCommande);
        }
    }

    /**
     * Détermine s'il est possible ou non d'avancer
     * @param $iNextX coord X souhaitee
     * @param $iNextY coord Y souhaitee
     * @return bool
     */
    private function isDeplacementPossible($iNextX, $iNextY) {
        return($iNextX > 0  && $iNextY > 0
            && $iNextX <= $this->iNombreX  && $iNextY <= $this->iNombreY);
    }

    /**
     * Détermine l'orientation de l'aspirateur
     * @param $sCommande (Droite ou Gauche)
     * @return string nouvelle orientation
     */
    private function getNextOrientation($sCommande) {
        if($this->sOrientation == "N") {
            return $sCommande == "D" ? "E" : "W";
        }
        if($this->sOrientation == "E") {
            return $sCommande == "D" ? "S" : "N";
        }
        if($this->sOrientation == "W") {
            return $sCommande == "D" ? "N" : "S";
        }
        if($this->sOrientation == "S") {
            return $sCommande == "D" ? "W" : "E";
        }
    }
}