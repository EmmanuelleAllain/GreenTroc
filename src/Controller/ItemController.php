<?php

namespace App\Controller;


use App\Entity\ItemToBorrow;
use App\Repository\BorrowRepository;
use App\Repository\ItemToBorrowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/tous-les-objets/{keyword}', name: 'app_item_search')]
    public function search(ItemToBorrowRepository $itemToBorrowRepository, string $keyword): Response
    {
        $itemToBorrow = $itemToBorrowRepository->findIdsItemByKeyword($keyword);
        return new JsonResponse($itemToBorrow);
    }

    #[Route('/objet/{id}', name: 'app_item_show')]
    public function show(ItemToBorrow $itemToBorrow): Response
    {

        return $this->render('item/show.html.twig', [
            'item' => $itemToBorrow,
        ]);
    }

    //route to disable dates on the calendar
    #[Route('/disableDates/{id}', name: 'app_disable_dates')]
    public function disableDates(ItemToBorrow $itemToBorrow, BorrowRepository $borrowRepository): Response
    {
        $borrows = $borrowRepository->findBy([
            'borrowedItem' => $itemToBorrow
        ]);
        $dates = [];

        // remove from the array all the dates with refuse status, because they cannot be available
        for ($i = 0; $i < count($borrows); $i++) {
            if ($borrows[$i]->getStatus() === "Refusé") {
                unset($borrows[$i]); 
            }
        }

        foreach ($borrows as $borrow) {
            $dates[] = $borrow->getDate();
        }
        $disableDates = json_encode($dates);
        return new Response($disableDates);
    }
}
