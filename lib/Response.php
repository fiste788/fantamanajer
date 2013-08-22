<?php

namespace Lib;

/**
 * Handles the outgoing HTTP Response object.
 *
 * @package Rest_Runner
 * @copyright 2012 Roger E Thomas (http://www.rogerethomas.com)
 * @author Roger Thomas
 *
 */
class Response {

    /**
     *
     * @var string
     */
    const DEFAULT_CONTENT_TYPE = 'text/html';

    /**
     *
     * @var Rest_Response
     */
    //protected static $_response = null;

    /**
     *
     * @var string
     */
    protected $_rawBody = null;

    /**
     *
     * @var string
     */
    protected $_contentType = null;

    /**
     *
     * @var string
     */
    protected $_body = null;
    
    public function __construct() {
        ob_start();
    }
    
    /**
     * Assign a HTTP Response code.
     *
     * @param integer $code
     * @return Rest_Response
     */
    public function setHttpCode($code) {
        $http_codes = $this->_getHttpResponseCodes ();
        if (! array_key_exists ( $code, $http_codes )) {
            $string = 'HTTP/1.0 500 ' . $http_codes [500];
        } else {
            $string = 'HTTP/1.0 ' . $code . ' ' . $http_codes [$code];
        }

        header ( $string );
    }

    /**
     * Associated array of HTTP Response codes
     *
     * @return array
     */
    protected function _getHttpResponseCodes() {
        return $http_codes = array (
                100 => 'Continue',
                101 => 'Switching Protocols',
                102 => 'Processing',
                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',
                207 => 'Multi-Status',
                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                306 => 'Switch Proxy',
                307 => 'Temporary Redirect',
                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                417 => 'Expectation Failed',
                418 => 'I\'m a teapot',
                422 => 'Unprocessable Entity',
                423 => 'Locked',
                424 => 'Failed Dependency',
                425 => 'Unordered Collection',
                426 => 'Upgrade Required',
                449 => 'Retry With',
                450 => 'Blocked by Windows Parental Controls',
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported',
                506 => 'Variant Also Negotiates',
                507 => 'Insufficient Storage',
                509 => 'Bandwidth Limit Exceeded',
                510 => 'Not Extended'
        );
    }

    /**
     * Return a header from the stack, or empty string
     * if not set.
     *
     * @param string $name
     * @return string
     */
    public function getHeader($name) {
        $headers = $this->getHeaders ();
        if (! array_key_exists ( $name, $headers )) {
            return "";
        }
        return $headers [$name];
    }

    /**
     * Assign a response header to be sent when issuing
     * the http response
     *
     * @param string $name
     * @param string $value
     * @param boolean $overrideExisting
     */
    public function setHeader($name, $value, $overrideExisting = true) {
        if (strtolower ( $name ) == "content-type") {
            $this->_contentType = $value;
        }

        if (! empty ( $value )) {
            $string = $name . ":" . $value;
        }

        if ($overrideExisting == false) {
            $headers = $this->getHeaders ();
            if (! array_key_exists ( $name, $headers )) {
                header ( $string );
            }
        } else {
            header ( $string );
        }
    }

    /**
     * Remove all headers from stack.
     * Word of warning, this will clear everything and
     * your browser would download the content versus
     * displaying it. At least set the content-type after
     */
    public function clearAllHeaders() {
        header_remove ();
    }

    /**
     * Remove header by the name of $name
     * from the stack
     */
    public function unsetHeader($name) {
        header_remove ( $name );
    }

    /**
     * Assign a content type (application/json etc)
     *
     * @param string $type
     */
    public function setContentType($type) {
        $this->setHeader ( "Content-Type", $type . "; charset=UTF-8" );
    }

    /**
     * Manually assign the body content to a response.
     *
     * @param string $content
     */
    public function setBody($content) {
        $this->_body = $content;
    }

    /**
     * Retrieve the array of response headers
     * from the stack.
     *
     * @return array
     */
    public function getHeaders() {
        $arh = array ();
        $headers = headers_list ();
        foreach ( $headers as $header ) {
            $header = explode ( ":", $header );
            $arh [array_shift ( $header )] = trim ( implode ( ":", $header ) );
        }
        return $arh;
    }

    /**
     * Retrieve the Content-type from the stack of
     * response headers
     * from the stack.
     *
     * @return array
     */
    public function getContentType() {
        $arh = $this->getHeaders ();
        foreach ( $arh as $k => $v ) {
            if (strtolower ( $k ) == "content-type") {
                return str_replace ( "; charset=UTF-8", "", $v );
            }
        }
        return self::DEFAULT_CONTENT_TYPE;
    }

    /**
     * Set the required headers and send a JSON response
     *
     * @param array $data
     */
    public function sendJsonResponse($data) {
        if (is_array ( $data ) || is_object ( $data )) {
            $final = json_encode ( $data );
        } else {
            $final = $data;
        }
        $this->clearAllHeaders ();
        $this->setContentType ( "application/json" );
        $this->setHeader ( "Content-Length", strlen ( $final ) );
        $this->setBody ( $final );
        $this->sendResponse ();
    }

    /**
     * Immediately send the response, including
     * any headers that need to be sent.
     */
    public function sendResponse() {
        if (empty ( $this->_contentType )) {
            $this->setContentType ( self::DEFAULT_CONTENT_TYPE );
        }
        $body = ob_get_clean ();
        if (! empty ( $this->_body )) {
            $body = $this->_body;
        } else {
            @ob_flush ();
        }
        print (@$body) ;
        @ob_flush ();
        exit;
    }
} 

 
