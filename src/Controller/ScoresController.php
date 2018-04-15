<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 *
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoresController extends AppController
{
    public function view($id)
    {
        $score = $this->Scores->get($id);
        $this->set(
            [
            'success' => true,
            'data' => $this->Scores->loadDetails($score),
            '_serialize' => ['success', 'data']
            ]
        );
    }
}
