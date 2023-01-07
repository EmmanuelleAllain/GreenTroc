<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Entity\ItemToBorrow;
use App\Entity\User;
use App\Form\ItemToBorrowType;
use App\Form\UserType;
use App\Repository\BorrowRepository;
use App\Repository\ItemToBorrowRepository;
use App\Repository\UserRepository;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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

    #[IsGranted('ROLE_USER')]
    #[Route('/mon-profil', name: 'app_myprofile')]
    public function myProfile(Request $request, UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user == null) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user == null || !$user->getRoles('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }
            $userRepository->add($user, true);
        }

        return $this->renderForm('profile/profile.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/mes-objets', name: 'app_myitem')]
    public function myIndex(UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user == null) {
            throw $this->createAccessDeniedException();
        }
        $myitems = $user->getItemToBorrows();
        return $this->render('profile/index.html.twig', [
            'myitems' => $myitems,
        ]);
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

        // remove from the array all the dates with refuse status, because they can be available
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

    #[IsGranted('ROLE_USER')]
    #[Route('/nouvel-objet', name: 'app_item_new')]
    public function new(ItemToBorrowRepository $itemToBorrowRepository, Request $request): Response
    {
        $item = new ItemToBorrow();
        $user = $this->getUser();
        $form = $this->createForm(ItemToBorrowType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user == null) {
                throw $this->createAccessDeniedException();
            }
            $item->setUserWhoOffer($user);
            $itemToBorrowRepository->add($item, true);
        }

        return $this->renderForm('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/delete/{id}', name: 'app_item_delete')]
    public function delete(Request $request, ItemToBorrow $itemToBorrow, ItemToBorrowRepository $itemToBorrowRepository): Response
    {
        if (is_string($request->request->get('_token')) || is_null($request->request->get('_token'))) {
            if ($this->isCsrfTokenValid('delete' . $itemToBorrow->getId(), $request->request->get('_token'))) {
                $itemToBorrowRepository->remove($itemToBorrow, true);
            } else {
                throw new Exception('Impossible de supprimer cet objet');
            }
        }
        /** @var User $user */
        $user = $this->getUser();
        return $this->redirectToRoute('app_myitem', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/mon-suivi', name: 'app_item_track')]
    public function track(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user == null) {
            throw $this->createAccessDeniedException();
        }
        $borrows = $user->getBorrows();
        $itemToBorrow = $user->getItemToBorrows();

        return $this->render('profile/track.html.twig', [
            'borrows' => $borrows,
            'items' => $itemToBorrow
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/ma-demande/objet/{id}', name: 'app_item_ask')]
    public function ask(ItemToBorrow $itemToBorrow, BorrowRepository $borrowRepository): Response
    {
        if(isset($_POST['askedDate'])) {
            $askedDate = new DateTime($_POST['askedDate']);
            $borrowsinprogress = $borrowRepository->findBy([
                'borrowedItem' => $itemToBorrow,
                'date' => $askedDate
            ]);

            if ($borrowsinprogress != null) {
                $this->addFlash('warning', 'Cette date n\'est pas disponible, veuillez en choisir une autre.');
            } else {
                $borrow = new Borrow();
                $borrow->setDate($askedDate);
                /** @var User $user */
                $user = $this->getUser();
                $borrow->setUserWhoBorrow($user);
                $borrow->setBorrowedItem($itemToBorrow);
                $borrow->setStatus('En attente');
                $borrowRepository->add($borrow, true);
                $this->addFlash('success', 'Votre demande a été envoyée au propriétaire, il peut maintenant l\'accepter ou la refuser.') == null;
            }
        }
        return $this->redirectToRoute('app_item_show', ['id' => $itemToBorrow->getId()]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/validation/{id}', name: 'app_item_validation')]
    public function valid(Borrow $borrow, BorrowRepository $borrowRepository): Response
    {
        $borrow->setStatus('Validé');
        $borrowRepository->add($borrow, true);
        return $this->redirectToRoute('app_item_track');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/refusal/{id}', name: 'app_item_refusal')]
    public function refuse(Borrow $borrow, BorrowRepository $borrowRepository): Response
    {
        $borrow->setStatus('Refusé');
        $borrowRepository->add($borrow, true);
        return $this->redirectToRoute('app_item_track');
    }
}
