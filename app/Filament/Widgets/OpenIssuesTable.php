<?php

namespace App\Filament\Widgets;

use App\Models\CachedIssue;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OpenIssuesTable extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CachedIssue::query()
                    ->whereHas('extension', function (Builder $query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->where('state', 'open')
                    ->latest('last_updated_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('extension.extension_name')
                    ->label('Extension')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('issue_iid')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('author'),
                Tables\Columns\TextColumn::make('opened_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('last_updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->heading('Latest Open Issues')
            ->defaultPaginationPageOption(5);
    }
}
