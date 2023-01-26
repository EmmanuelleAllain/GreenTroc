<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Entity\ItemToBorrow;
use App\Entity\User;
use App\Form\RequestDateType;
use App\Repository\BorrowRepository;
use App\Repository\ItemToBorrowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function show(ItemToBorrow $itemToBorrow, Request $request, BorrowRepository $borrowRepository): Response
    {
        $form = $this->createForm(RequestDateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $askedDate = $form->getData()['askedDate'];
            // Todo : add validation if item is already borrowed
            // $borrowsinprogress = $borrowRepository->findBy([
            //     'borrowedItem' => $itemToBorrow,
            //     'date' => $askedDate,
            //     'status' => 'Validé'
            // ]);

            // if ($borrowsinprogress !== null) {
            //     $this->addFlash('warning', 'Cette date n\'est pas disponible, veuillez en choisir une autre.');
            // } else {
            $borrow = new Borrow;
            $borrow->setDate($askedDate);
            /** @var User $user */
            $user = $this->getUser();
            $borrow->setUserWhoBorrow($user);
            $borrow->setBorrowedItem($itemToBorrow);
            $borrow->setStatus('En attente');
            $borrowRepository->add($borrow, true);
            $this->addFlash('success', 'Votre demande a été envoyée au propriétaire, il peut maintenant l\'accepter ou la refuser.') == null;
        }
        return $this->renderForm('item/show.html.twig', [
            'item' => $itemToBorrow,
            'form' => $form,
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
