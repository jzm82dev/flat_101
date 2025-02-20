<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use Doctrine\Persistence\Proxy;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProductControllerTest extends WebTestCase
{
    public function testInsertProduct(): void
    {
        
        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();

        // Create and save product
        $product = new Product();
        $product->setName('Boli');
        $product->setPrice(1.60);

        $entityManager->persist($product);
        $entityManager->flush();

        $productRepository = $entityManager->getRepository(Product::class);
        $savedProduct = $productRepository->findOneBy(['name' => 'Boli']);

        // Check product saved correctly
        $this->assertNotNull($savedProduct);
        $this->assertSame('Boli', $savedProduct->getName());
        $this->assertSame(1.60, $savedProduct->getPrice());

    }


    public function testDeleteProductFromDatabase()
    {
        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();

        // Create and save product
        $product = new Product();
        $product->setName('Gafas');
        $product->setPrice(135.90);;
        $entityManager->persist($product);
        $entityManager->flush();

        // Get product of database and check exits it
        $productRepository = $entityManager->getRepository(Product::class);
        $savedProduct = $productRepository->findOneBy(['name' => 'Gafas']);
        $this->assertNotNull($savedProduct);

        // Remove product
        $entityManager->remove($savedProduct);
        $entityManager->flush();

        // Check product not exists in database
        $deletedProduct = $productRepository->findOneBy(['name' => 'Gafas']);
        $this->assertNull($deletedProduct);
    }

    public function testUpdateProductInDatabase()
    {
        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();

        // Create and save product
        $product = new Product();
        $product->setName('Martillo');
        $product->setPrice(20.90);;
        $entityManager->persist($product);
        $entityManager->flush();

         // Get product of database
        $productRepository = $entityManager->getRepository(Product::class);
        $savedProduct = $productRepository->findOneBy(['name' => 'Martillo']);
        $this->assertNotNull($savedProduct);

        // Update name of product
        $savedProduct->setName('Destornillador');
        $savedProduct->setPrice('7.77');
        $entityManager->flush();

        // Get product updated 
        $updatedProduct = $productRepository->findOneBy(['name' => 'Destornillador']);

        // Check product updated correctly
        $this->assertNotNull($updatedProduct);
        $this->assertSame('Destornillador', $updatedProduct->getName());
    }

}
