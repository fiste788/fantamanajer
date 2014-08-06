<?php

namespace Lib;

 /**
 * Handles the incoming HTTP Request object.
 *
 * @package Rest_Runner
 * @copyright 2012 Roger E Thomas (http://www.rogerethomas.com)
 * @author Roger Thomas
 *
 */
class Request {

    /**
     *
     * @var Rest_Request
     */
    protected static $_request = null;

    /**
     * Holds the raw $_SERVER array
     *
     * @var array
     */
    private $serverArray = array ();

    /**
     * Raw array of all params, including $_GET, $_POST, and user params
     *
     * @var array
     */
    private $params = array ();

    /**
     * The raw $_GET array
     *
     * @var array
     */
    private $get = array ();

    /**
     * The raw $_POST array
     *
     * @var array
     */
    private $post = array ();

    /**
     * Body of response, or false if none manuall set
     *
     * @var mixed:boolean string
     */
    private $body = false;

    /**
     * Holds any manually set parameters when using:
     * $this->setParam()
     *
     * @var array
     */
    private $userParams = array ();

    /**
     * Initiate the class
     */
    public function __construct() {
        $this->_buildParams ();
        $this->serverArray = $_SERVER;
    }

    /**
     * Return instance of Rest_Request
     *
     * @return Request
     */
    public static function getRequest() {
        //ob_start ();
        if (null === self::$_request) {
            self::$_request = new self ();
        }

        return self::$_request;
    }

    /**
     * Retrieve the REQUEST_URI without and GET parameters
     *
     * @example /contact-us
     * @return string
     */
    public function getRequestUri() {
        if (isset ( $this->serverArray ['REQUEST_URI'] )) {
            $uri = $this->serverArray ['REQUEST_URI'];
        } else {
            $uri = "/";
        }
        if (strstr ( $uri, "?" )) {
            $uri = strstr ( $uri, "?", true );
        }
        return $uri;
    }

    /**
     * Is request via HTTPS
     *
     * @return boolean
     */
    public function isHttpsRequest() {
        if (empty ( $this->serverArray ['HTTPS'] ) || $this->serverArray ['HTTPS'] == "off") {
            return false;
        }

        return true;
    }

    /**
     * Retrieve the REQUEST_URI WITH and GET parameters
     *
     * @example /contact-us
     * @return string
     */
    public function getRawRequestUri() {
        if (isset ( $this->serverArray ['REQUEST_URI'] )) {
            $uri = $this->serverArray ['REQUEST_URI'];
        } else {
            $uri = "/";
        }

        return $uri;
    }

    /**
     * Retrieve a header from the request header stack and
     * optionally set a default value to use if key isn't
     * found.
     *
     * @param string $name
     * @param mixed:multitype $default
     * @return string
     */
    public function getHeader($name, $default = null) {
        if (empty ( $name )) {
            return $default;
        }

        $temp = 'HTTP_' . strtoupper ( str_replace ( '-', '_', $name ) );
        if (isset ( $this->serverArray [$temp] )) {
            return $this->serverArray [$temp];
        }

        if (function_exists ( 'apache_request_headers' )) {
            $method = 'apache_request_headers';
            $headers = $method ();
            if (isset ( $headers [$name] )) {
                return $headers [$name];
            }
            $header = strtolower ( $header );
            foreach ( $headers as $key => $value ) {
                if (strtolower ( $key ) == $name) {
                    return $value;
                }
            }
        }

        return $default;
    }

    /**
     * Return the REQUEST_METHOD from the SERVER global array
     *
     * @return string
     */
    public function getRequestMethod() {
        if (isset ( $this->serverArray ['REQUEST_METHOD'] )) {
            $m = $this->serverArray ['REQUEST_METHOD'];
        } else {
            $m = "GET";
        }

        return $m;
    }

    /**
     * Add a single parameter to the params stack
     *
     * @param string $name
     * @param mixed $value
     */
    public function setParam($name, $value) {
        $this->userParams [$name] = $value;
        $this->_buildParams ();
    }

    /**
     * Retrieve all request params (GET / POST and Manuall Set Params) as a
     * single array
     *
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Retrieve all POST params as an array
     *
     * @return array
     */
    public function getPostParams() {
        return $this->post;
    }

    /**
     * Retrieve all GET params as an array
     *
     * @return array
     */
    public function getGetParams() {
        return $this->get;
    }

    /**
     * Retrieve all request params (GET and POST) as a single array
     *
     * @return array
     */
    public function getParam($name, $default = null) {
        if (array_key_exists ( $name, $this->params )) {
            return $this->params [$name];
        }
        return $default;
    }

    /**
     * Check if the request is HTTP_GET
     *
     * @return boolean
     */
    public function isGet() {
        if (strtolower ( $this->getRequestMethod () ) == "get") {
            return true;
        }
        return false;
    }

    /**
     * Check if the request is HTTP_POST
     *
     * @return boolean
     */
    public function isPost() {
        if (strtolower ( $this->getRequestMethod () ) == "post") {
            return true;
        }
        return false;
    }

    /**
     * Check if the request is HTTP_PUT
     *
     * @return boolean
     */
    public function isPut() {
        if (strtolower ( $this->getRequestMethod () ) == "put") {
            return true;
        }
        return false;
    }

    /**
     * Check if the request is HTTP_DELETE
     *
     * @return boolean
     */
    public function isDelete() {
        if (strtolower ( $this->getRequestMethod () ) == "delete") {
            return true;
        }
        return false;
    }

    /**
     * Get the raw body, if any.
     * Else this will return false
     */
    public function getBody() {
        if ($this->body == false) {

            @$body = file_get_contents ( 'php://input' );

            if (strlen ( trim ( $body ) ) > 0) {
                $this->body = $body;
            } else {
                $this->body = false;
            }
        }

        if ($this->body == false) {
            return false;
        }

        return $body;
    }

    /**
     * Alias for self::getBody
     *
     * @see Rest_Request::getBody
     * @return string
     */
    public function getRawBody() {
        return $this->getBody ();
    }

    /**
     * Alias for self::getIp
     *
     * @see Rest_Request::getIp
     * @return string
     */
    public function getClientIp() {
        return $this->getIp ();
    }

    /**
     * Return the users IP Address
     *
     * @return string
     */
    public function getIp() {
        if (isset ( $this->serverArray ['HTTP_X_FORWARDED_FOR'] )) {
            return $this->serverArray ['HTTP_X_FORWARDED_FOR'];
        } else if (isset ( $this->serverArray ['HTTP_CLIENT_IP'] )) {
            return $this->serverArray ['HTTP_CLIENT_IP'];
        }

        return $this->serverArray ['REMOTE_ADDR'];
    }
    protected function _buildParams() {
        if (empty ( $this->get )) {
            foreach ( $_GET as $k => $v ) {
                $this->get [$k] = $v;
                $this->params [$k] = $v;
            }
        }

        if (empty ( $this->post )) {
            foreach ( $_POST as $k => $v ) {
                $this->post [$k] = $v;
                $this->params [$k] = $v;
            }
        }

        foreach ( $this->userParams as $k => $v ) {
            $this->params [$k] = $v;
        }
    }

    /**
     * Is the request an Ajax XMLHttpRequest?
     *
     * @return boolean
     */
    public function isXmlHttpRequest() {
        if ($this->serverArray ['X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            return true;
        }

        return false;
    }
} 
 
