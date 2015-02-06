<?php
class RestServer {
    private $oRequest;
    private $parameters;
    protected $format;

    public function makePassword($clearTextPassword) {
        // Encrypt password
        $password = crypt($clearTextPassword, base64_encode($clearTextPassword));
        return $password;
    }

    public function authentification(){

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            exit($this->requestStatus(401));
        } else {
            echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
            echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
            echo http_digest_parse($_SERVER['PHP_AUTH_DIGEST']);
        }
    }

    public function RestServer(){
        $this->oRequest = Request::getInstance();
        $this->format = 'json';
        $this->parseIncomingParams();
        // initialise json as default format

        if(isset($this->parameters['format'])) {
            $this->format = $this->parameters['format'];
        }
        return true;
    }

    private function parseIncomingParams() {
        $parameters = array();

        // first of all, pull the GET vars
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
        }

        // now how about PUT/POST bodies? These override what we got from GET
        $body = file_get_contents("php://input");
        $content_type = false;
        if(isset($_SERVER['CONTENT_TYPE'])) {
            $content_type = $_SERVER['CONTENT_TYPE'];
        } elseif (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            $content_type = $_SERVER['HTTP_CONTENT_TYPE'];
        }
        switch($content_type) {
            case "application/json":
                $body_params = json_decode($body);
                if($body_params) {
                    foreach($body_params as $param_name => $param_value) {
                        $parameters[$param_name] = $param_value;
                    }
                }
                $this->format = "json";
                break;
            case "application/x-www-form-urlencoded":
                parse_str($body, $postvars);
                foreach($postvars as $field => $value) {
                    $parameters[$field] = $value;
                }
                $this->format = "xml";
                break;
            default:
            break;
        }
        $this->parameters = $parameters;
    }

    public function getParams(){
        return $this->parameters;
    }

    public function requestStatus($code) {
        $status = array(
            200 => 'OK',
            401 => 'Unauthorized',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );

        $status[$code] = (isset($status[$code])) ? $status[$code] : $status[500];
        switch($this->getFormat()){
            case 'json':
                return json_encode(array('error' => $status[$code]));
            break;
            case 'xml':
                return $this->arrayToXml(array('error' =>$status[$code]));
            break;
            default:
                return $this->requestStatus(405);
            break;
        }
    }

    private function getController(){
        $ctrl = isset($this->oRequest->urlElement[1]) ? $this->oRequest->urlElement[1] : 'index';
        $name = $ctrl.'Controller';
        $file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
        if(!file_exists($file)) {
            die($this->requestStatus(405));
        }

        require_once $file;
        return new $name();
    }

    public function init(){
        $controller = $this->getController();
        $action = strtoupper($this->oRequest->method);
        $action .= isset($this->oRequest->urlElement[2]) ? $this->oRequest->urlElement[2] : null;
        if (false === is_callable(array($controller, $action))) {
            die($this->requestStatus(405));
        }
        die(call_user_func_array(array($controller,$action), array($this)));
    }

    public function getFormat(){
        return $this->format;
    }

    public function arrayToXml(array $array){
        return generate_valid_xml_from_array($array, 'restful');
    }
}


function generate_xml_from_array($array, $node_name) {
    $xml = '';

    if (is_array($array) || is_object($array)) {
        foreach ($array as $key=>$value) {
            if (is_numeric($key)) {
                $key = $node_name;
            }

            $xml .= '<' . $key . '>' . generate_xml_from_array($value, $node_name) . '</' . $key . '>';
        }
    } else {
        $xml = htmlspecialchars($array, ENT_QUOTES);
    }

    return $xml;
}

function generate_valid_xml_from_array($array, $node_block='nodes', $node_name='node') {
    $xml = '<?xml version="1.0" encoding="UTF-8" ?>';
    $xml .= '<' . $node_block . '>';
    $xml .= generate_xml_from_array($array, $node_name);
    $xml .= '</' . $node_block . '>';

    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($xml);
    $dom->formatOutput = TRUE;

    return $dom->saveXML();
}