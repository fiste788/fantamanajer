<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\Event;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\SelectionsTable $Selections
 */
class SelectionsController extends AppController
{
    /**
     * add
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function add(): ResponseInterface
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event): void {
                /** @var \App\Model\Entity\Selection $selection */
                $selection = $event->getSubject()->entity;
                $selection->matchday_id = $this->currentMatchday->id;
                $selection->active = true;

                $this->Selections->updateQuery()->set(['active' => null])->where([
                    'team_id' => $selection->team_id,
                    'matchday_id' => $selection->matchday_id,
                    'active' => true,
                ])->execute();
            },
        );

        return $this->Crud->execute();
    }
}
