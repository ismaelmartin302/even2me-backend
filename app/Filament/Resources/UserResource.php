<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split as LayoutSplit;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

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
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        // ->required()
                        ->confirmed()
                        ->revealable()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password_confirmation')
                        // ->required()
                        ->password()
                        ->revealable()
                        ->maxLength(255),
                    Section::make([
                        Forms\Components\FileUpload::make('avatar')
                            ->image()
                            ->maxSize(528)
                            ->deletable(false)
                            ->avatar(),
                        // ->default('default.png'),
                        Forms\Components\FileUpload::make('banner')
                            ->image()
                            ->deletable(false)
                            ->visibility('private')
                            ->maxSize(528),
                    ])->columns(2),
                ])->heading('Required')->columns(2),
                Section::make([
                    Forms\Components\TextInput::make('nickname')
                        ->maxLength(40)
                        ->ascii()
                        ->default(null),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(15)
                        ->default(null),
                    Forms\Components\TextInput::make('biography')
                        ->maxLength(160)
                        ->default(null),
                    Forms\Components\TextInput::make('location')
                        ->maxLength(30)
                        ->default(null),
                    Forms\Components\TextInput::make('website')
                        ->maxLength(100)
                        ->default(null),
                    Forms\Components\DatePicker::make('birthday')
                        ->native(false),
                ])->heading('Personal Information'),
                Section::make([
                    Forms\Components\Select::make('type')
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
                            Tables\Columns\ImageColumn::make('avatar')
                                ->circular()
                                ->visibility('private')
                                ->grow(false)
                                ->checkFileExistence(false),
                        Stack::make([
                            Tables\Columns\TextColumn::make('username')
                                ->weight(FontWeight::Bold)
                                ->searchable(),
                        ]),
                        Stack::make([
                            Tables\Columns\TextColumn::make('type')
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
                                Tables\Columns\TextColumn::make('followings_count')->counts('followings')
                                    ->suffix(' Following')
                                    ->grow(false),
                                Tables\Columns\TextColumn::make('followers_count')->counts('followers')
                                    ->suffix(' Followers'),
                            ]),
                            Tables\Columns\TextColumn::make('biography')
                                ->fontFamily('italic')
                                ->color('gray'),
                        ])->space(3),
                    ]),
                ])->space(2),
                Stack::make([
                    Stack::make([
                        Tables\Columns\TextColumn::make('email')
                            ->color('gray')
                            ->copyable()
                            ->icon('heroicon-m-envelope'),
                        Tables\Columns\TextColumn::make('phone')
                            ->color('gray')
                            ->copyable()
                            ->icon('heroicon-m-phone'),
                        Tables\Columns\TextColumn::make('location')
                            ->color('gray')
                            ->icon('heroicon-m-map-pin'),
                        Tables\Columns\TextColumn::make('website')
                            ->url(fn ($state) => $state, true)
                            ->formatStateUsing(fn () => 'Link')
                            ->color('info')
                            ->iconColor('gray')
                            ->icon('heroicon-m-link'),
                        Tables\Columns\TextColumn::make('birthday')
                            ->date()
                            ->color('gray')
                            ->icon('heroicon-m-calendar'),
                    ]),
                ])->collapsible(),
            ])
            ->contentGrid([
                'xl' => 2,
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
