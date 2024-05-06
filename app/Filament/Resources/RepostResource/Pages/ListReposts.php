<?php

namespace App\Filament\Resources\RepostResource\Pages;

use App\Filament\Resources\RepostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReposts extends ListRecords
{
    protected static string $resource = RepostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
