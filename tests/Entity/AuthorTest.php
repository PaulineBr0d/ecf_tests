<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Author;

class AuthorTest extends TestCase
{
    // Test getters / setters de l'Author
    public function testAuthorGettersAndSetters(): void
    {
        $author = new Author();
        $author->setName('Jules Verne')
               ->setDateOfBirth(new \DateTimeImmutable('1828-02-08'))
               ->setNationality('Française');

        $this->assertSame('Jules Verne', $author->getName());
        $this->assertSame('Française', $author->getNationality());
        $this->assertEquals(new \DateTimeImmutable('1828-02-08'), $author->getDateOfBirth());
    }

    public function testAuthorDefaultValues(): void
    {
        $author = new Author();

        $this->assertNull($author->getName());
        $this->assertNull($author->getDateOfBirth());
        $this->assertNull($author->getDateOfDeath());
        $this->assertNull($author->getNationality());

        // Collection par défaut
        $this->assertCount(0, $author->getBooks());
    }
}
