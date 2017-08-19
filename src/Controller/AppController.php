<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Entity\Championship;
use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
	
	/**
     *
     * @var Matchday
     */
    protected $currentMatchday;

    /**
     *
     * @var Season
     */
    protected $currentSeason;

    /**
     *
     * @var Championship
     */
    protected $currentChampionship;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
		$this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'Pages',
                'action' => 'index'
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email']
                ]
            ],
            'authError' => 'Did you really think you are allowed to see that?',
        ]);
        
        Configure::write('Config.timezone', 'Europe/Rome');
        $matchdays = TableRegistry::get("Matchdays");
        $championships = TableRegistry::get("Championships");
        $this->currentMatchday = $matchdays->findCurrent();
        $this->currentSeason = TableRegistry::get("Seasons")->get($this->currentMatchday->get('season_id'));
        $this->currentChampionship = $championships->get(1,['contain'=>'Leagues']);
        $endDate = $matchdays->getTargetCountdown();
        $this->set("controller_name", strtolower($this->modelClass));
        $this->set("view_name", $this->template);
        $this->set('timestamp', $endDate->getTimestamp());
        $this->set('endDate', date_parse($endDate->format("Y-m-d H:i:s")));
        $this->set('currentChampionship', $this->currentChampionship);
        $this->set('currentMatchday', $this->currentMatchday);
        $this->set('roles', TableRegistry::get('Roles')->find());
    }

    /**
     * Before render callback.
     *
     * @param EventbeforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
		if ($this->request->session()->read("championship") != null) {
            $this->set("currentChampionship", $this->request->session()->read("championship"));
        }
    }
    
    public function beforeFilter(Event $event) {
        $this->response->cors($this->request)->allowOrigin(['*'])->allowMethods(['POST', 'GET', 'PUT', 'DELETE', 'OPTIONS'])->allowHeaders(['origin', 'x-requested-with', 'content-type'])->build();
        if($this->RequestHandler->prefers('json'))
            $this->RequestHandler->renderAs($this, 'json');
        //$this->response->withHeader('Access-Control-Allow-Origin', '*');
        parent::beforeFilter($event);
    }
}
