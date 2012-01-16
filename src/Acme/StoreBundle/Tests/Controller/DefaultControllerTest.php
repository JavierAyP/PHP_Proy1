<?php

namespace Acme\StoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
  public function testIndex()
  {
    $client = static::createClient();

    $crawler = $client->request(
                          'GET',
                          '/acme/store/create',
                          array(),
                          array()
                          );
    $this->assertTrue($crawler->filter('html:contains("Created product id")')->count() > 0);
    
    $crawler = $client->request('GET', '/acme/store/update/1');
    $crawler = $client->followRedirect();
    $this->assertRegExp('/New product name!/', $client->getResponse()->getContent());
    
    $crawler = $client->request('GET', '/acme/store/create2');
    $this->assertTrue($crawler->filter('html:contains("and category id:")')->count() > 0);
  }
}
