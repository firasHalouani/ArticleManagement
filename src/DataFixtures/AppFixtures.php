<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Créer 3 catégories fictives
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence(3))
                     ->setDescription($faker->paragraph());
            $manager->persist($category);

            // Créer des articles pour chaque catégorie
            for ($j = 1; $j <= mt_rand(4, 6); $j++) {
                $article = new Article();
                $article->setTitle($faker->sentence(5))
                        ->setContent($faker->paragraphs(3, true))
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 months')))
                        ->setCategory($category); // Associer l'article à la catégorie
                $manager->persist($article);

                // Créer des commentaires pour chaque article
                for ($k = 1; $k <= mt_rand(2, 5); $k++) {
                    $comment = new Comment();
                    $comment->setAuthor($faker->name)
                            ->setContent($faker->sentence())
                            ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 month')))
                            ->setArticle($article);
                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}