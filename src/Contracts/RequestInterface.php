<?php
/**
 * RequestInterface.php
 * 7/15/17 - 12:24 AM
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
 * Class Request
 *
 * @package Maduser\Minimal\Http
 */
interface RequestInterface
{
    /**
     * Setter $uriString
     *
     * @param $str
     */
    public function setUriString(string $str);

    /**
     * Getter $uriString
     *
     * @return string
     */
    public function getUriString();

    /**
     * Setter $requestMethod
     *
     * @param $str
     */
    public function setRequestMethod(string $str);

    /**
     * Getter $requestMethod
     *
     * @return mixed
     */
    public function getRequestMethod();

    /**
     * Setter $segments
     *
     * @param array $segments
     */
    public function setSegments(array $segments);

    /**
     * Getter $segments
     *
     * @return array
     */
    public function getSegments(): array;

    /**
     * Request constructor
     * sets $requestMethod
     * sets $uriString
     * sets $segments
     */
    public function __construct();

    /**
     * Fetch the http method
     */
    public function fetchRequestMethod();

    /**
     * Fetches the REQUEST_URI and sets $uriString
     */
    public function fetchUriString();

    /**
     * Formats cli args like a uri
     *
     * @return string
     */
    public function parseCliArgs();

    /**
     * Filter or replace bad chars from uri
     *
     * @param $uri
     *
     * @return mixed
     */
    public function filterUri($uri);

    /**
     * Explodes the uri string
     */
    public function explodeSegments();

    /**
     * Returns the nth uri segment
     *
     * @param int $n
     *
     * @return string
     */
    public function segment(int $n);
}