<?php

namespace JobZ\FrontBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerControllerTest extends WebTestCase
{
    public function testHome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testCategory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/category/slug');
    }

}
