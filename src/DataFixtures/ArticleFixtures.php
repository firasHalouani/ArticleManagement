<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer une catégorie par défaut
        $category = new Category();
        $category->setTitle('Catégorie par défaut')
                 ->setDescription('Description par défaut');
        $manager->persist($category);

        // Créer 10 articles associés à cette catégorie
        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();
            $article->setTitle("Titre de l'article n°$i")
                    ->setContent("Le contenu de l'article n°$i")
                    ->setImage("https://upload.wikimedia.org/wikipedia/commons/e/e3/Tunisia_logo.svg")
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setCategory($category); // Associer l'article à la catégorie
            $manager->persist($article);
        }

        $manager->flush();
    }
}