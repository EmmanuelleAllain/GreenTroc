<?php

namespace App\Service;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService {

    public function setDoughnutChart(ChartBuilderInterface $chartBuilder, array $labels, array $data) {
        $chart = $chartBuilder->createChart(CHART::TYPE_DOUGHNUT);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'backgroundColor' => [
                        "#397A8D",
                        "#1CD6CE",
                        "#D61C4E",
                        "#FEDB39",
                        "#091114",
                        "#326878",
                    ],
                    'data' => $data,
                ],
            ],
        ]);
        $chart->setOptions([
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Répartition des objets par catégorie',
                ],
                'legend' => [
                    'display' => true,
                    'position' => 'left'
                ]
            ]
        ]);
        return $chart;
    }

    public function setLineChart(ChartBuilderInterface $chartBuilder, array $data) {
        $chart = $chartBuilder->createChart(CHART::TYPE_LINE);
        $chart->setData([
            'labels' => [
                'Janvier', 'Février', 'Mars','Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ],
            'datasets' => [
                [
                    'label' => 'Nombre de prêts',
                    'backgroundColor' => [
                        "#397A8D",
                    ],
                    'data' => $data,
                ],
            ],
        ]);
        $chart->setOptions([
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Evolution des prêts en 2022',
                ],
            ]
        ]);
        return $chart;
    }
}