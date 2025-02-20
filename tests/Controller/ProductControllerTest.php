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

        $product = new Product();
        $product->setName('Boli');
        $product->setPrice(1.60);

        $entityManager->persist($product);
        $entityManager->flush();

        $productRepository = $entityManager->getRepository(Product::class);
        $savedProduct = $productRepository->findOneBy(['name' => 'Boli']);

        // Verificar que se guardó correctamente
        $this->assertNotNull($savedProduct);
        $this->assertSame('Boli', $savedProduct->getName());
        $this->assertSame(1.60, $savedProduct->getPrice());

    }


    public function testDeleteProductFromDatabase()
    {
        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();

        // Crear y guardar un producto
        $product = new Product();
        $product->setName('Gafas');
        $product->setPrice(135.90);;
        $entityManager->persist($product);
        $entityManager->flush();

        // Recuperar el usuario y verificar que existe
        $userRepository = $entityManager->getRepository(Product::class);
        $savedProduct = $userRepository->findOneBy(['name' => 'Gafas']);
        $this->assertNotNull($savedProduct);

        // Eliminar el usuario
        $entityManager->remove($savedProduct);
        $entityManager->flush();

        // Verificar que ya no existe en la base de datos
        $deletedProduct = $userRepository->findOneBy(['name' => 'Gafas']);
        $this->assertNull($deletedProduct);
    }

    public function testUpdateProductInDatabase()
    {
        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();

        // Crear y guardar un producto
        $product = new Product();
        $product->setName('Martillo');
        $product->setPrice(20.90);;
        $entityManager->persist($product);
        $entityManager->flush();

        // Recuperar el usuario desde la base de datos
        $productRepository = $entityManager->getRepository(Product::class);
        $savedProduct = $productRepository->findOneBy(['name' => 'Martillo']);
        $this->assertNotNull($savedProduct);

        // Actualizar el nombre del usuario
        $savedProduct->setName('Destornillador');
        $entityManager->flush();

        // Recuperar nuevamente el usuario actualizado
        $updatedProduct = $productRepository->findOneBy(['name' => 'Destornillador']);

        // Verificar que el nombre se haya actualizado correctamente
        $this->assertNotNull($updatedProduct);
        $this->assertSame('Destornillador', $updatedProduct->getName());
    }

}
