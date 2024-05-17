<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages\CreateComment;
use App\Filament\Resources\CommentResource\Pages\EditComment;
use App\Filament\Resources\CommentResource\Pages\ListComments;
use App\Filament\Resources\CommentResource\Pages\ViewComment;
use App\Models\Comment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';
    protected static ?string $activeNavigationIcon = 'heroicon-s-chat-bubble-oval-left-ellipsis';
    protected static ?string $navigationGroup = 'Many-To-Many';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user_id')
                    ->required()
                    ->numeric()
                    ->hidden(),
                Select::make('event_id')
                    ->required()
                    ->preload()
                    ->relationship(name: 'event', titleAttribute: 'name'),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.nickname')
                    ->sortable(),
                TextColumn::make('event_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
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
            'index' => ListComments::route('/'),
            'create' => CreateComment::route('/create'),
            'view' => ViewComment::route('/{record}'),
            'edit' => EditComment::route('/{record}/edit'),
        ];
    }
}
