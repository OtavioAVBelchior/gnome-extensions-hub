<?php

namespace App\Filament\Resources\CachedIssueResource\Pages;

use App\Filament\Resources\CachedIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCachedIssue extends EditRecord
{
    protected static string $resource = CachedIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
