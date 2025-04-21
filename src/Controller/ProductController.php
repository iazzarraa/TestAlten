<?php

namespace App\Controller;

use App\Entity\Product;
use App\Dto\ProductInput;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormFactoryInterface;

#[Route('/products')]
class ProductController extends AbstractController
{
    #[Route('', name: 'product_create', methods: ['POST'])]
    public function create(Request $request, FormFactoryInterface $formFactory, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        // Vérifier si l'utilisateur authentifié est l'admin
        $user = $this->getUser();
        if ($user->getEmail() !== 'admin@admin.com') {
            throw new AccessDeniedException('Vous n\'avez pas les droits pour effectuer cette action.');
        }

        $dto = new ProductInput();
        $data = json_decode($request->getContent(), true);
        
        $form = $formFactory->create(ProductType::class, $dto);
        $form->submit($data);

        if (!$form->isValid()) {
            
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        $now = new \DateTime();
        $product = (new Product())
            ->setCode($dto->code)
            ->setName($dto->name)
            ->setDescription($dto->description)
            ->setImage($dto->image)
            ->setCategory($dto->category)
            ->setPrice($dto->price)
            ->setQuantity($dto->quantity)
            ->setInternalReference($dto->internalReference)
            ->setShellId($dto->shellId)
            ->setInventoryStatus($dto->inventoryStatus)
            ->setRating($dto->rating)
            ->setCreatedAt($now)
            ->setUpdatedAt($now);

        $em->persist($product);
        $em->flush();

        return $this->json(['id' => $product->getId()], Response::HTTP_CREATED);
    }

    #[Route('', name: 'product_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $products = $em->getRepository(Product::class)->findAll();
        return $this->json($products);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $product = $em->getRepository(Product::class)->find($id);
        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }
        return $this->json($product);
    }

    #[Route('/{id}', name: 'product_patch', methods: ['PATCH'])]
    public function patch(int $id, Request $request, FormFactoryInterface $formFactory, EntityManagerInterface $em): JsonResponse
    {
        // Vérifier si l'utilisateur authentifié est l'admin
        $user = $this->getUser();
        if ($user->getEmail() !== 'admin@admin.com') {
            throw new AccessDeniedException('Vous n\'avez pas les droits pour effectuer cette action.');
        }

        $product = $em->getRepository(Product::class)->find($id);
        if (!$product) {
            return $this->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $dto = new ProductInput();
        $data = json_decode($request->getContent(), true);

        $form = $formFactory->create(ProductType::class, $dto);
        $form->submit($data, false);

        if (!$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($product, $setter)) {
                $product->$setter($value);
            }
        }

        $product->setUpdatedAt(new \DateTime());
        $em->flush();

        return $this->json(['message' => 'Product updated']);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        // Vérifier si l'utilisateur authentifié est l'admin
        $user = $this->getUser();
        if ($user->getEmail() !== 'admin@admin.com') {
            throw new AccessDeniedException('Vous n\'avez pas les droits pour effectuer cette action.');
        } 
        
        $product = $em->getRepository(Product::class)->find($id);
        if (!$product) {
            return $this->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($product);
        $em->flush();

        return $this->json(['message' => 'Product deleted']);
    }
}
