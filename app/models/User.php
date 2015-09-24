<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\UsersTable;
use Fantamanajer\Models\View\GiocatoreStatistiche;
use FirePHP;
use Lib\Database\ConnectionFactory;
use Lib\FormException;
use PDO;
use PHPImageWorkshop\ImageWorkshop;

class User extends UsersTable {

    public static function getByIdLegaLite($idLega) {
        $q = "SELECT id,nomeSquadra
                FROM utente
                WHERE idLega = :idLega";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idLega", $idLega, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getId()] = $obj;
        return $values;
    }

    public static function login($username, $password) {
        $q = "SELECT *
                FROM utente
                WHERE username LIKE :username AND password = :password";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":username", $username);
        $exe->bindValue(":password", $password);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->rowCount() == 1;
    }

    public static function logout() {
        session_unset();
    }

    public function save(array $parameters = NULL) {
        if (isset($_FILES['logo'])) {
            $logo = (object) $_FILES['logo'];
            $filename = $this->getId() . '.jpg';
            $filepath = UPLOADDIR . $filename;
            if (file_exists($filepath))
                unlink($filepath);
            if (move_uploaded_file($logo->tmp_name, $filepath)) {
                $image = new ImageWorkshop(array('imageFromPath' => $filepath));
                if ($image->getHeight() > 215)
                    $image->resizeInPixel(NULL, 215, TRUE);
                $image->save(UPLOADDIR . 'thumb/', $filename, TRUE, NULL, 80);
                $thumb = new ImageWorkshop(array('imageFromPath' => $filepath));
                if ($thumb->getHeight() > 93)
                    $thumb->resizeInPixel(NULL, 93, TRUE);
                $thumb->save(UPLOADDIR . 'thumb-small/', $filename, TRUE, NULL, 80);
            }
        }
        return parent::save($parameters);
    }

    public static function getSquadraByUsername($username, $idUtente) {
        $q = "SELECT *
				FROM utente
				WHERE username LIKE :username AND id <> :idUtente";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":username", $username);
        $exe->bindValue(":idUtente", $idUtente,PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchObject(__CLASS__);
    }

    public static function getSquadraByNome($nome, $idUtente) {
        $q = "SELECT *
				FROM utente
				WHERE nome LIKE :nome AND id <> :idUtente";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":nome", $nome);
        $exe->bindValue(":idUtente", $idUtente);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchObject(__CLASS__);
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
        return Punteggio::getByUtenteAndGiornata($this, $idGiornata);
    }

    /**
     * @todo Check
     * @param array $array
     * @return boolean
     */
    public function check(array $array) {
        if (isset($_FILES['logo'])) {
            $logo = (object) $_FILES['logo'];
            $allowedTypes = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
            if (!in_array($logo->type, $allowedTypes))
                throw new FormException("File non valido");
            if ($logo->size > 1000000)
                throw new FormException("File più grande di 1MB");
            if ($logo->error)
                throw new FormException("Errore generico upload file");
        }
        return TRUE;
    }

    /**
     * Getter: id
     * @return Articolo[]
     */
    public function getArticoli() {
        if (empty($this->articoli))
            $this->articoli = Articolo::getByField('idUtente', $this->getId());
        return $this->articoli;
    }

    /**
     * Getter: id
     * @return Giocatore[]
     */
    public function getGiocatori() {
        if (empty($this->giocatori))
            $this->giocatori = GiocatoreStatistiche::getByField('idUtente', $this->getId());
        return $this->giocatori;
    }

    /**
     * Getter: id
     * @return Evento[]
     */
    public function getEventi() {
        if (empty($this->eventi))
            $this->eventi = Evento::getByField('idUtente', $this->getId());
        return $this->eventi;
    }

}

 