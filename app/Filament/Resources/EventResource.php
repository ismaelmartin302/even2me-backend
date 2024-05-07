<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $activeNavigationIcon = 'heroicon-s-calendar-days';
    protected static ?string $navigationGroup = 'Main';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric()
                    ->hidden(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Textarea::make('description')
                    ->maxLength(1200)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('€'),
                Forms\Components\TextInput::make('capacity')
                    ->numeric(),
                Forms\Components\TextInput::make('current_attendees')
                    ->numeric(),
                Forms\Components\TextInput::make('category')
                    ->required()
                    ->maxLength(30),
                Forms\Components\TextInput::make('picture')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->required()
                    ->default(now()),
                Forms\Components\DateTimePicker::make('finish_in'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->sortable()
                    ->label('User'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->suffix(fn ($record) => match ($record->price) { // Esto seguro que es optimizable Ismael no me jodas, lo hace del lado del cliente creo y podria hacerlo del lado del server
                        'Free' => '',
                        default => '€'
                    })
                    ->numeric(2, ",", ".", 2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_attendees')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('picture')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('finish_in')
                    ->dateTime()
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
