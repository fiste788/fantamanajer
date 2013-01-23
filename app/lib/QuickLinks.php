<?php

namespace Fantamanajer\Lib;

class QuickLinks {

    public $next;
    public $prev;

    /**
     *
     * @var \Lib\Request
     */
    private $request;

    /**
     *
     * @var \AltoRouter
     */
    private $router;

    private $route;

    function __construct($request,$router,$route) {
        $this->prev = FALSE;
        $this->next = FALSE;
        $this->request = $request;
        $this->router = $router;
        $this->route = $route;
    }

    public function set($param, $array, $title, $other = NULL) {
        $keys = array_keys($array);
        $current = array_search($this->route['params'][$param], $keys);
        if (isset($keys[($idPrec = $current - 1)])) {
            $params = ($other != NULL) ? array_merge(array($param => $keys[$idPrec]), $other) : array($param => $keys[$idPrec]);
            $this->prev = new \stdClass();
            $this->prev->href = $this->router->generate($this->route['name'], $params);
            //$this->prev->href = Links::getLink($this->request->get('p'), $params);
            $this->prev->title = $title . ((!empty($array)) ? $array[$keys[$idPrec]] : $keys[$idPrec]);
        }
        if (isset($keys[($idSucc = $current + 1)])) {
            $params = ($other != NULL) ? array_merge(array($param => $keys[$idSucc]), $other) : array($param => $keys[$idSucc]);
            $this->next = new \stdClass();
            $this->next->href = $this->router->generate($this->route['name'], $params);
            //$this->next->href = Links::getLink($this->request->get('p'), $params);
            $this->next->title = $title . (!empty($array) ? $array[$keys[$idSucc]] : $keys[$idSucc]);
        }
    }

}

?>
