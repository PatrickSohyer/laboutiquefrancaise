<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/nos-produits", name="produits")
     */
    public function index(ProductRepository $productRepo)
    {
        $products = $productRepo->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="produit")
     */
    public function show($slug, ProductRepository $productRepo)
    {
        $product = $productRepo->findOneBySlug($slug);   
        if (!$product) {
            return $this->redirectToRoute('produits');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
