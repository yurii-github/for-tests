<?php


class TestClient extends PHPUnit_Framework_TestCase {

    function createDeposit() {
        $client = new \App\Model\Client();
    }


    function testDummy() {
        $this->assertTrue(true);
    }

}