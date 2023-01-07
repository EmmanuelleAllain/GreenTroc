<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Entity\ItemToBorrow;
use App\Repository\BorrowRepository;
use App\Repository\ItemToBorrowRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function dashboard(UserRepository $userRepository, ItemToBorrowRepository $itemToBorrowRepository, BorrowRepository $borrowRepository): Response
    {
        $users = $userRepository->findAll();
        $itemsToBorrow = $itemToBorrowRepository->findAll();
        $borrows = $borrowRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'items' => $itemsToBorrow,
            'borrows' => $borrows,
        ]);
    }

    #[Route('/admin/utilisateurs', name: 'app_admin_users_control')]
    public function usersControl(UserRepository $userRepository): Response
    {
        // Todo : do not get the users with admin role
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }
}
