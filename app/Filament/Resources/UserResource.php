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
                        ->required()
                        ->confirmed()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password_confirmation')
                        ->required()
                        ->password()
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
                        ->alphaNum()
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
                LayoutSplit::make([
                        Tables\Columns\ImageColumn::make('avatar')
                            ->circular()
                            ->visibility('private')
                            ->grow(false)
                            ->checkFileExistence(false),
                    Stack::make([
                        Tables\Columns\TextColumn::make('username')
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
                Panel::make([
                    Stack::make([
                        Tables\Columns\TextColumn::make('email')
                            ->icon('heroicon-m-envelope'),
                        Tables\Columns\TextColumn::make('phone')
                            ->icon('heroicon-m-phone'),
                        Tables\Columns\TextColumn::make('location')
                            ->icon('heroicon-m-map-pin'),
                        Tables\Columns\TextColumn::make('website')
                            ->url(fn ($state) => $state, true)
                            ->formatStateUsing(fn () => 'Link')
                            ->color('info')
                            ->iconColor('gray')
                            ->icon('heroicon-m-link'),
                        Tables\Columns\TextColumn::make('birthday')
                            ->date()
                            ->icon('heroicon-m-calendar'),
                    ]),
                ])->collapsible(),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
