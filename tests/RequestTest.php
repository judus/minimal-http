<?php

namespace Maduser\Minimal\Html\Tests;

use Maduser\Minimal\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testCanRunFectchServerInfoWithouError()
    {
        $request = new Request();
        $this->assertNull($request->fetchServerInfo());
    }

    public function testCanReturnIP()
    {
        $request = new Request();
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $this->assertTrue(filter_var($request->getIp(),
                FILTER_VALIDATE_IP));
        } else {
            $this->assertNull($request->getIp());
        }
    }

    public function testCanFetchRequestMethodFromPOST()
    {
        $request = new Request();

        $_POST['_method'] = 'PATCH';

        $method = $request->fetchRequestMethod();

        unset($_POST['_method']);

        $this->assertEquals('PATCH', $method);
    }

    public function testCanFetchRequestMethodCLI()
    {
        $request = new Request();
        $method = $request->fetchRequestMethod();
        $this->assertEquals('CLI', $method);
    }

    public function testCanReturnGETData()
    {
        $request = new Request();
        $this->assertEquals($_GET, $request->get());
    }

    public function testCanReturnGETDataByKey()
    {
        $_GET['key'] = 'test';
        $request = new Request();
        $this->assertEquals('test', $request->get('key'));
    }

    /**
     */
    public function testCanHandleInvalidGETKey()
    {
        $this->expectException(\InvalidArgumentException::class);
        $request = new Request();
        $request->get('invalidKey');
    }

    public function testCanReturnPOSTData()
    {
        $request = new Request();
        $this->assertEquals($_POST, $request->post());
    }

    public function testCanReturnPOSTDataByKey()
    {
        $_POST['key'] = 'test';
        $request = new Request();
        $this->assertEquals('test', $request->post('key'));
    }

    /**
     */
    public function testCanHandleInvalidPOSTKey()
    {
        $this->expectException(\InvalidArgumentException::class);
        $request = new Request();
        $request->post('invalidKey');
    }

    public function testCanReturnREQUESTData()
    {
        $request = new Request();
        $this->assertEquals($_REQUEST, $request->request());
    }

    public function testCanReturnREQUESTDataByKey()
    {
        $_REQUEST['key'] = 'test';
        $request = new Request();
        $this->assertEquals('test', $request->request('key'));
    }

    /**
     */
    public function testCanHandleInvalidREQUESTKey()
    {
        $this->expectException(\InvalidArgumentException::class);
        $request = new Request();
        $request->request('invalidKey');
    }

    public function testCanReturnFormattedArrayOfFILES()
    {
        $_FILES = [
            "name" => [
                "file0.txt",
                "file1.txt"
            ],
            "type" => [
                "text/plain",
                "text/html"
            ],
            "tmp_name" => [
                'file0.txt',
                'file1.txt'
            ],
            "error" => [
                UPLOAD_ERR_OK,
                UPLOAD_ERR_OK
            ],
            "size" => [
                '123',
                '456'
            ],
        ];

        $expect = [
            [
                "name" => "file0.txt",
                "type" => "text/plain",
                "tmp_name" => "file0.txt",
                "error" => UPLOAD_ERR_OK,
                "size" => '123'
            ],
            [
                "name" => "file1.txt",
                "type" => "text/html",
                "tmp_name" => "file1.txt",
                "error" => UPLOAD_ERR_OK,
                "size" => '456'
            ]
        ];

        $request = new Request();
        $this->assertEquals(json_encode($request->files()), json_encode($expect));

    }

    public function testCanSetAndGetUriString()
    {
        $request = new Request();
        $request->setUriString('seg1/seg2/seg3/123');
        $this->assertEquals($request->getUriString(), 'seg1/seg2/seg3/123');
    }

    public function testCanParseCliArgs()
    {
        $server['argv'] = ['arg1', 'arg2', 'arg3'];
        $expect = '/arg2/arg3';

        $request = new Request();
        $this->assertEquals($request->parseCliArgs($server), $expect);
    }

    public function testCanFilterUri()
    {
        $uri = 'aa/a$aa/a(aaa)aa/a%28a/aa%29aa/a';
        $expect = 'aa/a&#36;aa/a&#40;aaa&#41;aa/a&#40;a/aa&#41;aa/a';

        $request = new Request();
        $this->assertEquals($request->filterUri($uri), $expect);
    }

    public function testCanExplodeAUriString()
    {
        $request = new Request();
        $this->assertEquals(
            json_encode(['123','456','789']),
            json_encode($request->explodeSegments('123/456/789'))
        );
    }

    public function testCanGetTheNthSegmentOfUriString()
    {
        $request = new Request();
        $request->setUriString('seg1/seg2/seg3/123');
        $this->assertEquals($request->segment(3), 'seg3');
    }
}
