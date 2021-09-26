<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public $client;

    public function setUp() : void
    {
        $this->client = static::createClient();
    }

    public function testRegister(): void
    {

        $body = json_encode([
            "username" => "test",
            "password" => "test",
            "full_name" => "Test",
            "phone" => "5555555555"
        ]);

        $this->client->request("POST", "/api/register", [], [], ["CONTENT_TYPE" => "application/json"], $body);
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Müşteri kayıt olma api hatası.");
    }

    public function testLogin(): void
    {
        $body = json_encode([
            "username" => "test",
            "password" => "test",
        ]);

        $this->client->request("POST", "/api/login", [], [], ["CONTENT_TYPE" => "application/json"], $body);
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Müşteri girişi api hatası.");
    }
}
