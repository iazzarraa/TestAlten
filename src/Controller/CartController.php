<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'create_cart', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $cart = $this->getUserCart($this->getUser(), $em);
        return $this->json([
            'message' => 'Panier créé avec succès !',
            'cartId' => $cart->getId(),
        ]);
    }

    #[Route('/cart', name: 'cart_get', methods: ['GET'])]
    public function getCart(EntityManagerInterface $em)
    {
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour voir votre panier.');
        }
        
        $cart = $em->getRepository(Cart::class)->findOneBy(['user' => $user]);

        if (!$cart) {
            return new JsonResponse(['message' => 'Panier vide.'], 200);
        }

        return $this->json($cart, 200, [], ['groups' => ['cart:read']]);
    }

    #[Route('/cart/add', name: 'cart_add', methods: ['POST'])]
    public function addProductToCart(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour ajouter un produit au panier.');
        }

        $data = json_decode($request->getContent(), true);

        $productId = $data['productId'];
        $quantity = $data['quantity'];

        $product = $em->getRepository(Product::class)->find($productId);

        if (!$product) {
            return new JsonResponse(['message' => 'Produit non trouvé.'], 404);
        }

        // Vérifier si l'utilisateur a déjà un panier
        $cart = $this->getUserCart($user, $em);

        // Ajouter l'élément au panier
        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity($quantity);
        $cart->addCartItem($cartItem);

        $em->persist($cartItem);
        $em->flush();

        return new JsonResponse(['message' => 'Produit ajouté au panier.'], 201);
    }

    #[Route('/cart/{productId}', name: 'cart_remove', methods: ['DELETE'])]
    public function removeProductFromCart(int $productId, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour supprimer un produit du panier.');
        }

        $cart = $em->getRepository(Cart::class)->findOneBy(['user' => $user]);

        if (!$cart) {
            return new JsonResponse(['message' => 'Panier vide.'], 404);
        }

        // Rechercher le CartItem à supprimer
        $cartItem = $cart->getCartItems()->filter(function ($item) use ($productId) {
            return $item->getProduct()->getId() === $productId;
        })->first();

        if (!$cartItem) {
            return new JsonResponse(['message' => 'Produit non trouvé dans le panier.'], 404);
        }

        $cart->removeCartItem($cartItem);
        $em->remove($cartItem);
        $em->flush();

        return new JsonResponse(['message' => 'Produit supprimé du panier.'], 200);
    }

    private function getUserCart($user, EntityManagerInterface $em)
    {
        // Chercher un panier pour l'utilisateur
        $cart = $em->getRepository(Cart::class)->findOneBy(['user' => $user]);

        // S'il n'existe pas, on crée un panier vide
        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $em->persist($cart);
            $em->flush();
        }

        return $cart;
    }
}
