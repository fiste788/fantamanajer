<?php
namespace Lib;

class Pages {

    /**
     *
     * @var Page[]
     */
    var $pages;
    var $navbar;
    private $orderNavbar;
    private $rawNavbar;

    function __construct() {
        $this->pages = array();
        $this->navbar = array();
        $this->orderNavbar = array();
        $this->rawNavbar = array();
    }

    public function addPage($key, $title, $roles, $category, $order = FALSE, $js = NULL, $params = array()) {
        //$this->pages[$key] = (object) array('title' => $title, 'roles' => $roles, 'js' => $js, 'pages' => array());
        $this->pages[$key] = new Page($key, $title, $category, $roles, $js, $params);
        if (!empty($category)) {
            array_push($this->pages[$category]->pages, $key);
            if ($category == $key) {
                if (!isset($this->orderNavbar[$category])) {
                    $this->array_insert($this->orderNavbar, $category, (int) $order);
                    ksort($this->orderNavbar);
                }
                $this->rawNavbar[$category] = array();
                //array_splice($this->rawNavbar[$category], 0, 0, array($key));
            } elseif ($order != FALSE) {
                array_splice($this->rawNavbar[$category], $order, 0, array($key));
            }
        } else {
            array_push($this->pages[$key]->pages, $key);
        }
    }

    public function finalize() {
        foreach ($this->orderNavbar as $val) {
            $this->navbar[$val] = $this->rawNavbar[$val];
        }
    }

    function array_insert(&$array, $element, $position = NULL) {
        if (is_numeric($position) && $position < 0) {
            if ((count($array) + position) < 0) {
                $array = array_insert($array, $element, 0);
            } else {
                $array[count($array) + $position] = $element;
            }
        }
        elseif (is_numeric($position) && isset($array[$position])) {
            $part1 = array_slice($array, 0, $position, TRUE);
            $part2 = array_slice($array, $position, NULL, TRUE);
            $array = array_merge($part1, array($position => $element), $part2);
            foreach ($array as $key => $item) {
                if (is_null($item)) {
                    unset($array[$key]);
                }
            }
        }
        elseif (is_null($position)) {
            $array[] = $element;
        } elseif (!isset($array[$position])) {
            $array[$position] = $element;
        }
        return $array;
    }

}

 
