<?php

namespace App\Filament\Resources\FollowerResource\Pages;

use App\Filament\Resources\FollowerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFollower extends EditRecord
{
    protected static string $resource = FollowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
