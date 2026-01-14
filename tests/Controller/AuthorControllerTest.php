<?php

namespace App\Tests\Controller;

use App\Entity\Author;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    private function getAdminUser(): User
    {
        $container = static::getContainer();
        $userRepo = $container->get('doctrine')->getRepository(User::class);
        return $userRepo->findOneBy(['email' => 'vaillant.roger@delaunay.org']);
    }

    // Test accès à la liste des auteurs en admin (connecté)
    public function testAdminAuthorIndexWithLogin(): void
    {
        $admin = $this->getAdminUser();
        $this->client->loginUser($admin);

        $crawler = $this->client->request('GET', '/admin/author');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "Liste d'auteur");

        // Vérifie que la page contient tous les auteurs
        $authorCount = $crawler->filter('h4.mb-1')->count();
        $this->assertGreaterThan(0, $authorCount, 'Des auteurs doivent être affichés');
    }

    // Test redirection si accès à /admin/author non connecté
    public function testAdminAuthorIndexWithoutLogin(): void
    {
        $this->client->request('GET', '/admin/author');
        $this->assertResponseRedirects('/login');
    }

    // Test page formulaire nouvel auteur
    public function testAddAuthorValid(): void
    {
        $admin = $this->getAdminUser();
        $this->client->loginUser($admin);

        $crawler = $this->client->request('GET', '/admin/author/new');

        $form = $crawler->selectButton('Envoyer')->form([
            'author[name]' => 'Auteur Valide',
            'author[dateOfBirth]' => '1980-05-01',
            'author[dateOfDeath]' => '',
            'author[nationality]' => 'Française',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/admin/author');
        $this->client->followRedirect();

        $container = static::getContainer();
        $authorRepo = $container->get('doctrine')->getRepository(Author::class);

        $author = $authorRepo->findOneBy(['name' => 'Auteur Valide']);
        $this->assertNotNull($author);
        $this->assertEquals('Française', $author->getNationality());
    }

    // Test table author
    public function testFiftyAuthorsExistInDatabase(): void
    {
        $container = static::getContainer();
        $authorRepo = $container->get('doctrine')->getRepository(Author::class);

        $authors = $authorRepo->findAll();

        $this->assertCount(50, $authors, 'Il doit y avoir exactement 50 auteurs en base');
    }
}
