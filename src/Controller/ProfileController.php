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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ExpressionLanguage\Expression;

class ProfileController extends AbstractController {

        public function getCurrentUser() {
            /** @var User $user **/
            $user = $this->getUser();
            return $user;
    }

    #[Route('/mon-profil/{id}', requirements: ['id'=>'\d+'], name: 'app_myprofile')]
    //#[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")]
    public function myProfile(User $ownerUser, Request $request, UserRepository $userRepository): Response
    {

        //   if ($this->getCurrentUser() !== $ownerUser || !$this->getCurrentUser()->getRoles(['ROLE_ADMIN'])) {
        //       throw $this->createAccessDeniedException();
        //  }
        
        //  $this->denyAccessUnlessGranted(new Expression((
        //       '"ROLE_ADMIN" in role_names or (user == ownerUser, ["ownerUser" => $ownerUser])'
        //   )) );
        
        $form = $this->createForm(UserType::class, $ownerUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($ownerUser, true);
        }

        return $this->renderForm('profile/profile.html.twig', [
            'form' => $form,
            'user' => $ownerUser,
        ]);
    }

    #[Route('/mon-profil/mes-objets', name: 'app_myprofile_items')]
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

    #[Route('/mon-profil/suivi', name: 'app_myprofile_itemtrack')]
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

    #[Route('/mon-profil/nouvel-objet', name: 'app_myprofile_newitem')]
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
            //todo : add a flash message to confirm user form has been send
            return $this->redirectToRoute('app_myprofile_newitem', []);
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
        return $this->redirectToRoute('app_myprofile_items', [], Response::HTTP_SEE_OTHER);
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

    #[Route('/mon-profil/validation/{id}', name: 'app_item_validation')]
    public function valid(Borrow $borrow, BorrowRepository $borrowRepository): Response
    {
        $borrow->setStatus('Validé');
        $borrowRepository->add($borrow, true);
        return $this->redirectToRoute('app_item_track');
    }

    #[Route('/mon-profil/refus/{id}', name: 'app_item_refusal')]
    public function refuse(Borrow $borrow, BorrowRepository $borrowRepository): Response
    {
        $borrow->setStatus('Refusé');
        $borrowRepository->add($borrow, true);
        return $this->redirectToRoute('app_item_track');
    }

}