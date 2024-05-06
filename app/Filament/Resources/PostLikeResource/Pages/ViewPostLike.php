<?php

namespace App\Filament\Resources\PostLikeResource\Pages;

use App\Filament\Resources\PostLikeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPostLike extends ViewRecord
{
    protected static string $resource = PostLikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
