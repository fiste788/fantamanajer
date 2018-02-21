<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Event\Event;

/**
 *
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoresController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['view']);
    }

    public function view($id)
    {
        $score = null;
        if ($id != null) {
            $appo = $this->Scores->get($id);
            $score = $this->Scores->get(
                $id,
                [
                'contain' => [
                    'Lineups' => [
                        'Dispositions' => [
                            'Members' => [
                                'Roles', 'Players', 'Clubs', 'Ratings' => function (\Cake\ORM\Query $q) use ($appo) {
                                    return $q->where(['matchday_id' => $appo->matchday_id]);
                                }
                            ]
                        ]
                    ]
                ]
                ]
            );
        }

        $this->set(
            [
            'success' => true,
            'data' => $score,
            '_serialize' => ['success', 'data']
            ]
        );
    }
}
