<?php

namespace Yurii\OroBundle\Tests\Functional;

class TranslationTest extends BaseTestCase
{

    public function testTranslation()
    {
        $client = $this->createClient();
        $client->insulate();

        $client->request('get', 'http://example.com/');
        $content = $client->getResponse()->getContent();
        $this->assertRegExp('/WelcomeEN/', $content);

        $client->request('get', 'http://example.fr/');
        $content = $client->getResponse()->getContent();
        $this->assertRegExp('/Bienvenue/', $content);

        $client->request('get', 'http://example.com/fr/');
        $content = $client->getResponse()->getContent();
        $this->assertRegExp('/Bienvenue/', $content);
    }

}
