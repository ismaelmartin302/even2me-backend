<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FollowerResource\Pages;
use App\Filament\Resources\FollowerResource\RelationManagers;
use App\Models\Follower;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FollowerResource extends Resource
{
    protected static ?string $model = Follower::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('follower_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('following_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('follower_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('following_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListFollowers::route('/'),
            'create' => Pages\CreateFollower::route('/create'),
            'view' => Pages\ViewFollower::route('/{record}'),
            'edit' => Pages\EditFollower::route('/{record}/edit'),
        ];
    }
}
