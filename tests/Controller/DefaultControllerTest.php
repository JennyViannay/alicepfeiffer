<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    public function testTestsAreWorking()
    {
        $this->assertEquals(2, 1 + 1);
    }

    public function testHomePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        // $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
    }

    public function testContactSubmit()
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $crawler = $client->submitForm('New Contact', [
            'contact_form[message]' => 'lorem ipsum test',
            'contact_form[email]' => 'jenny@gmail.com',
            'contact_form[subject]' => 'subject text',
        ]);

    }
}
