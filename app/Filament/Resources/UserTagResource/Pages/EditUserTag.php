<?php

namespace App\Filament\Resources\UserTagResource\Pages;

use App\Filament\Resources\UserTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserTag extends EditRecord
{
    protected static string $resource = UserTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
