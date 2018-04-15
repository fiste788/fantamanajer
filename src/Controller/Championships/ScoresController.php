<?php
namespace App\Controller\Championships;

use App\Controller\AppController;

/**
 *
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoresController extends AppController
{
    public function index()
    {
        $this->Crud->action()->findMethod([
            'ranking' => [
                'championship_id' => $this->request->getParam('championship_id'),
                'scores' => true
            ]
        ]);
        return $this->Crud->execute();
    }
}
