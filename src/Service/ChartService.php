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
                        "#091114",
                        "#326878",
                        "#112228",
                        "#2A5764",
                        "#1A343B",
                    ],
                    'data' => $data,
                ],
            ],
        ]);
        return $chart;
    }
}