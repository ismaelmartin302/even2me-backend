<?php

namespace App\Filament\Resources\EventTagResource\Pages;

use App\Filament\Resources\EventTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEventTag extends ViewRecord
{
    protected static string $resource = EventTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
