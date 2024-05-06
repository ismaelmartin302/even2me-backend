<?php

namespace App\Filament\Resources\PostLikeResource\Pages;

use App\Filament\Resources\PostLikeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePostLike extends CreateRecord
{
    protected static string $resource = PostLikeResource::class;
}
