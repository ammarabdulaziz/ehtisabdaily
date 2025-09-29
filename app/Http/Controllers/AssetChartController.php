<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AssetChartController extends Controller
{
    /**
     * Get chart data for assets dashboard
     */
    public function getChartData(Request $request)
    {
        $user = Auth::user();
        
        // Get time range from request (default: last 2 years)
        $years = $request->get('years', 2);
        $startDate = Carbon::now()->subYears($years);
        
        // Get assets data for the user within the time range
        $assets = Asset::where('user_id', $user->id)
            ->where(function ($query) use ($startDate) {
                $query->where('year', '>', $startDate->year)
                    ->orWhere(function ($subQuery) use ($startDate) {
                        $subQuery->where('year', $startDate->year)
                            ->where('month', '>=', $startDate->month);
                    });
            })
            ->with(['accounts', 'lentMoney', 'borrowedMoney', 'investments', 'deposits'])
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Prepare chart data
        $chartData = [];
        
        foreach ($assets as $asset) {
            $period = sprintf('%04d-%02d', $asset->year, $asset->month);
            
            $chartData[] = [
                'period' => $period,
                'month' => $asset->month_name,
                'year' => $asset->year,
                'totalAccounts' => round($asset->total_accounts, 2),
                'totalLentMoney' => round($asset->total_lent_money, 2),
                'totalBorrowedMoney' => round($asset->total_borrowed_money, 2),
                'totalInvestments' => round($asset->total_investments, 2),
                'totalDeposits' => round($asset->total_deposits, 2),
                'grandTotal' => round($asset->grand_total, 2),
                'savings' => round($asset->savings, 2),
            ];
        }

        // Calculate summary statistics
        $summary = [
            'totalPeriods' => count($chartData),
            'currentNetWorth' => count($chartData) > 0 ? end($chartData)['grandTotal'] : 0,
            'totalSavings' => array_sum(array_column($chartData, 'savings')),
            'averageMonthlySavings' => count($chartData) > 0 ? array_sum(array_column($chartData, 'savings')) / count($chartData) : 0,
            'peakNetWorth' => count($chartData) > 0 ? max(array_column($chartData, 'grandTotal')) : 0,
            'lowestNetWorth' => count($chartData) > 0 ? min(array_column($chartData, 'grandTotal')) : 0,
        ];

        return response()->json([
            'chartData' => $chartData,
            'summary' => $summary,
            'timeRange' => [
                'years' => $years,
                'startDate' => $startDate->format('Y-m'),
                'endDate' => Carbon::now()->format('Y-m'),
            ]
        ]);
    }

    /**
     * Get lent money analysis data
     */
    public function getLentMoneyAnalysis(Request $request)
    {
        $user = Auth::user();
        
        // Get time range from request (default: last 2 years)
        $years = $request->get('years', 2);
        $startDate = Carbon::now()->subYears($years);
        
        // Get assets data for the user within the time range
        $assets = Asset::where('user_id', $user->id)
            ->where(function ($query) use ($startDate) {
                $query->where('year', '>', $startDate->year)
                    ->orWhere(function ($subQuery) use ($startDate) {
                        $subQuery->where('year', $startDate->year)
                            ->where('month', '>=', $startDate->month);
                    });
            })
            ->with(['lentMoney.friend'])
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Prepare trend data
        $trendData = [];
        foreach ($assets as $asset) {
            $period = sprintf('%04d-%02d', $asset->year, $asset->month);
            $trendData[] = [
                'period' => $period,
                'month' => $asset->month_name,
                'year' => $asset->year,
                'totalLentMoney' => round($asset->total_lent_money, 2),
                'lentMoneyCount' => $asset->lentMoney->count(),
            ];
        }

        // Get friend breakdown for current period
        $latestAsset = $assets->last();
        $friendBreakdown = [];
        $totalLentToFriends = 0;
        
        if ($latestAsset) {
            $friendTotals = [];
            foreach ($latestAsset->lentMoney as $lentMoney) {
                $friendName = $lentMoney->friend->name;
                if (!isset($friendTotals[$friendName])) {
                    $friendTotals[$friendName] = 0;
                }
                $friendTotals[$friendName] += $lentMoney->amount ?? 0;
                $totalLentToFriends += $lentMoney->amount ?? 0;
            }
            
            foreach ($friendTotals as $friendName => $amount) {
                $friendBreakdown[] = [
                    'name' => $friendName,
                    'value' => round($amount, 2),
                    'percentage' => $totalLentToFriends > 0 ? round(($amount / $totalLentToFriends) * 100, 1) : 0,
                ];
            }
        }

        // Calculate summary statistics
        $summary = [
            'totalLentMoney' => $totalLentToFriends,
            'totalFriends' => count($friendBreakdown),
            'averageLentPerFriend' => count($friendBreakdown) > 0 ? round($totalLentToFriends / count($friendBreakdown), 2) : 0,
            'peakLentMoney' => count($trendData) > 0 ? max(array_column($trendData, 'totalLentMoney')) : 0,
            'lowestLentMoney' => count($trendData) > 0 ? min(array_column($trendData, 'totalLentMoney')) : 0,
        ];

        return response()->json([
            'trendData' => $trendData,
            'friendBreakdown' => $friendBreakdown,
            'summary' => $summary,
            'timeRange' => [
                'years' => $years,
                'startDate' => $startDate->format('Y-m'),
                'endDate' => Carbon::now()->format('Y-m'),
            ]
        ]);
    }

    /**
     * Get asset allocation breakdown for current period
     */
    public function getAllocationBreakdown(Request $request)
    {
        $user = Auth::user();
        
        // Get the most recent asset data
        $latestAsset = Asset::where('user_id', $user->id)
            ->with(['accounts', 'lentMoney', 'borrowedMoney', 'investments', 'deposits'])
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        if (!$latestAsset) {
            return response()->json([
                'allocations' => [],
                'total' => 0,
                'period' => null
            ]);
        }

        $allocations = [
            [
                'name' => 'Accounts',
                'value' => round($latestAsset->total_accounts, 2),
                'percentage' => $latestAsset->grand_total > 0 ? round(($latestAsset->total_accounts / $latestAsset->grand_total) * 100, 1) : 0,
                'type' => 'asset'
            ],
            [
                'name' => 'Investments',
                'value' => round($latestAsset->total_investments, 2),
                'percentage' => $latestAsset->grand_total > 0 ? round(($latestAsset->total_investments / $latestAsset->grand_total) * 100, 1) : 0,
                'type' => 'asset'
            ],
            [
                'name' => 'Deposits',
                'value' => round($latestAsset->total_deposits, 2),
                'percentage' => $latestAsset->grand_total > 0 ? round(($latestAsset->total_deposits / $latestAsset->grand_total) * 100, 1) : 0,
                'type' => 'asset'
            ],
            [
                'name' => 'Lent Money',
                'value' => round($latestAsset->total_lent_money, 2),
                'percentage' => $latestAsset->grand_total > 0 ? round(($latestAsset->total_lent_money / $latestAsset->grand_total) * 100, 1) : 0,
                'type' => 'asset'
            ],
            [
                'name' => 'Borrowed Money',
                'value' => round($latestAsset->total_borrowed_money, 2),
                'percentage' => $latestAsset->grand_total > 0 ? round(($latestAsset->total_borrowed_money / $latestAsset->grand_total) * 100, 1) : 0,
                'type' => 'liability'
            ],
        ];

        return response()->json([
            'allocations' => $allocations,
            'total' => round($latestAsset->grand_total, 2),
            'period' => $latestAsset->formatted_period,
            'netWorth' => round($latestAsset->grand_total, 2)
        ]);
    }
}