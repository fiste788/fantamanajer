<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Models\Article;
use FirePHP;
use Lib\FormException;

class ArticlesController extends ApplicationController {

    public function index() {
        $articles = Article::getByChampionship($this->currentChampionship->getId()); 
        $this->templates['content']->assign('articles', $articles);
    }
    
    public function team_index() {
        $articles = Article::getByTeam($this->request->getParam('team_id')); 
        $this->templates['content']->assign('articles', $articles);
        $this->view = 'index';
        $this->noLayout = true;
    }

    public function build() {
        $this->templates['content']->assign('article', new Article());
    }

    public function create() {
        try {
            $article = new Article();
            $article->setTeamId($_SESSION['team']->id);
            $article->setMatchday($this->currentMatchday);
            $article->setCreatedAt('now');
            $article->save();
            $this->redirectTo("articles");
        } catch(FormException $e) {
            die($e->getMessage());
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("articles_new");
        }

    }

    public function edit() {
        $article = Article::getById($this->route['params']['id']);
        FirePHP::getInstance()->log($article);
        if (($article) == FALSE) {
            $this->send404();
        }
        $this->templates['content']->assign('article', $article);
    }

    public function update() {
        try {
            $article = Article::getById($this->route['params']['id']);
            $article->save();
            $this->setFlash(self::FLASH_SUCCESS, "Modificato con successo");
            $this->redirectTo("articles");
        } catch(FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("articles");
        }
    }

    public function delete() {
        $article = Article::getById($this->route['params']['id']);
        $article->delete();
        $this->redirectTo("articles");
    }
}

 