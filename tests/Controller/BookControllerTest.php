<?php
namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class BookControllerTest extends WebTestCase
{
    private $client;
    private $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine')->getManager();
    }

    // Test page publique /book → HTTP 200
    public function testPublicBookPage(): void
    {
        $crawler = $this->client->request('GET', '/book');

        $this->assertResponseIsSuccessful();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'Repellat doloremque et et.',
            $crawler->filter('h5')->first()->text()
        );
    }

    // Accès admin non connecté → redirection vers /login
    public function testAdminBookIndexWithoutLogin(): void
    {
        $this->client->request('GET', '/admin/book');
        $this->assertResponseRedirects('/login');
    }

    // Accès admin connecté → HTTP 200
    public function testAdminBookIndexWithLogin(): void
    {
        $em = $this->client->getContainer()->get('doctrine')->getManager();

        // Récupérer l'utilisateur admin dans la base de test
        $adminUser = $em->getRepository(User::class)->findOneBy(['email' => 'vaillant.roger@delaunay.org']);

        $this->client->loginUser($adminUser);
        $this->client->request('GET', '/admin/book');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h4'); // Vérifie qu’un titre existe sur la page admin
    }

    // Vérification des données en base
    public function testBookCount(): void
    {
        $books = $this->em->getRepository(Book::class)->findAll();
        $this->assertCount(100, $books, 'La base de test doit contenir exactement 100 livres.');
    }
}
