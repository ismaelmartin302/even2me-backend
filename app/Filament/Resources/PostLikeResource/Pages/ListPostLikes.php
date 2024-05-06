<?php

namespace App\Filament\Resources\PostLikeResource\Pages;

use App\Filament\Resources\PostLikeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostLikes extends ListRecords
{
    protected static string $resource = PostLikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
