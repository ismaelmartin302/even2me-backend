<?php

namespace App\Filament\Resources\UserTagResource\Pages;

use App\Filament\Resources\UserTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserTag extends ViewRecord
{
    protected static string $resource = UserTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
