<?php

namespace App\Filament\Resources;

use App\Models\Event;
use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Split as InfoSplit;
use App\Filament\Resources\EventResource\Pages\EditEvent;
use App\Filament\Resources\EventResource\Pages\ViewEvent;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Resources\EventResource\Pages\ListEvents;
use App\Filament\Resources\EventResource\Pages\CreateEvent;

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
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Event Details')
                    ->schema([
                        InfoSplit::make([
                            ImageEntry::make('user.avatar')
                                ->label('')
                                ->circular()
                                ->grow(false)
                                ->url(fn (Event $record): string => route('users.view', ['user' => $record->user_id])),
                            TextEntry::make('user.nickname')
                                ->label('')
                                ->url(fn (Event $record): string => route('users.view', ['user' => $record->user_id]))
                                ->weight(FontWeight::Bold),
                        ])->verticalAlignment(VerticalAlignment::Center),
    
                        Section::make([
                            InfoSplit::make([
                                TextEntry::make('name')
                                    ->label('')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('category')
                                    ->label('')
                                    ->alignEnd()
                                    ->badge(),
                            ]),
    
                            Section::make([
                                TextEntry::make('description')
                                    ->label(''),
    
                                InfoSplit::make([
                                    TextEntry::make('location')
                                        ->label('')
                                        ->icon('heroicon-m-map-pin'),
                                    TextEntry::make('price')
                                        ->label('')
                                        ->suffix('€')
                                        ->color('success'),
                                ]),
    
                                InfoSplit::make([
                                    TextEntry::make('capacity')
                                        ->label('Capacity')
                                        ->icon('heroicon-m-user-group'),
                                    TextEntry::make('current_attendees')
                                        ->label('Current Attendees'),
                                ]),
    
                                TextEntry::make('website')
                                    ->label('Website')
                                    ->url(fn ($state) => $state)
                                    ->icon('heroicon-m-link')
                                    ->iconColor('gray')
                                    ->color('info')
                                    ->weight(FontWeight::Bold),
    
                                InfoSplit::make([
                                    TextEntry::make('starts_at')
                                        ->label('Starts At')
                                        ->dateTime(),
                                    TextEntry::make('finish_in')
                                        ->label('Finish In')
                                        ->dateTime(),
                                ]),
                            ])->aside(),
                        ]),
                    ])->columns(2),
                
                InfoSplit::make([
                        RepeatableEntry::make('comments')
                            ->label('')
                            ->schema([
                                InfoSplit::make([
                                    ImageEntry::make('user.avatar')
                                        ->label('')
                                        ->height('5em')
                                        ->width('5em')
                                        ->grow(false)
                                        ->circular()
                                        ->url(fn (Comment $record): string => route('users.view', ['user' => $record->user_id])),
                                    TextEntry::make('user.username')
                                        ->label('')
                                        ->url(fn (Comment $record): string => route('users.view', ['user' => $record->user_id]))
                                        ->weight(FontWeight::Bold),
                                    ])->verticalAlignment(VerticalAlignment::Center),
                                    TextEntry::make('content')
                                        ->label('')
                                        ->grow(),
    
                                RepeatableEntry::make('comments')
                                    ->label('Replies')
                                    ->schema([
                                        InfoSplit::make([
                                            ImageEntry::make('user.avatar')
                                                ->label('')
                                                ->height('5em')
                                                ->width('5em')
                                                ->grow(false)
                                                ->circular()
                                                ->url(fn (Comment $record): string => route('users.view', ['user' => $record->user_id])),
                                            TextEntry::make('user.username')
                                                ->label('')
                                                ->url(fn (Comment $record): string => route('users.view', ['user' => $record->user_id]))
                                                ->weight(FontWeight::Bold),
                                        ])->verticalAlignment(VerticalAlignment::Center),
                                        TextEntry::make('content')
                                            ->label('')
                                            ->grow(),
                                    ])
                                    ->hidden(fn ($record) => $record->parent_comment_id !== null)
                                ])
                ])
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
