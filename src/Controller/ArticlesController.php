<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\ArticlesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;
use DateTime;

/**
 * Articles Controller
 *
 * @property ArticlesTable $Articles
 */
class ArticlesController extends AppController
{

    /**
     * Index method
     *
     * @return Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Teams']
        ];
        $articles = $this->paginate($this->Articles);

        $this->set(compact('articles'));
        $this->set('_serialize', ['articles']);
    }
	
	/**
     * Index method
     *
     * @return void
     */
    public function indexByTeam($team_id) {
        $articles = $this->Articles->findByTeamId($team_id)->contain([
            'Teams'
        ])->all();
        //die(var_dump($articles));
        $this->set('articles', $articles);
        $this->render('index');
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return Response|null
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $article = $this->Articles->get($id);

        $this->set('article', $article);
        $this->set('_serialize', ['article']);
    }

    /**
     * Add method
     *
     * @return Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->data);
			$article->created_at = new DateTime();
            $article->team = $this->currentChampionship->findTeamByUser($this->request->session()->read('Auth.User.id'));
            $article->matchday = $this->currentMatchday;
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('The article has been saved.'));
                return $this->redirect(['action' => 'indexByTeam']);
            } else {
                $this->Flash->error(__('The article could not be saved. Please, try again.'));
            }
        }
        $teams = $this->Articles->Teams->find('list', ['limit' => 200]);
        $matchdays = $this->Articles->Matchdays->find('list', ['limit' => 200]);
        $this->set(compact('article', 'teams', 'matchdays'));
        $this->set('_serialize', ['article']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return Response|void Redirects on successful edit, renders view otherwise.
     * @throws NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $article = $this->Articles->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $article = $this->Articles->patchEntity($article, $this->request->data);
            if ($this->Articles->save($article)) {
				$message = __('The article has been saved.');
                $this->Flash->success($message);
				$teams = $this->Articles->Teams->find('list', ['limit' => 200]);
				$matchdays = $this->Articles->Matchdays->find('list', ['limit' => 200]);
				$this->set(compact('article', 'teams', 'matchdays','message'));
				$this->set('_serialize', ['article','message']);
				if(!$this->request->is('json'))
					return $this->redirect(['action' => 'index']);
            } else {
				$message = __('The article could not be saved. Please, try again.');
				$this->Flash->error($message);
				if($this->request->is('json')) {
					$this->set('message', $message);
					$this->set('errors', $article->errors());
					$this->set('_jsonOptions', JSON_FORCE_OBJECT);
					$this->set('_serialize', ['message','errors']);
				}
            }
        }
        
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return Response|null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article has been deleted.'));
        } else {
            $this->Flash->error(__('The article could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
