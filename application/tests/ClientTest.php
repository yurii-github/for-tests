<?php


class TestClient extends PHPUnit_Framework_TestCase {

    function testDummy() {
        $this->assertTrue(true);
    }


    function testInit() {
        $client = new \App\Model\Client();
        $client->setFirstName('asd');
        $this->assertEquals('asd', $client->firstName);
    }

}