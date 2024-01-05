<?php
declare(strict_types=1);

namespace App\Controller\Championships;

use App\Controller\ArticlesController as AppArticlesController;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppArticlesController
{
    /**
     * Pagination
     *
     * @var array<string, mixed>
     */
    protected array $paginate = [
        'limit' => 8,
    ];

    /**
     * Index
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function index(): ResponseInterface
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod([
            'byChampionshipId' => [
                'championshipId' => (int)$this->request->getParam('championship_id'),
            ],
        ]);

        return $this->Crud->execute();
    }
}
