<?php

namespace Maduser\Minimal\Html\Tests;

use Maduser\Minimal\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testCanReturnIP()
    {
        $request = new Request();
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $this->assertTrue(filter_var($request->getIp(), FILTER_VALIDATE_IP));
        } else {
            $this->assertNull($request->getIp());
        }

    }

    public function testCanReturnGETData()
    {
        $request = new Request();
        $this->assertEquals($_GET, $request->get());
    }

    public function testCanReturnGETDataByKey($key = 'key')
    {
        $_GET['key'] = 'test';
        $request = new Request();
        $this->assertEquals('test', $request->get('key'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCanHandleInvalidGETKey($key = 'key')
    {
        $request = new Request();
        $request->get('invalidKey');
    }
}
