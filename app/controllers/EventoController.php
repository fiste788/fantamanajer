<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Models\Evento;
use Fantamanajer\Models\Lega;
use FirePHP;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

class EventoController extends ApplicationController {

    public function index() {
        $eventi = Evento::getEventi($_SESSION['legaView'], $this->request->getParam('evento'), 0, 25);
        FirePHP::getInstance()->log($this->request->getParam('evento'));
        $this->templates['content']->assign('eventi', $eventi);
    }

    public function rss() {
        $this->response->setContentType("text/xml;charset=\"utf-8\"");
        $feed = new Feed();
        $leghe = Lega::getList();
        foreach($leghe as $key => $lega) {
            $eventi = Evento::getEventi($key, NULL, 0, 50);
            $channel = new Channel();
            $channel->title("Fantamanajer - " . $lega->nome);
            $channel->description("Feed per la lega " . $lega->nome);
            $channel->url("http://fantamanajer.it");
            $channel->copyright("Copiright 2013 fantamanajer.it");
            $channel->language("IT-it");
            $channel->lastBuildDate($eventi[0]->data->getTimestamp());
            foreach ($eventi as $key2 => $evento) {
                $item = new Item();
                $item->title($evento->titolo);
                $item->pubDate($evento->data->getTimestamp());
                $item->description($evento->content);
                if(!empty($evento->link)) {
                    $item->url($evento->link);
                } else {
                    $item->url($this->router->generate("feed") . "#evento-" . $evento->id);
                }
                $channel->addItem($item);
            }
            $feed->addChannel($channel);
        }
        echo $feed;
    }

}
