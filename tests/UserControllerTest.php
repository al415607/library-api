<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserControllerTest extends WebTestCase
{
    private $entityManager;
    private $client;


    // Se ejecuta antes de cada prueba
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $this->entityManager->getRepository(User::class)->createQueryBuilder('u')
            ->delete()
            ->getQuery()
            ->execute();

    }

    public function testCreateUser(): void
    {
        $this->client->request(
            'POST',
            '/users',
            [],  
            [],  
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'age' => 30,
                'password' => 'password123',
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($this->client->getResponse()->getContent());

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertGreaterThan(0, $data['id']);
    }

    public function testUpdateUser(): void
    {
        $this->client->request(
            'POST',
            '/users',
            [], 
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'age' => 30,
                'password' => 'password123',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $userId = $data['id'];

        $this->client->request(
            'PUT',
            "/users/{$userId}",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Updated User',
            ])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteUser(): void
    {
        $this->client->request(
            'POST',
            '/users',
            [],  
            [], 
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'age' => 30,
                'password' => 'password123',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $userId = $data['id'];

        $this->client->request('DELETE', "/users/{$userId}", [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertResponseStatusCodeSame(204);
    }
}
