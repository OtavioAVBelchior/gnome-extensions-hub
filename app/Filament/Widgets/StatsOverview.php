<?php

namespace App\Filament\Widgets;

use App\Models\CachedIssue;
use App\Models\CachedMergeRequest;
use App\Models\Extension;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $userId = Auth::id();

        $extensionsCount = Extension::where('user_id', $userId)->count();
        
        $openIssuesCount = CachedIssue::whereHas('extension', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('state', 'open')->count();

        $openMRsCount = CachedMergeRequest::whereHas('extension', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('state', 'open')->count();

        return [
            Stat::make('Tracked Projects', $extensionsCount)
                ->description('Projects registered in your dashboard')
                ->icon('heroicon-o-puzzle-piece'),
            Stat::make('Open Issues', $openIssuesCount)
                ->description('Total open issues across projects')
                ->color('danger')
                ->icon('heroicon-o-exclamation-circle'),
            Stat::make('Open Merge Requests', $openMRsCount)
                ->description('Total open PRs/MRs across projects')
                ->color('success')
                ->icon('heroicon-o-document-arrow-up'),
        ];
    }
}
