<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FollowerResource\Pages\CreateFollower;
use App\Filament\Resources\FollowerResource\Pages\EditFollower;
use App\Filament\Resources\FollowerResource\Pages\ListFollowers;
use App\Filament\Resources\FollowerResource\Pages\ViewFollower;
use App\Models\Follower;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FollowerResource extends Resource
{
    protected static ?string $model = Follower::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $activeNavigationIcon = 'heroicon-s-user-group';
    protected static ?string $navigationGroup = 'Many-To-Many';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('follower_id')
                    ->required()
                    ->numeric(),
                TextInput::make('following_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    TextColumn::make('follower.username')
                        ->badge(),
                ]),
            ])
            ->defaultGroup('following.username')
            ->contentGrid([
                'sm' => 2,
                'md' => 3,
                'xl' => 4,
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFollowers::route('/'),
            'create' => CreateFollower::route('/create'),
            'view' => ViewFollower::route('/{record}'),
            'edit' => EditFollower::route('/{record}/edit'),
        ];
    }
}
