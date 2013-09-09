<?php

namespace Fantamanajer\Controllers;

class UtenteController extends ApplicationController {

    public function login() {
        if (!$_SESSION['logged']) {
            if ($this->request->getParam('username') != NULL && $this->request->getParam('password') != NULL) {
                $this->auth->remember = ($this->request->getParam('remember') == 'on');
                \FirePHP::getInstance()->log("redirect");
                if (!$this->auth->doLogin($this->request->getParam('username'), md5($this->request->getParam('password')))) {
                    $this->setFlash(1,"Errore nel login");
                    $this->redirectTo('home');
                }
                else {
                    $this->redirectTo('squadra_show',array('id'=>$_SESSION['id']));
                }
                    //$this->request->goToUrl($this->router->generate('home'));
            }
            elseif (isset($_COOKIE['auth_username']) && isset($_COOKIE['auth_key'])) {
                $this->auth->renewLogin(filter_input(INPUT_COOKIE, 'auth_username'), $_COOKIE['auth_key']);
            }
        }
    }

    public function logout() {
        if($_SESSION['logged']) {
            $this->auth->logout();
        }
        $this->redirectTo('classifica');
    }
    
    public function edit() {
        if(($utente = \Fantamanajer\Models\Utente::getById($_SESSION['idUtente'])) === FALSE)
            $this->send404();

        $this->templates['content']->assign('utente',$utente);
    }
    
    public function update() {
        if(($utente = \Fantamanajer\Models\Utente::getById($_SESSION['idUtente'])) === FALSE)
            $this->send404();
        try {
            $password = $utente->getPassword() == "" ? $utente->getOriginalValues("password") : md5($utente->getPassword());
            $utente->setPassword($password);
            $utente->save();
            $this->setFlash(self::FLASH_SUCCESS, "Modificato con successo");
            $this->redirectTo("squadra_show",array('id'=>$_SESSION['idUtente']));
        } catch(\Lib\FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("edit");
        }
    }

    public function upload() {
        if ($_SESSION['logged']) {

            $options = array(
                'filename' => $_SESSION['idUtente'] . '.jpg',
                'upload_dir' => UPLOADDIR,
                'upload_url' => UPLOADURL,
                'image_versions' => array(
                    '' => array(
                        'max_width' => 1920,
                        'max_height' => 1200,
                        'jpeg_quality' => 95
                    ),
                    'thumb' => array(
                        'max_height' => 215,
                        'max_width' => 1000,
                        'jpeg_quality' => 80
                    ),
                    'thumb-small' => array(
                        'max_width' => 1000,
                        'max_height' => 93
                    )
                )
            );
            $upload_handler = new \Fantamanajer\Lib\UploadHandler($options);
            $this->response->setContentType('application/javascript');
        }
    }

}

