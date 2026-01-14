<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Editor;
use App\Enum\BookStatus;


class BookTest extends TestCase
{
    // Test getters / setters du Book
    public function testBookGettersAndSetters(): void
    {
        $book = new Book();

        $book->setTitle('Le Petit Prince')
             ->setPageNumber(120)
             ->setStatus(BookStatus::Available);

        $this->assertSame('Le Petit Prince', $book->getTitle());
        $this->assertSame(120, $book->getPageNumber());
        $this->assertSame(BookStatus::Available, $book->getStatus());
    }

    // Test association entre Book et Author
    public function testBookAuthorAssociation(): void
    {
        $book = new Book();
        $author = new Author();
        $author->setName('Victor Hugo');

        // Ajouter l'auteur au livre
        $book->addAuthor($author);

        $this->assertCount(1, $book->getAuthors());
        $this->assertTrue($book->getAuthors()->contains($author));

        // Retirer l'auteur
        $book->removeAuthor($author);
        $this->assertCount(0, $book->getAuthors());
    }

    // Test association entre Book et Editor
    public function testBookEditorAssociation(): void
    {
        $book = new Book();
        $editor = new Editor();
        $editor->setName('Editions Gallimard');

        $book->setEditor($editor);

        $this->assertSame($editor, $book->getEditor());
    }

    public function testBookDefaultValues(): void
    {
        $book = new Book();

        $this->assertNull($book->getTitle());
        $this->assertNull($book->getIsbn());
        $this->assertNull($book->getPageNumber());
        $this->assertNull($book->getStatus());

        // Collections par défaut
        $this->assertCount(0, $book->getAuthors());
        $this->assertCount(0, $book->getComments());
    }
}
