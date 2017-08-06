<?php namespace Maduser\Minimal\Html\Tests;

use Maduser\Minimal\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testCanSetContent()
    {
        $response = new Response();
        $this->assertEquals($response, $response->setContent('test'));
    }

    public function testCanGetContent()
    {
        $response = new Response();
        $response->setContent('testtext');
        $this->assertEquals('testtext', $response->getContent());
    }

    public function testCanGetJsonEncodeArray()
    {
        $response = new Response();
        $json = json_encode(['test', 'test', 'test']);
        $response->setJsonEncodeArray($json);
        $this->assertJson($response->getJsonEncodeArray());
    }

    public function testCanSetJsonEncodeArray()
    {
        $response = new Response();

        $json = json_encode(['test', 'test', 'test']);

        $this->assertEquals($response, $response->setJsonEncodeArray($json));
    }

    public function testCanGetJsonEncodeObject()
    {
        $response = new Response();

        $object = json_encode($response);
        $response->setJsonEncodeObject($object);

        $this->assertJson($response->getJsonEncodeObject());
    }

    public function testCanSetJsonEncodeObject()
    {
        $response = new Response();

        $json = json_encode($response);

        $this->assertEquals($response, $response->setJsonEncodeObject($json));
    }

    public function testCanSetHeader()
    {
        $response = new Response();
        $this->assertEquals($response, $response->header(''));
    }

    public function testCanPrepare()
    {
        $response = new Response();
        $this->assertEquals($response, $response->prepare());
    }

    public function testCanPrepareArrayToJson()
    {
        $content = ['123', '123', '123'];
        $response = new Response();
        $response->prepare($content);
        $this->assertJson($response->getContent());
    }

    public function testCanPrepareObjectToJson()
    {
        $response = new Response();
        $response->prepare(new TestObject());
        $this->assertJson($response->getContent());
    }

    public function testCanPrepareObjectToRecursiveNonAlphaNum()
    {
        $response = new Response();
        $response->prepare(new \stdClass());
        $this->assertEquals(true,
            is_string($response->getContent())
            && !is_null($response->jsonErrors($response->getContent()))
        );
    }

    public function testCanSend()
    {
        $response = new Response();
        $this->assertEquals($response, $response->send());
    }

    public function testCanSendPrepared()
    {
        $response = new Response();
        $this->assertEquals($response, $response->sendPrepared());

    }

    public function testCanDoArrayToJson()
    {
        $response = new Response();
        $this->assertJson($response->arrayToJson(['test', 'test', 'test']));
    }

    public function testCanDoObjectToJson()
    {
        $response = new Response();
        $this->assertJson($response->objectToJson(new TestObject()));
    }

    public function testCanPrintRecursiveNonAlphaNum()
    {
        $response = new Response();
        $testObject = new \stdClass();
        $result = $response->printRecursiveNonAlphaNum($testObject);

        $this->assertEquals(true,
            is_string($result) && !is_null($response->jsonErrors($result))
        );
    }

    function testCanGetJsonErrors()
    {
        $response = new Response();
        $results = $response->jsonErrors('{blabla:blabla}');
        $this->assertNotNull($results);
    }

}

class TestObject
{
    public function toJson()
    {
        return json_encode($this);
    }
}