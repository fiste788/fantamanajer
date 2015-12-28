<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Lib\UploadHandler;
use Fantamanajer\Models as Models;
use FirePHP;
use Google_Client;
use Lib\FormException;

class UsersController extends ApplicationController {

    public function login() {
        if (!$_SESSION['logged']) {
            $email = $this->request->getParam('email');
            $password = $this->request->getParam('password');
            $googleToken = $this->request->getParam('google-token');
            if($googleToken != null) {
                $client = new Google_Client();
                $client->setAuthConfigFile(CONFIGDIR . 'client_secret.json');
                $ticket = $client->verifyIdToken($googleToken);
                
                die($ticket->getUserId());
            }elseif ($email != NULL && $password != NULL) {
               
                $this->auth->remember = ($this->request->getParam('remember') == 'on');
                FirePHP::getInstance()->log("redirect");
                if (!$this->auth->doLogin($email, md5($password))) {
                    die($email);
                    $this->setFlash(1, "Errore nel login");
                    $this->redirectTo('home');
                } else {
                    $this->redirectTo('teams_show', array('id' => $_SESSION['id']));
                }
            } elseif (isset($_COOKIE['auth_username']) && isset($_COOKIE['auth_key'])) {
                $this->auth->renewLogin(filter_input(INPUT_COOKIE, 'auth_username'), $_COOKIE['auth_key']);
            }
        }
    }

    public function logout() {
        if ($_SESSION['logged']) {
            $this->auth->logout();
        }
        $this->redirectTo('home');
    }

    public function edit() {
        if (($user = Models\User::getById($_SESSION['user_id'])) === FALSE) {
            $this->send404();
        }

        $this->templates['content']->assign('user', $user);
    }

    public function update() {
        if (($user = Models\User::getById($_SESSION['user_id'])) === FALSE) {
            $this->send404();
        }
        try {
            $password = $user->getPassword() == "" ? $user->getOriginalValues("password") : md5($user->getPassword());
            $user->setPassword($password);
            $user->save();
            $user->setFlash(self::FLASH_SUCCESS, "Modificato con successo");
            $this->redirectTo("team_show", array('id' => $_SESSION['user_id']));
        } catch (FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("edit");
        }
    }

    public function upload() {
        if ($_SESSION['logged']) {

            $options = array(
                'filename' => $_SESSION['user_id'] . '.jpg',
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
            $upload_handler = new UploadHandler($options);
            $this->response->setContentType('application/javascript');
        }
    }

}
