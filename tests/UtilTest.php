<?php

namespace App\Tests;

use App\Entity\Article;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function testTitleLength()
    {
        $title = 'Notre Produit';
        $article = new Article();
        $article->setTitle($title);

        $this->assertGreaterThan(5, strlen($article->getTitle()));
    }

    public function testTitleIsString()
    {
        $title = 'Sample Title';
        $article = new Article();
        $article->setTitle($title);

        $this->assertIsString($article->getTitle());
    }

    public function testTitleStartsWithUpperCase()
    {
        $title = 'Sample Title';
        $article = new Article();
        $article->setTitle($title);

        $this->assertMatchesRegularExpression('/^[A-Z]/', $article->getTitle());
    }
}
