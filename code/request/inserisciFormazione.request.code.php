<?php
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(INCDBDIR . "giocatore.db.inc.php");
require_once(INCDBDIR . "punteggio.db.inc.php");
require_once(INCDBDIR . "voto.db.inc.php");

$filterUtente = Request::getInstance()->has('idUtente') ? Request::getInstance()->get('idUtente') : NULL;
$filterGiornata = Request::getInstance()->has('idGiornata') ? Request::getInstance()->get('idGiornata') : NULL;
$filterLega = Request::getInstance()->has('idLega') ? Request::getInstance()->get('idLega') : NULL;
if ($_SESSION['usertype'] == 'admin')
    $filterLega = $_SESSION['idLega'];

$formazione = Formazione::getFormazioneBySquadraAndGiornata($filterUtente, $filterGiornata);
if (!$formazione)
    $formazione = new Formazione();

$titolari = Request::getInstance()->getRawData('post', 'titolari');
$panchinari = Request::getInstance()->getRawData('post', 'panchinari');
$formazione->setIdGiornata($filterGiornata);
$formazione->setIdUtente($_SESSION['idUtente']);
if (Request::getInstance()->get('C') != 0)
    $formazione->setIdCapitano(Request::getInstance()->get('C'));
if (Request::getInstance()->get('VC') != 0)
    $formazione->setIdVCapitano(Request::getInstance()->get('VC'));
if (Request::getInstance()->get('VVC') != 0)
    $formazione->setIdVVCapitano(Request::getInstance()->get('VVC'));
$formazione->save(array('titolari' => $titolari, 'panchinari' => $panchinari));
if (Voto::checkVotiExist($filterGiornata)) {
    Punteggio::unsetPenalità($filterUtente, $filterGiornata);
    Punteggio::unsetPunteggio($filterUtente, $filterGiornata);
    Punteggio::calcolaPunti($formazione);
    /* $mailContent->assign('giornata',$filterGiornata);
      $mailContent->assign('squadra',$squadraDett->nome);
      $mailContent->assign('somma',$punteggiObj->getPunteggi($squadra,$giornata));
      $mailContent->assign('formazione',$giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($giornata,$squadra));

      $object = "Giornata: ". $giornata . " - Punteggio: " . $punteggiObj->getPunteggi($squadra,$giornata);
      //$mailContent->display(TPLDIR.'mail.tpl.php');
      $mailObj->sendEmail($squadraDett['nomeProp'] . " " . $squadraDett['cognome'] . "<" . $squadraDett['mail']. ">",$mailContent->fetch(TPLDIR.'mail.tpl.php'),$object); */
}
$message->success('Formazione caricata correttamente');
?>
