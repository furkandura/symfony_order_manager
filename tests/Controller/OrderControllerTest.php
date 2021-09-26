<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public $client;

    public function setUp() : void
    {
        $this->client = static::createClient();
    }
    
    public function getToken() : string
    {
        $body = json_encode([
            "username" => "test",
            "password" => "test",
        ]);

        $this->client->request("POST", "/api/login", [], [], ["CONTENT_TYPE" => "application/json"], $body);

        return json_decode($this->client->getResponse()->getContent())->data->token;
    }

    public function testNewOrder(): void
    {
        $token = $this->getToken();


        $body = json_encode([
            "product_id" => 4,
            "quantity" => 15,
            "address" => "Beylikdüzü"
        ]);

        $this->client->request(
            "POST",
            "/api/order/new",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer {$token}",
                "CONTENT_TYPE" => "application/json",
            ],
            $body
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Sipariş oluşturma api hatası.");
    }

    public function testUpdateOrder(): void
    {
        $token = $this->getToken();

        $body = json_encode([
            "product_id" => 4,
            "quantity" => 15,
            "address" => "Beylikdüzü",
            "shipping_date" => "2021-09-26 00:00:00"
        ]);

        $this->client->request(
            "PUT",
            "/api/order/update/1",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer {$token}",
                "CONTENT_TYPE" => "application/json",
            ],
            $body
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Sipariş güncelleme api hatası.");
    }

    public function testDetailOrder(): void
    {
        $token = $this->getToken();

        $this->client->request(
            "GET",
            "/api/order/detail/1",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer {$token}",
                "CONTENT_TYPE" => "application/json",
            ]
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Sipariş detayı görüntüleme api hatası.");
    }

    public function testAllOrder(): void
    {
        $token = $this->getToken();

        $this->client->request(
            "GET",
            "/api/order/all",
            [],
            [],
            [
                "HTTP_AUTHORIZATION" => "Bearer {$token}",
                "CONTENT_TYPE" => "application/json",
            ]
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Toplu sipariş görüntüleme api hatası.");
    }

}
