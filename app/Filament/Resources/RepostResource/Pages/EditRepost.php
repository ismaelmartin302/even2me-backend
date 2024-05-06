<?php

namespace App\Filament\Resources\RepostResource\Pages;

use App\Filament\Resources\RepostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRepost extends EditRecord
{
    protected static string $resource = RepostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
