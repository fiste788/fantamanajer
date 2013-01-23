<?php

namespace Fantamanajer\Controllers;

class UtenteController extends ApplicationController {

    public function login() {
        if (!$_SESSION['logged']) {
            if ($this->request->has('username') && $this->request->has('password')) {
                $this->auth->remember = ($this->request->get('remember') == 'on');
                \FirePHP::getInstance()->log("redirect");
                if (!$this->auth->doLogin($this->request->get('username'), md5($this->request->get('password'))))
                    $this->setFlash(1,"Errore nel login");
                else
                    $this->redirectTo('squadra_show',array('id'=>$_SESSION['id']));
                    //$this->request->goToUrl($this->router->generate('home'));
            }
            elseif (isset($_COOKIE['auth_username']) && isset($_COOKIE['auth_key']))
                $this->auth->renewLogin($_COOKIE['auth_username'], $_COOKIE['auth_key']);
        }
    }

    public function logout() {
        if($_SESSION['logged'])
            $this->auth->logout();
        $this->redirectTo('classifica');
    }

}

?>