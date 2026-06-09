<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Charts\SalesComparisonChart;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index(SalesComparisonChart $salesChart)
    {
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;

        /*
        |--------------------------------------------------------------------------
        | Monthly Sales Arrays
        |--------------------------------------------------------------------------
        */

        $thisYearSales = [];
        $lastYearSales = [];

        for ($month = 1; $month <= 12; $month++) {

            $thisYearSales[] = Transaction::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('total_amount');

            $lastYearSales[] = Transaction::whereYear('created_at', $lastYear)
                ->whereMonth('created_at', $month)
                ->sum('total_amount');
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard Cards
        |--------------------------------------------------------------------------
        */

        $incomeThisMonth = Transaction::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');

        $incomeThisYear = Transaction::whereYear('created_at', now()->year)
            ->sum('total_amount');

        $transactionsThisMonth = Transaction::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalPenalties = Transaction::sum('total_penalty');

        /*
        |--------------------------------------------------------------------------
        | Currnt Month Income
        |--------------------------------------------------------------------------
        */

        $currentMonthIncome = Transaction::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');

        $lastMonthIncome = Transaction::whereYear(
                'created_at',
                now()->copy()->subMonth()->year
            )
            ->whereMonth(
                'created_at',
                now()->copy()->subMonth()->month
            )
            ->sum('total_amount');

        if ($lastMonthIncome > 0) {
            $incomePercentageChange = (($currentMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100;
        } else {
            $incomePercentageChange = $currentMonthIncome > 0 ? 100 : 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Recent Transactions
        |--------------------------------------------------------------------------
        */

        $recentTransactions = Transaction::with('student')
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Chart
        |--------------------------------------------------------------------------
        */

        $chart = $salesChart->build(
            $thisYearSales,
            $lastYearSales
        );

        return view('dashboard', compact(
            'chart',
            'incomeThisMonth',
            'incomeThisYear',
            'transactionsThisMonth',
            'totalPenalties',
            'recentTransactions',
            'incomePercentageChange',
        ));
    }
}
