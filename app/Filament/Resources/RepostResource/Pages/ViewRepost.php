<?php

namespace App\Filament\Resources\RepostResource\Pages;

use App\Filament\Resources\RepostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRepost extends ViewRecord
{
    protected static string $resource = RepostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
