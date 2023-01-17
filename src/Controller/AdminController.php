<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Entity\Category;
use App\Entity\ItemToBorrow;
use App\Entity\User;
use App\Repository\BorrowRepository;
use App\Repository\CategoryRepository;
use App\Repository\ItemToBorrowRepository;
use App\Repository\UserRepository;
use App\Service\ChartService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;

use function PHPUnit\Framework\throwException;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function dashboard(ChartBuilderInterface $chartBuilder, ChartService $chartService, UserRepository $userRepository, ItemToBorrowRepository $itemToBorrowRepository, BorrowRepository $borrowRepository, CategoryRepository $categoryRepository): Response
    {
        $users = $userRepository->findAll();
        $itemsToBorrow = $itemToBorrowRepository->findAll();
        $borrows = $borrowRepository->findAll();
        $categories = $categoryRepository->findAll();
        $numberOfItemsByCategory = [];
        $categoryNames = [];
        foreach ($categories as $category) {
            $numberOfItemsByCategory[] = count($category->getItem());
            $categoryNames[] = $category->getCategoryName();
        }
        $chart = $chartService->setDoughnutChart($chartBuilder, $categoryNames, $numberOfItemsByCategory);

        
        
        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'items' => $itemsToBorrow,
            'borrows' => $borrows,
            'chart' => $chart,
        ]);
    }

    #[Route('/admin/utilisateurs', name: 'app_admin_users_control')]
    public function usersControl(UserRepository $userRepository): Response
    {
        // Todo : do not get the users with admin role
        $users = $userRepository->findUsersByRole('ROLE_USER');

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/blocage-utilisateur/{id}', name: 'app_admin_block_user')]
    public function blockUser(User $user, UserRepository $userRepository) {
        // todo : create a new user with role admin and try to delete it to test it
        if ($user->getRoles() !== ['ROLE_USER']) {
            $this->addFlash('warning', 'Vous ne pouvez pas bloquer un utilisateur ayant un rôle administrateur');
        }
        else {
            $blockUserStatus = $user->setStatus(false);
            $userRepository->add($blockUserStatus, true);
        } 
        return $this->redirectToRoute('app_admin_users_control'); 
    }
    
    #[Route('/admin/deblocage-utilisateur/{id}', name: 'app_admin_unblock_user')]
    public function unblockUser(User $user, UserRepository $userRepository) {
        // todo : create a new user with role admin and try to delete it to test it
        if ($user->getRoles() !== ['ROLE_USER']) {
           $this->addFlash('warning', 'Vous ne pouvez pas débloquer un utilisateur ayant un rôle administrateur');
        }
        else {
            $blockUserStatus = $user->setStatus(true);
            $userRepository->add($blockUserStatus, true);
        }
        return $this->redirectToRoute('app_admin_users_control');
    }
}
