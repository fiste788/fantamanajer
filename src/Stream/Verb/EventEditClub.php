<?php
namespace App\Model\Entity;

use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Event Entity.
 *
 * @property int $article_id
 */
class EventEditClub extends Event
{
    protected function processEvent()
    {
        $article = TableRegistry::get('Articles')->get($this->external);
        $this->title = $this->team->name . ' ha rilasciato una conferenza stampa intitolata ' . $article->title;
        $this->body = $article->body;
        $this->icon = 'message';
        $this->link = [
            'controller' => 'Articles',
            'action' => 'view'
        ];
    }
}
