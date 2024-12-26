<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\BonusPackage;
use App\Models\WeddingPackage;
use Filament\Resources\Resource;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WeddingPackageResource\Pages;
use App\Filament\Resources\WeddingPackageResource\RelationManagers;

class WeddingPackageResource extends Resource
{
    protected static ?string $model = WeddingPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Details')
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),


                        Forms\Components\FileUpload::make('thumbnail')
                            ->required()
                            ->image(),

                        Forms\Components\Repeater::make('photos')
                            ->relationship('photos')
                            ->schema([
                                Forms\Components\FileUpload::make('photo')
                                    ->required(),
                            ]),


                        Forms\Components\Repeater::make('weddingBonusPackages')
                            ->relationship('weddingBonusPackages')
                            ->schema([
                                Forms\Components\Select::make('bonus_package_id')
                                    ->label('Bonus Package')
                                    ->options(BonusPackage::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                            ]),

                    ]),


                Fieldset::make('Additional')
                    ->schema([

                        Forms\Components\Textarea::make('about')
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('IDR'),

                        Forms\Components\Select::make('is_popular')
                            ->options([
                                true => 'Popular',
                                false => 'Not Popular',
                            ])
                            ->required(),

                        Forms\Components\Select::make('city_id')
                            ->relationship('city', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('wedding_organizer_id')
                            ->relationship('weddingOrganizer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        // add is_active field
                        Forms\Components\Select::make('is_active')
                            ->options([
                                true => 'Active',
                                false => 'Inactive',
                            ])
                            ->required(),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn($state) => 'Rp. ' . number_format($state, 0, ',', '.')),

                Tables\Columns\ImageColumn::make('thumbnail'),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('City')
                    ->searchable(),

                Tables\Columns\TextColumn::make('weddingOrganizer.name')
                    ->label('Wedding Organizer')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_popular')
                    ->boolean()
                    ->trueColor('success')
                    ->false('danger')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->label('Popular'),

                // ToggleColumn::make('is_active'),
                SelectColumn::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ])
                    ->label('Status')
                    ->selectablePlaceholder(false),
            ])
            ->filters([

                SelectFilter::make('city_id')
                    ->relationship('city', 'name')
                    ->label('City'),

                SelectFilter::make('wedding_organizer_id')
                    ->relationship('weddingOrganizer', 'name')
                    ->label('Wedding Organizer'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                ->action(fn (WeddingPackage $record) => $record->delete())
                ->requiresConfirmation()
                ->modalHeading('Delete Wedding Package')
                ->modalDescription('Are you sure you\'d like to delete this wedding package? This cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete it')
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
            'index' => Pages\ListWeddingPackages::route('/'),
            'create' => Pages\CreateWeddingPackage::route('/create'),
            'edit' => Pages\EditWeddingPackage::route('/{record}/edit'),
        ];
    }
}
