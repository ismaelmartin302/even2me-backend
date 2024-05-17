<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages\CreateEvent;
use App\Filament\Resources\EventResource\Pages\EditEvent;
use App\Filament\Resources\EventResource\Pages\ListEvents;
use App\Filament\Resources\EventResource\Pages\ViewEvent;
use App\Models\Event;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                TextInput::make('user_id')
                    ->required()
                    ->numeric()
                    ->hidden(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                Textarea::make('description')
                    ->maxLength(1200)
                    ->columnSpanFull(),
                TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                TextInput::make('price')
                    ->numeric()
                    ->suffix('€'),
                TextInput::make('capacity')
                    ->numeric(),
                TextInput::make('current_attendees')
                    ->numeric(),
                TextInput::make('category')
                    ->required()
                    ->maxLength(30),
                TextInput::make('picture')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('website')
                    ->maxLength(255)
                    ->default(null),
                DateTimePicker::make('starts_at')
                    ->required()
                    ->default(now())
                    ->seconds(false)
                    ->timezone('Europe/Madrid')
                    ->native(false),
                DateTimePicker::make('finish_in')
                    ->seconds(false)
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Split::make([
                        ImageColumn::make('user.avatar')
                            ->grow(false)
                            ->circular(),
                        Split::make([
                            TextColumn::make('user.nickname')
                                ->action(function (Event $record): void {
                                    redirect()->route('users.view', $record->user_id); // <- esto va un poco lento la verdad, optimizable FIJO, url va mas rapido pero me jode el layout
                                }) 
                                // ->url(fn (Event $record): string => route('users.view' , ['user' => $record]))
                                ->label('User'),
                        ]),
                    ]),
                    TextColumn::make('name')
                        ->searchable(),
                    ImageColumn::make('picture')
                        ->searchable()
                        ->height('100%')
                        ->width('100%')
                        ->extraImgAttributes(['loading' => 'lazy']),
                    TextColumn::make('location')
                        ->searchable()
                        ->icon('heroicon-s-map-pin'),
                    TextColumn::make('starts_at')
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
                        TextColumn::make('description')
                            ->searchable(),
                        Split::make([
                            TextColumn::make('price')
                                ->suffix(fn ($record) => match ($record->price) { // Esto seguro que es optimizable Ismael no me jodas, lo hace del lado del cliente creo y podria hacerlo del lado del server
                                    'Free' => '',
                                    default => '€'
                                })
                                ->color('success')
                                ->numeric(2, ",", ".", 2)
                                ->sortable(),
                            TextColumn::make('capacity')
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
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'view' => ViewEvent::route('/{record}'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
