<?php

namespace App\Controller;

use App\Entity\ItemToBorrow;
use App\Repository\ItemToBorrowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/tous-les-objets', name: 'app_item')]
    public function index(ItemToBorrowRepository $itemToBorrowRepository): Response
    {
        $items = $itemToBorrowRepository->findAll();
        return $this->render('item/index.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route('/{id}', name: 'app_item_show')]
    public function show(ItemToBorrow $itemToBorrow): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $itemToBorrow,
        ]);
    }

    #[Route('/nouvel-objet', name: 'app_item_new')]
    public function new(): Response
    {
        return $this->render('item/new.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }
}
