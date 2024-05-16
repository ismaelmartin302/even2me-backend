<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
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
                    ->suffix('€'),
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
                    ->default(now())
                    ->native(false),
                Forms\Components\DateTimePicker::make('finish_in')
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Split::make([
                        Tables\Columns\ImageColumn::make('user.avatar')
                            ->grow(false)
                            ->circular(),
                        Split::make([
                            Tables\Columns\TextColumn::make('user.nickname')
                                ->action(function (Event $record): void {
                                    redirect()->route('users.view', $record->user_id); // <- esto va un poco lento la verdad, optimizable FIJO, url va mas rapido pero me jode el layout
                                }) 
                                // ->url(fn (Event $record): string => route('users.view' , ['user' => $record]))
                                ->label('User'),
                        ]),
                    ]),
                    Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                    Tables\Columns\ImageColumn::make('picture')
                        ->searchable()
                        ->height('100%')
                        ->width('100%')
                        ->extraImgAttributes(['loading' => 'lazy']),
                    Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->icon('heroicon-s-map-pin'),
                    Tables\Columns\TextColumn::make('starts_at')
                        ->icon('heroicon-s-calendar')
                        ->formatStateUsing(function ($state, Event $event) {
                            $startsAt = $event->starts_at->format('d/m H:i');
                            if ($event->finish_in) {
                                $finishIn = $event->finish_in->format('d/m H:i');
                                return "$startsAt - $finishIn";
                            } else {
                                return $startsAt;
                            }
                    }),
                ])->space(3),
                Panel::make([
                    Stack::make([
                        Tables\Columns\TextColumn::make('description')
                        ->searchable(),
                        Split::make([
                            Tables\Columns\TextColumn::make('price')
                                ->suffix(fn ($record) => match ($record->price) { // Esto seguro que es optimizable Ismael no me jodas, lo hace del lado del cliente creo y podria hacerlo del lado del server
                                    'Free' => '',
                                    default => '€'
                                })
                                ->color('success')
                                ->numeric(2, ",", ".", 2)
                                ->sortable(),
                            Tables\Columns\TextColumn::make('capacity')
                                ->icon('heroicon-s-user-group')
                                ->formatStateUsing(function ($state, Event $event) {
                                    if ($state != 'Unlimited' && $event->current_attendees < $state) {
                                        return $event->current_attendees . ' / ' . $state;
                                    } elseif ($state != 'Unlimited' && $event->current_attendees >= $state) {
                                        return 'Full';
                                    } else {
                                        return $state;
                                    }
                                })
                                ->color(function (string $state, Event $event) {
                                    if ($state != 'Unlimited' && $event->current_attendees <= $state) {
                                        $percentage = ($event->current_attendees / $state) * 100;
                                        if ($percentage >= 90) {
                                            return 'danger';
                                        } elseif ($percentage >= 75) {
                                            return 'warning';
                                        } else {
                                            return 'success'; 
                                        }
                                    } else {
                                        return 'info';
                                    }
                                }),
                        ]),
                    ]),
                ])->collapsible(),
            ])
            ->contentGrid([
                'md' => 2,
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
