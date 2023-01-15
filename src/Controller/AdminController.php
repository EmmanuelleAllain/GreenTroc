<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Entity\Category;
use App\Entity\ItemToBorrow;
use App\Repository\BorrowRepository;
use App\Repository\CategoryRepository;
use App\Repository\ItemToBorrowRepository;
use App\Repository\UserRepository;
use ContainerC0Bxosf\getChartjs_BuilderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataTransformer\WeekToArrayTransformer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function dashboard(ChartBuilderInterface $chartBuilder, UserRepository $userRepository, ItemToBorrowRepository $itemToBorrowRepository, BorrowRepository $borrowRepository, CategoryRepository $categoryRepository): Response
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

        //todo : set the chart in a servicewith labels and data in parameters
        $chart = $chartBuilder->createChart(CHART::TYPE_DOUGHNUT);
        $chart->setData([
            'labels' => $categoryNames,
            'datasets' => [
                [
                    'label' => 'Répartition par catégorie',
                    'backgroundColor' => [
                        "#0F5B01",
                        "#2ecc71",
                        "rgb(240,147,145)",
                        "#f39c12",],
                    'data' => $numberOfItemsByCategory,
                ],
            ],
        ]);
        
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
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }
}
