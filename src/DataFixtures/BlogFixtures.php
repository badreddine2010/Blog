<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      $faker = \Faker\Factory::create('ar_AR');
//génération des catégories
for ($c=0; $c <5 ; $c++) { 
    $cat = new Category();
    $cat->setTitle($faker->word());
    $cat->setDescription($faker->sentence(5,true));
    $manager->persist($cat);

          //génération des articles
      for ($i=0; $i<mt_rand(1,5) ; $i++) { 
          # code...
          $art = new Article();
          $art->setTitle($faker->sentence(5,true));
          $art->setCategory($cat);
          $art->setContent($faker->paragraph(4,true));
          $manager->persist($art);

          for ($ct=0; $ct<mt_rand(1,5) ; $ct++) { 
            $cmt = new Comment();
            $cmt->setAuteur($faker->name());
            $cmt->setRemark($faker->paragraph(4,true));
            $cmt->setRelation($art);
            $manager->persist($cmt);
  
        }

      }
    }

        $manager->flush();
    }
}
