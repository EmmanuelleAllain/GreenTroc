<?php

namespace App\Controller;

use App\Entity\ItemToBorrow;
use App\Entity\User;
use App\Form\ItemToBorrowType;
use App\Repository\ItemToBorrowRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\throwException;

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

    #[Route('/mes-objets/{id}', name: 'app_myitem')]
    public function myIndex(UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user == null) {
            throw $this->createAccessDeniedException();
        }
        $myitems = $user->getItemToBorrows();
        return $this->render('item/profile_index.html.twig', [
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

    #[Route('/delete/{id}', name: 'app_item_delete')]
    public function delete(Request $request, ItemToBorrow $itemToBorrow, ItemToBorrowRepository $itemToBorrowRepository): Response
    {
        if (is_string($request->request->get('_token')) || is_null($request->request->get('_token'))) {
            if ($this->isCsrfTokenValid('delete' . $itemToBorrow->getId(), $request->request->get('_token'))) {
                $itemToBorrowRepository->remove($itemToBorrow, true);
            } else {
                throw new Exception('Impossible de supprimer le gateau');
            }
        }
        return $this->redirectToRoute('app_myitem', [], Response::HTTP_SEE_OTHER);
    }
}
