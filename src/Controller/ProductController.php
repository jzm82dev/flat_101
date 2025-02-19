<?php

namespace App\Controller;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    
    public function listProduct(ProductRepository $productRepository): Response
    {
        $products = $productRepository->getAllProducts();

        $data = array_map(function ($product) {
            return [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'created_at' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $products);

        return $this->json($data);
    }

    public function createProduct(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator, ProductRepository $productRepository): Response
    {

        $data = $request->getPayload()->all();

        // Validate data
        if (!isset($data['name']) || !isset($data['price'])) {
            return $this->json(['error' => 'The name and the price produdct are mandatory'], 400);
        }

         // Create DTO
         $productDTO = new ProductDTO(
            $data['name'] ?? '',
            (float) ($data['price'] ?? 0)
        );

        // Validate DTO
        $errors = $validator->validate($productDTO);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $exitsProduct = $productRepository->getProductByName($data['name']);
        if( $exitsProduct ){
            return $this->json(['error' => 'A product by that name already exists'], 400);
        }


        $product = new Product();
        $product->setName($productDTO->name);
        $product->setPrice($productDTO->price);


        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json([
            'message' => 'Product saved',
            'product' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'created_at' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
            ]
        ], 201);

    }



    public function createProductAutomatically(EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(10.50);

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Producr created with id: ' . $product->getId());
    }

    
    public function showProduct(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return new Response('Product not found', 404);
        }

        return new Response('Product: ' . $product->getName() . ' - Price: ' . $product->getPrice() . '€');
    }


    public function updateProduct(int $id, EntityManagerInterface $entityManager,  Request $request, 
                                  ValidatorInterface $validator, ProductRepository $productRepository): Response
    {
        // Find product DB
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }


        $data = $request->getPayload()->all();
       
        // Create DTO with new values
        $productDTO = new ProductDTO(
            $data['name'] ?? $product->getName(),
            isset($data['price']) ? (float) $data['price'] : $product->getPrice()
        );

        // Validate DTO
        $errors = $validator->validate($productDTO);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }


        $exitsProduct = $productRepository->getProductByName($productDTO->name);
        if( $exitsProduct ){
            return $this->json(['error' => 'A product with the new name already exists'], 400);
        }

        // Update product whit the new values
        $product->setName($productDTO->name);
        $product->setPrice($productDTO->price);
       

        // SAve changes
        $entityManager->flush();

        return $this->json([
            'message' => 'Producto actualizado',
            'product' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'created_at' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
            ]
        ]);
    }



    public function deleteProduct(int $id, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        // Find prodcut
        $product = $productRepository->find($id);

        
        if (!$product) {
            return $this->json(['error' => 'Product does not found'], 404);
        }

        // Delete product
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json(['message' => 'Product delete correctly']);
    }


}
