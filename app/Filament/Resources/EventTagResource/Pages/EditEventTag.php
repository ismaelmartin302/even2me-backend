<?php

namespace App\Filament\Resources\EventTagResource\Pages;

use App\Filament\Resources\EventTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventTag extends EditRecord
{
    protected static string $resource = EventTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
