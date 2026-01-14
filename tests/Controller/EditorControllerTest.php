<?php

namespace App\Tests\Controller;

use App\Entity\Editor;
use App\Repository\EditorRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditorControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $editorRepo;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = self::getContainer();

        $this->entityManager = $container->get('doctrine')->getManager();
        $this->editorRepo = $container->get(EditorRepository::class);

        $user = $container->get('App\Repository\UserRepository')->findOneBy(['email' => 'vaillant.roger@delaunay.org']);
        $this->client->loginUser($user);
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/admin/editor');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('h1', 'Liste d\'éditeurs');
    }

    public function testAddEditor(): void
    {
        $crawler = $this->client->request('GET', '/admin/editor/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Ajout d\'éditeur');

        $form = $crawler->selectButton('Envoyer')->form([
            'editor[name]' => 'Nouvel Éditeur Test',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/editor');

        $this->client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Liste d\'éditeurs');

        $editor = $this->editorRepo->findOneBy(['name' => 'Nouvel Éditeur Test']);
        $this->assertNotNull($editor);
    }

    public function testShowEditor(): void
    {
        $editor = new Editor();
        $editor->setName('Éditeur Show Test');
        $this->entityManager->persist($editor);
        $this->entityManager->flush();

        $this->client->request('GET', '/admin/editor/' . $editor->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Éditeur Show Test');
    }
}
