<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    public function index(ProductRepository $productRepo, Request $request)
    {

        $search = new Search;
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $productRepo->findWithSearch($search);
        } else {
            $products = $productRepo->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
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
