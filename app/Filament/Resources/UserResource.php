<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Builder;
use Rawilk\FilamentPasswordInput\Password;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns([
                        'sm' => 3,
                        'xl' => 6,
                        '2xl' => 9,
                    ])
                    ->schema([
                        Fieldset::make('Personal Details')
                        ->columns([
                            'sm' => 3,
                            'xl' => 6,
                            '2xl' => 9,
                        ])
    ->schema([
        Forms\Components\TextInput::make('first_name')
        ->maxLength(191)
        ->columnSpan(3),
    Forms\Components\TextInput::make('last_name')
        ->maxLength(191)
        ->columnSpan(3),
    ])
    ->columns(3),

    Fieldset::make('Account Details')
    ->columns([
        'sm' => 3,
        'xl' => 6,
        '2xl' => 9,
    ])
    ->schema([
        Select::make('role')
        ->label('Role')
        ->options(Role::all()->pluck('title', 'title'))
        ->searchable()
        ->columnSpan(3)
        ,
    Forms\Components\TextInput::make('email')
        ->email()
        ->required()
        ->maxLength(191)
        ->columnSpan(3),

    Password::make('password')
        ->columnSpan(3)
        // ->showPasswordIcon('heroicon-m-eye-slash')
        // ->hidePasswordIcon('heroicon-m-eye')
        ->label(fn (string $operation) => $operation == 'create' ? 'Password' : 'New Password')
        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
        ->dehydrated(fn (?string $state): bool => filled($state))
        ->required(fn (string $operation): bool => $operation === 'create')

    ]),


                    ]),

                // ->columnSpan(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
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
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
