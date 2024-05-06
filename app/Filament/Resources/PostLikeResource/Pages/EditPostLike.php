<?php

namespace App\Filament\Resources\PostLikeResource\Pages;

use App\Filament\Resources\PostLikeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPostLike extends EditRecord
{
    protected static string $resource = PostLikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
