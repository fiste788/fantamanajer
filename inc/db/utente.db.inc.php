<?php

require_once(TABLEDIR . 'Utente.table.db.inc.php');

class Utente extends UtenteTable {

    public static function login($username, $password) {
        $q = "SELECT * FROM utente WHERE username LIKE '" . $username . "'
				AND password = '" . $password . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        if (mysql_num_rows($exe) == 1)
            return TRUE;
        else
            return FALSE;
    }

    public static function logout() {
        session_unset();
    }

    public function save($parameters = NULL) {
        require_once(INCDIR . 'ImageWorkshop.php');
        if (isset($_FILES['logo'])) {
            $logo = (object) $_FILES['logo'];

            $filename = $this->getId() . '.jpg';
            $filepath = UPLOADDIR . $filename;
            if (file_exists($filepath))
                unlink($filepath);
            if (move_uploaded_file($logo->tmp_name, $filepath)) {
                $image = new PHPImageWorkshop\ImageWorkshop(array('imageFromPath' => $filepath));
                if ($image->getHeight() > 215)
                    $image->resizeInPixel(NULL, 215, TRUE);
                $image->save(UPLOADDIR . 'thumb/', $filename, TRUE, NULL, 80);
                $thumb = new PHPImageWorkshop\ImageWorkshop(array('imageFromPath' => $filepath));
                if ($thumb->getHeight() > 93)
                    $thumb->resizeInPixel(NULL, 93, TRUE);
                $thumb->save(UPLOADDIR . 'thumb-small/', $filename, TRUE, NULL, 80);
                FirePHP::getInstance()->log("caricato");
            }
        }
        return parent::save($parameters);
    }

    public static function getSquadraByUsername($username, $idUtente) {
        $q = "SELECT *
				FROM utente
				WHERE username LIKE '" . $username . "' AND id <> '" . $idUtente . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        $val = FALSE;
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $val = $row;
        return $val;
    }

    public static function getSquadraByNome($nome, $idUtente) {
        $q = "SELECT *
				FROM utente
				WHERE nome LIKE '" . $nome . "' AND id <> '" . $idUtente . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $val = FALSE;
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $val = $row;
        return $val;
    }

    public static function createRandomPassword() {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $i = 0;
        $pass = '';
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }

    public function getPunteggioByGiornata($idGiornata) {
        require_once(INCDBDIR . 'punteggio.db.inc.php');

        return Punteggio::getByUtenteAndGiornata($this, $idGiornata);
    }

    /**
     * @todo Check
     * @param type $array
     * @param type $message
     * @return boolean
     */
    public function check($array, $message) {

        /* if(empty($post->titolo) || empty($post->testo)) {
          $message->error("Non hai compilato correttamente tutti i campi");
          return FALSE;
          } */
        if (isset($_FILES['logo'])) {
            $logo = (object) $_FILES['logo'];
            $allowedTypes = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
            if (!in_array($logo->type, $allowedTypes)) {
                $message->error("File non valido");
                return FALSE;
            }
            if ($logo->size > 1000000) {
                $message->error("File più grande di 1MB");
                return FALSE;
            }
            if ($logo->error) {
                $message->error("Errore generico upload file");
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * Getter: id
     * @return Articolo[]
     */
    public function getArticoli() {
        require_once(INCDBDIR . 'articolo.db.inc.php');
        if (empty($this->articoli))
            $this->articoli = Articolo::getByField('idUtente', $this->getId());
        return $this->articoli;
    }

    /**
     * Getter: id
     * @return Giocatore[]
     */
    public function getGiocatori() {
        require_once(INCDBDIR . 'GiocatoreStatisticheTable.db.inc.php');
        if (empty($this->giocatori))
            $this->giocatori = GiocatoreStatistiche::getByField('idUtente', $this->getId());
        return $this->giocatori;
    }

    /**
     * Getter: id
     * @return Evento[]
     */
    public function getEventi() {
        require_once(INCDBDIR . 'evento.db.inc.php');
        if (empty($this->eventi))
            $this->eventi = Evento::getByField('idUtente', $this->getId());
        return $this->eventi;
    }

}

?>
