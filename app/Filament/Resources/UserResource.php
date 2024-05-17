<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use Filament\Forms;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Tables\Columns\Layout\Split as LayoutSplit;
use Filament\Infolists\Components\Split as ComponentsSplit;
use Filament\Infolists\Components\Section as ComponentsSection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $activeNavigationIcon = 'heroicon-s-user';
    protected static ?string $navigationGroup = 'Main';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Forms\Components\TextInput::make('username')
                        ->required()
                        ->alphaDash()
                        ->maxLength(40),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('password')
                        ->password()
                        ->confirmed()
                        ->revealable()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->maxLength(255),
                    TextInput::make('password_confirmation')
                        ->password()
                        ->revealable()
                        ->maxLength(255),
                    Section::make([
                        FileUpload::make('avatar')
                            ->image()
                            ->maxSize(528)
                            ->deletable(false)
                            ->avatar(),
                        FileUpload::make('banner')
                            ->image()
                            ->deletable(false)
                            ->visibility('private')
                            ->maxSize(528),
                    ])->columns(2),
                ])->heading('Required')->columns(2),
                Section::make([
                    TextInput::make('nickname')
                        ->maxLength(40)
                        ->ascii()
                        ->default(null),
                    TextInput::make('phone')
                        ->tel()
                        ->maxLength(15)
                        ->default(null),
                    TextInput::make('biography')
                        ->maxLength(160)
                        ->default(null),
                    TextInput::make('location')
                        ->maxLength(30)
                        ->default(null),
                    TextInput::make('website')
                        ->maxLength(100)
                        ->default(null),
                    DatePicker::make('birthday')
                        ->native(false),
                ])->heading('Personal Information'),
                Section::make([
                    Select::make('type')
                        ->options([
                            'user' => 'User',
                            'verified_user' => 'Verified',
                            'organization' => 'Organization',
                            'moderator' => 'Moderator',
                            'admin' => 'Admin',
                        ])
                        ->native(false)
                        ->default('user')
                        ->required()
                ])->heading(''),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    LayoutSplit::make([
                            ImageColumn::make('avatar')
                                ->circular()
                                ->visibility('private')
                                ->grow(false)
                                ->checkFileExistence(false),
                        Stack::make([
                            TextColumn::make('username')
                                ->weight(FontWeight::Bold)
                                ->searchable(),
                        ]),
                        Stack::make([
                            TextColumn::make('type')
                                ->searchable()
                                ->badge()
                                ->alignEnd()
                                ->color(fn (string $state): string => match ($state) {
                                    'user' => 'gray',
                                    'verified_user' => 'success',
                                    'organization' => 'warning',
                                    'moderator' => 'info',
                                    'admin' => 'danger',
                                }),
                        ]),
                    ]),
                    Stack::make([
                        Stack::make([
                            LayoutSplit::make([
                                TextColumn::make('followings_count')->counts('followings')
                                    ->suffix(' Following')
                                    ->grow(false),
                                TextColumn::make('followers_count')->counts('followers')
                                    ->suffix(' Followers'),
                            ]),
                            TextColumn::make('biography')
                                ->fontFamily('italic')
                                ->color('gray'),
                        ])->space(3),
                    ]),
                ])->space(2),
                Stack::make([
                    Stack::make([
                        TextColumn::make('email')
                            ->color('gray')
                            ->copyable()
                            ->icon('heroicon-m-envelope'),
                        TextColumn::make('phone')
                            ->color('gray')
                            ->copyable()
                            ->icon('heroicon-m-phone'),
                        TextColumn::make('location')
                            ->color('gray')
                            ->icon('heroicon-m-map-pin'),
                        TextColumn::make('website')
                            ->url(fn ($state) => $state, true)
                            ->formatStateUsing(fn () => 'Link')
                            ->color('info')
                            ->iconColor('gray')
                            ->icon('heroicon-m-link'),
                        TextColumn::make('birthday')
                            ->date()
                            ->color('gray')
                            ->icon('heroicon-m-calendar'),
                    ]),
                ])->collapsible(),
            ])
            ->contentGrid([
                'xl' => 2,
            ])
            ->actions([
                // ViewAction::make(),
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
        $infolist = Infolist::make()

            ->schema([
                ComponentsSection::make('Followers')
                    ->schema([
                        RepeatableEntry::make('followers')
                            ->label('')
                            ->schema([
                                ComponentsSplit::make([
                                    ImageEntry::make('follower.avatar')
                                        ->label('')
                                        ->height('5em')
                                        ->width('5em')
                                        ->grow(false),
                                    TextEntry::make('follower.username')
                                        ->label(''),
                                ])->verticalAlignment(VerticalAlignment::Center),
                            ]),
                    ])
                    ->collapsible()
                    ->columns([
                        'md' => 2,
                        'xl' => 3,
                    ]),
                ComponentsSection::make('Followings')
                    ->label('')
                    ->schema([
                        RepeatableEntry::make('followings')
                            ->schema([
                                ComponentsSplit::make([
                                    ImageEntry::make('following.avatar')
                                        ->label('')
                                        ->height('5em')
                                        ->width('5em')
                                        ->grow(false),
                                    TextEntry::make('following.username')
                                        ->label(''),
                                ])->verticalAlignment(VerticalAlignment::Center),
                            ]),
                    ])
                    ->collapsible()
                    ->columns([
                        'md' => 2,
                        'xl' => 3,
                    ]),
            ]);


            return $infolist;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            // 'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
