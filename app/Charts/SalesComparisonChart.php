<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class SalesComparisonChart
{
    public function build(array $thisYearSales, array $lastYearSales): \ArielMejiaDev\LarapexCharts\BarChart
    {
        return (new LarapexChart)
            ->barChart()
            ->setTitle('Revenue Analytics')
            ->setSubtitle('This Year vs Last Year')
            ->addData($lastYearSales, 'Last Year')
            ->addData($thisYearSales, 'This Year')
            ->setXAxis([
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec',
            ]);
    }
}
