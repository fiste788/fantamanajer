<?php

namespace Lib;

class Page {

    var $name;
    var $title;
    var $category;
    var $roles;
    var $js;
    var $params = array();
    var $pages = array();

    function __construct($name, $title, $category, $roles, $js, $params) {
        $this->name = $name;
        $this->title = $title;
        $this->category = $category;
        $this->roles = $roles;
        $this->js = $js;
        $this->params = $params;
    }

}
