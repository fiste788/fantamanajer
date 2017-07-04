<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
    'inputContainerError' => '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">{{content}}{{error}}</div>',
    'inputContainer' => '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">{{content}}</div>',
    'input' => '<input class="mdl-textfield__input" type="{{type}}" name="{{name}}" {{attrs}}/>',
    'label' =>'<label class="mdl-textfield__label" for="name">{{text}}</label>',
    'formGroup'=>'{{input}}{{label}}',
    'error' => '<span class="mdl-textfield__error">{{content}}</span>',
    'textarea' => '<textarea class="mdl-textfield__input" name="{{name}}"{{attrs}}>{{value}}</textarea>',
    'button' => '<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent"{{attrs}}>{{text}}</button>',
];
