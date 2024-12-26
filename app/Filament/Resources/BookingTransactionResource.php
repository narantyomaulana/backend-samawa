<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\WeddingPackage;
use Filament\Resources\Resource;
use App\Models\BookingTransaction;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Filters\SelectFilter;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([

                    Forms\Components\Wizard\Step::make('Product and Price')
                        ->schema([

                            Grid::make(2)
                                ->schema([
                                    Forms\Components\Select::make('wedding_package_id')
                                        ->relationship('weddingPackage', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $weddingPackage = WeddingPackage::find($state);
                                            $price = $weddingPackage ? $weddingPackage->price : 0;

                                            $set('price', $price);

                                            $tax = 0.11;
                                            $totalTaxAmount = $tax * $price;

                                            $totalAmount = $price + $totalTaxAmount;
                                            $set('total_amount', number_format($totalAmount, 0, '', ''));
                                            $set('total_tax_amount', number_format($totalTaxAmount, 0, '', ''));
                                        }),

                                    Forms\Components\TextInput::make('price')
                                        ->required()
                                        ->readOnly()
                                        ->numeric()
                                        ->prefix('IDR'),

                                    Forms\Components\TextInput::make('total_amount')
                                        ->required()
                                        ->readOnly()
                                        ->numeric()
                                        ->prefix('IDR'),


                                    Forms\Components\TextInput::make('total_tax_amount')
                                        ->required()
                                        ->readOnly()
                                        ->numeric()
                                        ->prefix('IDR'),

                                    Forms\Components\DatePicker::make('started_at')
                                        ->required(),
                                ]),
                        ]),

                    Forms\Components\Wizard\Step::make('Customer Information')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->maxLength(255)
                                        ->required(),

                                    Forms\Components\TextInput::make('email')
                                        ->maxLength(255)
                                        ->required(),

                                    Forms\Components\TextInput::make('phone')
                                        ->maxLength(255)
                                        ->required(),
                                ]),
                        ]),

                    Forms\Components\Wizard\Step::make('Payment Information')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    Forms\Components\TextInput::make('booking_trx_id')
                                        ->maxLength(255)
                                        ->required(),
                                    ToggleButtons::make('is_paid')
                                        ->label('Apakah sudah membayar?')
                                        ->boolean()
                                        ->grouped()
                                        ->icons([
                                            true => 'heroicon-o-pencil',
                                            false => 'heroicon-o-clock',
                                        ])
                                        ->required(),

                                    Forms\Components\FileUpload::make('proof')
                                        ->image()
                                        ->required(),
                                ]),
                        ]),

                    ])
                ->columnSpan('full')
                ->columns(1)
                ->skippable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('weddingPackage.thumbnail'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('booking_trx_id')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label('Terverifikasi'),
            ])
            ->filters([
                SelectFilter::make('wedding_package_id')
                    ->relationship('weddingPackage', 'name')
                    ->label('Wedding Package'),
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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}
