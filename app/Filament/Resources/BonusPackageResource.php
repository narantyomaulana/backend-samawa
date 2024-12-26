<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BonusPackageResource\Pages;
use App\Filament\Resources\BonusPackageResource\RelationManagers;
use App\Models\BonusPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BonusPackageResource extends Resource
{
    protected static ?string $model = BonusPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

                Forms\Components\FileUpload::make('thumbnail')
                ->image()
                ->required(),

                Forms\Components\Textarea::make('about')
                ->required(),

                Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('IDR'),
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
                ->formatStateUsing(fn ($state) => 'Rp. ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('about')
                ->label('About')
                ->limit(50),

                Tables\Columns\ImageColumn::make('thumbnail'),
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
            'index' => Pages\ListBonusPackages::route('/'),
            'create' => Pages\CreateBonusPackage::route('/create'),
            'edit' => Pages\EditBonusPackage::route('/{record}/edit'),
        ];
    }
}
