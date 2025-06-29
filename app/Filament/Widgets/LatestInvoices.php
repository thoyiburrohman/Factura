<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Tables;
use Filament\Tables\Columns\{TextColumn, IconColumn};
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Str;

class LatestInvoices extends BaseWidget
{
    protected static ?int $sort = 2;
    protected  string|int|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Invoice::latest()->limit(5),
            )
            ->columns([
                TextColumn::make('number')->label('No. Invoice')->searchable(),
                TextColumn::make('client.name')->label('Nama Klien')->formatStateUsing(fn(string $state): string => Str::title($state)),
                TextColumn::make('total')->money('IDR', false),
                TextColumn::make('date')->label('Tanggal')->date(),
                IconColumn::make('status')
                    ->icon(fn(string $state): string => match ($state) {
                        'unpaid' => 'heroicon-s-x-circle',
                        'paid' => 'heroicon-s-check-badge',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'unpaid' => 'danger',
                        'paid' => 'success',
                    }),
            ]);
    }
}
