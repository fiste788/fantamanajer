<?php
declare(strict_types=1);

namespace App\Controller\Championships;

use App\Controller\ArticlesController as AppArticlesController;

/**
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppArticlesController
{
    public $paginate = [
        'limit' => 8,
    ];

    /**
     * Index
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function index()
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod(['byChampionshipId' => [
            'championship_id' => (int)$this->request->getParam('championship_id'),
        ]]);

        return $this->Crud->execute();
    }
}
