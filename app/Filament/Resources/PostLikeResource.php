<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostLikeResource\Pages;
use App\Filament\Resources\PostLikeResource\RelationManagers;
use App\Models\PostLike;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostLikeResource extends Resource
{
    protected static ?string $model = PostLike::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $activeNavigationIcon = 'heroicon-s-heart';
    protected static ?string $navigationGroup = 'Many-To-Many';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostLikes::route('/'),
            'create' => Pages\CreatePostLike::route('/create'),
            'view' => Pages\ViewPostLike::route('/{record}'),
            'edit' => Pages\EditPostLike::route('/{record}/edit'),
        ];
    }
}
