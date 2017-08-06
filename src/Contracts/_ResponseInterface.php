<?php
/**
 * ResponseInterface.php
 * 7/15/17 - 1:02 AM
 *
 * PHP version 7
 *
 * @package    @package_name@
 * @author     Julien Duseyau <julien.duseyau@gmail.com>
 * @copyright  2017 Julien Duseyau
 * @license    https://opensource.org/licenses/MIT
 * @version    Release: @package_version@
 *
 * The MIT License (MIT)
 *
 * Copyright (c) Julien Duseyau <julien.duseyau@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Maduser\Minimal\Http\Contracts;

/**
 * Class Response
 *
 * @package Maduser\Minimal\Http
 */
interface ResponseInterface
{
    /**
     * @param $content
     *
     * @return $this
     */
    public function setContent($content);

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @return mixed
     */
    public function getJsonEncodeArray();

    /**
     * @param mixed $jsonEncodeArray
     *
     * @return $this
     */
    public function setJsonEncodeArray($jsonEncodeArray);

    /**
     * @return mixed
     */
    public function getJsonEncodeObject();

    /**
     * @param mixed $jsonEncodeObject
     *
     * @return $this
     */
    public function setJsonEncodeObject($jsonEncodeObject);

    /**
     * Send a http header
     *
     * @param $str
     *
     * @return $this
     */
    public function header($str);

    /**
     * @param null $content
     *
     * @return $this
     */
    public function prepare($content = null);

    /**
     * Prepares and send the response to the client
     *
     * @param null $content
     *
     * @return $this
     */
    public function send($content = null);

    /**
     * Send the response to the client
     *
     * @return $this
     */
    public function sendPrepared();

    /**
     * Encode array to json if configured
     *
     * @param $content
     *
     * @return string
     */
    public function arrayToJson($content = null);

    /**
     * Encode object to json if configured
     *
     * @param $content
     *
     * @return string
     */
    public function objectToJson($content = null);

    /**
     * Does a print_r with objects and array recursive
     *
     * @param $content
     *
     * @return string
     */
    public function printRecursiveNonAlphaNum($content = null);

    /**
     * Redirect location
     *
     * @param $url
     */
    public function redirect($url);

    /**
     * Sends a 404 Error message
     */
    public function status404();

    /**
     * Exit PHP
     */
    public function terminate();
}