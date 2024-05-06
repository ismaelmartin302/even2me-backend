<?php

namespace App\Filament\Resources\FollowerResource\Pages;

use App\Filament\Resources\FollowerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowers extends ListRecords
{
    protected static string $resource = FollowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
