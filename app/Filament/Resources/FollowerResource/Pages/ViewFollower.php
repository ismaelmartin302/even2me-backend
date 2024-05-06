<?php

namespace App\Filament\Resources\FollowerResource\Pages;

use App\Filament\Resources\FollowerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFollower extends ViewRecord
{
    protected static string $resource = FollowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
