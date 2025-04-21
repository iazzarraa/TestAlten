<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\Wishlist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wishlist')]
class WishlistController extends AbstractController
{
    #[Route('', name: 'wishlist_show', methods: ['GET'])]
    public function show(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        $wishlist = $em->getRepository(Wishlist::class)->findOneBy(['user' => $user]);

        if (!$wishlist) {
            return $this->json(['products' => []]);
        }

        return $this->json($wishlist, 200, [], ['groups' => ['wishlist:read']]);
    }

    #[Route('/add/{id}', name: 'wishlist_add', methods: ['POST'])]
    public function add(Product $product, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        $wishlist = $em->getRepository(Wishlist::class)->findOneBy(['user' => $user]);

        if (!$wishlist) {
            $wishlist = new Wishlist();
            $wishlist->setUser($user);
        }

        $wishlist->addProduct($product);

        $em->persist($wishlist);
        $em->flush();

        return $this->json(['message' => 'Produit ajouté à la liste d\'envie.']);
    }

    #[Route('/remove/{id}', name: 'wishlist_remove', methods: ['DELETE'])]
    public function remove(Product $product, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        $wishlist = $em->getRepository(Wishlist::class)->findOneBy(['user' => $user]);

        if (!$wishlist) {
            return $this->json(['message' => 'Wishlist non trouvée.'], 404);
        }

        $wishlist->removeProduct($product);

        $em->flush();

        return $this->json(['message' => 'Produit retiré de la liste d\'envie.']);
    }
}
