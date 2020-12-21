<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 */
/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
    /**
     * Defines DS as short form of DIRECTORY_SEPARATOR.
     *
     * @var string
     */
    define('DS', (string) DIRECTORY_SEPARATOR);
}
/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */
/**
 * The full path to the directory which holds "src", WITHOUT a trailing DS.
 * @var string
 */
define('ROOT', dirname(__DIR__));
/**
 * The actual directory name for the application directory. Normally
 * named 'src'.
 * @var string
 */
define('APP_DIR', 'src');
/**
 * Path to the application's directory.
 * @var string
 */
define('APP', ROOT . DS . APP_DIR . DS);
/**
 * Path to the config directory.
 * @var string
 */
define('CONFIG', ROOT . DS . 'config' . DS);
/**
 * File path to the webroot directory.
 *
 * To derive your webroot from your webserver change this to:
 *
 * `define('WWW_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], DS) . DS);`
 * @var string
 */
define('WWW_ROOT', ROOT . DS . 'webroot' . DS);
/**
 * Path to the tests directory.
 * @var string
 */
define('TESTS', ROOT . DS . 'tests' . DS);
/**
 * Path to the temporary files directory.
 * @var string
 */
define('TMP', ROOT . DS . 'tmp' . DS);
/**
 * Path to the logs directory.
 * @var string
 */
define('LOGS', ROOT . DS . 'logs' . DS);
/**
 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.
 * @var string
 */
define('CACHE', TMP . 'cache' . DS);
/**
 * Path to the resources directory.
 * @var string
 */
define('RESOURCES', ROOT . DS . 'resources' . DS);
/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * CakePHP should always be installed with composer, so look there.
 * @var string
 */
define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
/**
 * Path to the cake directory.
 * @var string
 */
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
/**
 * @var string
 */
define('CAKE', CORE_PATH . 'src' . DS);

/**
 * File path to the ratings directory.
 * @var string
 */
define('RATINGS', RESOURCES . 'ratings' . DS);
/**
 * @var string
 */
define('RATINGS_CSV', RATINGS . 'csv' . DS);

define('IMG', WWW_ROOT . 'img' . DS);
/**
 * @var string
 */
define('IMG_CLUBS', IMG . 'clubs' . DS);
/**
 * @var string
 */
define('IMG_TEAMS', IMG . 'teams' . DS);
/**
 * @var string
 */
define('IMG_PLAYERS', IMG . 'players' . DS);
