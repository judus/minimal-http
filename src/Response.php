<?php namespace Maduser\Minimal\Http;

use Maduser\Minimal\Http\Contracts\ResponseInterface;

/**
 * Class Response
 *
 * @package Maduser\Minimal\Http
 */
class Response implements ResponseInterface
{
	/**
     * Holds the response content
     *
	 * @var mixed
	 */
	private $content;

	/**
     * Config option json encode if $content is array
     *
	 * @var bool
	 */
	private $jsonEncodeArray = true;

    /**
     * Config option json encode if $content is object
     *
     * @var bool
     */
    private $jsonEncodeObject = true;

    /**
     * @param $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getJsonEncodeArray()
    {
        return $this->jsonEncodeArray;
    }

    /**
     * @param mixed $jsonEncodeArray
     *
     * @return $this
     */
    public function setJsonEncodeArray($jsonEncodeArray)
    {
        $this->jsonEncodeArray = $jsonEncodeArray;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJsonEncodeObject()
    {
        return $this->jsonEncodeObject;
    }

    /**
     * @param mixed $jsonEncodeObject
     *
     * @return $this
     */
    public function setJsonEncodeObject($jsonEncodeObject)
    {
        $this->jsonEncodeObject = $jsonEncodeObject;

        return $this;
    }

    /**
     * Send a http header
     *
     * @param $str
     *
     * @return $this
     */
	public function header($str)
	{
		header($str);

        return $this;
    }

    /**
     * @param null $content
     *
     * @return $this
     */
    public function prepare($content = null)
    {
        is_null($content) || $this->setContent($content);

        $content = $this->getContent();

        $content = $this->arrayToJson($content);

        $content = $this->objectToJson($content);

        $content = $this->printRecursiveNonAlphaNum($content);

        if (! $this->json_errors($content)) {
            $this->header('Content-Type: application/json');
        }

        $this->setContent($content);

        return $this;
    }

    /**
     * Prepares and send the response to the client
     *
     * @param null $content
     *
     * @return $this
     */
    public function send($content = null)
    {
        $this->prepare($content);
        $this->sendPrepared();
        return $this;
    }

    /**
     * Send the response to the client
     *
     * @return $this
     */
    public function sendPrepared()
    {
        echo $this->getContent();
        return $this;
    }

    /**
     * Encode array to json if configured
     *
     * @param $content
     *
     * @return string
     */public function arrayToJson($content = null)
    {
        if ($this->getJsonEncodeArray() && is_array($content)) {
            $this->header('Content-Type: application/json');
            return json_encode($content);
        }

        return $content;
    }

    /**
     * Encode object to json if configured
     *
     * @param $content
     *
     * @return string
     */
    public function objectToJson($content = null)
    {
        if ($this->getJsonEncodeObject() && is_object($content)) {

            if (method_exists($content, '__toString')) {
                return (string)$content;
            }

            if (method_exists($content, 'toJson')) {
                $this->header('Content-Type: application/json');

                return json_encode($content);
            }

            if (method_exists($content, 'toArray')) {
                $this->header('Content-Type: application/json');

                return json_encode($content->toArray());
            }
        }

        return $content;
    }

    /**
     * Does a print_r with objects and array recursive
     *
     * @param $content
     *
     * @return string
     */
    public function printRecursiveNonAlphaNum($content = null)
    {
        if (is_array($content) || is_object($content)) {
            ob_start();
            /** @noinspection PhpUndefinedFunctionInspection */
            d($content);
            $content = ob_get_contents();
            ob_end_clean();
        }

        return $content;
    }

    /**
     * Redirect location
     *
     * @param $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
    }

    public function status404()
    {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
        $this->terminate();
    }

    /**
	 * Exit PHP
	 */
	public function terminate()
	{
		exit();
	}

    function json_errors($string)
    {
        // decode the JSON data
        $result = json_decode($string);

        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }

        if ($error !== '') {
            // throw the Exception or exit // or whatever :)
            return $error;
        }

        // everything is OK
        return null;
    }
}