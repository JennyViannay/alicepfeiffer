<?php

namespace App\Tests\Routes;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RouteTest extends WebTestCase
{
   /**
    * @dataProvider urlProvider
    */
   public function testPageIsSuccessful($url)
   {
       $client = self::createClient();
       $client->request('GET', $url);
       $this->assertTrue($client->getResponse()->isSuccessful());
   }

   public function urlProvider()
   {
       return [
          ['/'],
          ['/contact'],
          ['/captchaverify'],
          ['/articles'],
          ['/books'],
          ['/press'],
          ['/medias'],
          ['/search'],
          ['/autocomplete'],
        ];
   }
}
