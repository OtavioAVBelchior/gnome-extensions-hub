<?php

namespace App\Filament\Resources\CachedMergeRequestResource\Pages;

use App\Filament\Resources\CachedMergeRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCachedMergeRequest extends EditRecord
{
    protected static string $resource = CachedMergeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
