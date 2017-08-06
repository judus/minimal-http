<?php namespace Maduser\Minimal\Http;

use Maduser\Minimal\Http\Contracts\RequestInterface;

/**
 * Class Request
 *
 * @package Maduser\Minimal\Http
 */
class Request implements RequestInterface
{
    /**
     * @var
     */
    private $scheme;

    /**
     * @var
     */
    private $http;

    /**
     * The server name with port unless it is 80
     *
     * @var
     */
    private $host;

    /**
     * The server name
     *
     * @var
     */
    private $server;

    /**
     * The server port
     *
     * @var
     */
    private $port;

    /**
     * The remote address or ip
     *
     * @var
     */
    private $ip;

    /**
     * The current uri string
     *
     * @var
     */
    private $uriString;

    /**
     * The base uri string, if system is not in document root
     *
     * @var
     */
    private $baseUri;

    /**
     * The current http method
     *
     * @var
     */
    private $requestMethod;

    /**
     * Holds all the uri segments until ? or #
     *
     * @var array
     */
    private $segments = [];

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function get($key = null)
    {
        if ($key) {
            return $_GET['key'];
        }

        return $_GET;
    }

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function post($key = null)
    {
        if ($key) {
            return $_POST['key'];
        }

        return $_POST;
    }

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function request($key = null)
    {
        if ($key) {
            return $_REQUEST['key'];
        }

        return $_REQUEST;
    }

    /**
     * @param null $key
     *
     * @return array
     */
    public function files($key = null)
    {
        $keys = [];
        foreach ($_FILES['name'] as $key => $value) {
            $keys[] = $key;
        };

        $newFilesArray = [];
        $i = 0;
        foreach ($keys as $key) {
            $newFilesArray[$key] = [
                'name' => $_FILES['name'][$key],
                'type' => $_FILES['type'][$key],
                'tmp_name' => $_FILES['tmp_name'][$key],
                'error' => $_FILES['error'][$key],
                'size' => $_FILES['size'][$key]
            ];
            $i++;
        }

        return $newFilesArray;
    }




    /**
     * @return mixed
     */
    public function getHttp()
    {
        if (is_null($this->http)) {
            $this->setHttp(
                empty($this->getScheme()) ? 'http://' : $this->getScheme() . '://'
            );
        }

        return $this->http;
    }

    /**
     * @param mixed $http
     *
     * @return Request
     */
    public function setHttp($http)
    {
        $this->http = $http;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScheme()
    {
        if (is_null($this->scheme)) {
            $this->setScheme(
                isset($_SERVER['REQUEST_SCHEME']) ?
                    $_SERVER['REQUEST_SCHEME'] : ''
            );
        }

        return $this->scheme;
    }

    /**
     * @param mixed $scheme
     *
     * @return Request
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        if (is_null($this->host)) {
            $host = $this->getServer();
            empty($this->getPort()) || $host .= ':' . $this->getPort();
            $this->setHost($host);
        }

        return $this->host;
    }

    /**
     * @param mixed $host
     *
     * @return Request
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        if (is_null($this->server)) {
            $this->setServer(isset($_SERVER['SERVER_NAME']) ?
                $_SERVER['SERVER_NAME'] : '');
        }

        return $this->server;
    }

    /**
     * @param mixed $server
     *
     * @return Request
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        if (is_null($this->port)) {
            $this->setPort(
                isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80
                    ? $_SERVER['SERVER_PORT'] : ''
            );
        }

        return $this->port;
    }

    /**
     * @param mixed $port
     *
     * @return Request
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        if (is_null($this->ip)) {
            $this->setIp($_SERVER['REMOTE_ADDR']);
        }

        return $this->ip;
    }

    /**
     * @param mixed $ip
     *
     * @return Request
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Setter $uriString
     *
     * @param $str
     */
    public function setUriString(string $str)
    {
        $this->uriString = $str;
    }

    /**
     * Getter $uriString
     *
     * @return string
     */
    public function getUriString()
    {
        return $this->uriString;
    }

    /**
     * @return mixed
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param mixed $baseUri
     *
     * @return Request
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * Setter $requestMethod
     *
     * @param $str
     */
    public function setRequestMethod(string $str)
    {
        $this->requestMethod = $str;
    }

    /**
     * Getter $requestMethod
     *
     * @return mixed
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * Setter $segments
     *
     * @param array $segments
     */
    public function setSegments(array $segments)
    {
        $this->segments = $segments;
    }

    /**
     * Getter $segments
     *
     * @return array
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * Request constructor
     * sets $requestMethod
     * sets $uriString
     * sets $segments
     */
    public function __construct()
    {
        $this->fetchServerInfo();
        $this->fetchRequestMethod();
        $this->fetchUriString();
        $this->explodeSegments();
    }

    /**
     *
     */
    public function fetchServerInfo()
    {
        $this->getHost();
        $this->getScheme();
        $this->getHttp();
        $this->getIp();
    }

    /**
     * Fetch the http method
     */
    public function fetchRequestMethod()
    {
        if (php_sapi_name() == 'cli' or defined('STDIN')) {
            $this->setRequestMethod('CLI');

            return;
        }

        if (isset($_POST['_method'])) {
            if (
                strtoupper($_POST['_method']) == 'PUT' ||
                strtoupper($_POST['_method']) == 'PATCH' ||
                strtoupper($_POST['_method']) == 'DELETE'
            ) {
                $this->setRequestMethod(strtoupper($_POST['_method']));

                return;
            }
        }

        $this->setRequestMethod($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Fetches the REQUEST_URI and sets $uriString
     */
    public function fetchUriString()
    {
        if (php_sapi_name() == 'cli' or defined('STDIN')) {
            $this->setUriString($this->parseCliArgs());

            return;
        }

        // Fetch request string (apache)
        $uri = $_SERVER['REQUEST_URI'];
        $uri = parse_url($uri)['path'];

        // Further cleaning of the uri
        $uri = str_replace(array('//', '../'), '/', trim($uri, '/'));

        if (php_sapi_name() != 'cli' && !defined('STDIN')) {

            $dirname = dirname($_SERVER['SCRIPT_FILENAME']);
            $dirname !== '.' || $dirname = '';

            $diff = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dirname);

            $this->setBaseUri($diff);

            $uri = trim(str_replace($diff, '', '/' . $uri), '/');
        }

        if (empty($uri)) {
            $uri = '/';
        }


        $this->setUriString($uri);
    }

    /**
     * Formats cli args like a uri
     *
     * @return string
     */
    public function parseCliArgs()
    {
        $args = array_slice($_SERVER['argv'], 1);

        return $args ? '/' . implode('/', $args) : '';
    }

    /**
     * Filter or replace bad chars from uri
     *
     * @param $uri
     *
     * @return mixed
     */
    public function filterUri($uri)
    {
        $bad = array('$', '(', ')', '%28', '%29');
        $good = array('&#36;', '&#40;', '&#41;', '&#40;', '&#41;');

        return str_replace($bad, $good, $uri);
    }

    /**
     * Explodes the uri string
     */
    public function explodeSegments()
    {
        foreach (
            explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $this->uriString))
            as $val
        ) {
            $val = trim($this->filterUri($val));

            if ($val != '') {
                $this->segments[] = $val;
            }
        }
    }

    /**
     * @param $n
     *
     * @return mixed|null
     */
    public function segment(int $n)
    {
        if (isset($this->getSegments()[$n])) {
            return $this->getSegments()[$n];
        }

        return null;
    }
}