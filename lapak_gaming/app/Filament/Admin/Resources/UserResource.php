<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\DateTimePickerComponent;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->required()
                                    ->unique(User::class, 'email', ignoreRecord: true),
                            ]),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => bcrypt($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn($operation) => $operation === 'create'),
                    ]),
                Section::make('Role & Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('role')
                                    ->options([
                                        'admin' => 'Administrator',
                                        'seller' => 'Seller',
                                        'buyer' => 'Buyer',
                                    ])
                                    ->required()
                                    ->native(false),
                                Select::make('status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                        'suspended' => 'Suspended',
                                    ])
                                    ->required()
                                    ->native(false),
                            ]),
                        Select::make('seller_status')
                            ->label('Seller Status')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->native(false),
                    ]),
                Section::make('Shop Information')
                    ->schema([
                        TextInput::make('shop_name')
                            ->label('Shop Name')
                            ->maxLength(255),
                        TextInput::make('shop_description')
                            ->label('Shop Description')
                            ->columnSpanFull(),
                    ]),
                Section::make('Security')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('email_verified_at')
                                    ->label('Email Verified')
                                    ->default(false),
                                Toggle::make('two_factor_enabled')
                                    ->label('Two-Factor Authentication Enabled')
                                    ->default(false),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'admin' => 'danger',
                        'seller' => 'warning',
                        'buyer' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                ToggleColumn::make('status')
                    ->label('Active')
                    ->sortable(),
                TextColumn::make('seller_status')
                    ->label('Seller Status')
                    ->badge()
                    ->visible(fn($record) => $record->role === 'seller')
                    ->color(fn($state) => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Administrator',
                        'seller' => 'Seller',
                        'buyer' => 'Buyer',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
