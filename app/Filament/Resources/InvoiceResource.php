<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages\{ListInvoices, CreateInvoice, EditInvoice};
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{DatePicker, Select, TextInput, Repeater, Hidden, Section};
use Filament\Tables\Actions\{Action, EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction};
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Illuminate\Support\Str;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationGroup = 'Invoice Management';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $activeNavigationIcon = 'heroicon-s-document-text';

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        // Jika user adalah admin, tampilkan semua data tanpa filter.
        if ($user->is_admin) {
            return parent::getEloquentQuery();
        }

        // Jika bukan admin, hanya tampilkan data milik user yang sedang login.
        return parent::getEloquentQuery()->where('user_id', $user->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Invoice')->schema([
                    Hidden::make('user_id')->default(fn() => auth()->id()),
                    TextInput::make('number')
                        ->label('No Invoice')
                        ->default(
                            fn(string $context): ?string =>
                            // Jika konteksnya adalah 'create', panggil generator kita.
                            // Jika tidak (misal 'edit'), biarkan null agar diisi oleh data model.
                            $context === 'create' ? Invoice::generateNextInvoiceNumber() : null
                        )
                        ->disabled()
                        ->dehydrated()
                        ->required() // Tetap required untuk integritas data
                        ->unique(Invoice::class, 'number', ignoreRecord: true),
                    DatePicker::make('date')
                        ->label('Tanggal Invoice')
                        ->default(now())
                        ->required(),
                    DatePicker::make('due_date')
                        ->label('Tanggal Jatuh Tempo')
                        ->required(),
                    Select::make('client_id')
                        ->label('Nama Klien')
                        ->relationship('client', 'name')
                        ->preload()
                        ->required()
                        ->searchable(),
                    Select::make('company_id')
                        ->label('Nama Perusahaan')
                        ->relationship('company', 'name')
                        ->preload()
                        ->required()
                        ->searchable(),
                    Select::make('status')
                        ->label('Status Pembayaran')
                        ->options([
                            'unpaid' => 'Unpaid',
                            'paid' => 'Paid',
                        ])
                        ->required(),
                    Hidden::make('total')
                        ->label('Total Biaya')
                        ->dehydrated(true)
                        ->dehydrateStateUsing(
                            fn($state, callable $get) =>
                            collect($get('items') ?? [])
                                ->sum(fn($item) => $item['quantity'] * $item['unit_price'])
                        ),
                ]),
                Section::make('Detail Invoice')->schema([
                    Repeater::make('items')
                        ->label('Invoice Items')
                        ->relationship()
                        ->schema([
                            TextInput::make('description')->label('Keterangan')->required()->maxLength(255),
                            TextInput::make('quantity')->label('Jumlah')->numeric()->required(),
                            TextInput::make('unit_price')->label('Nominal')->numeric()->required(),
                        ])
                        ->columns(3)
                        ->columnSpanFull(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->striped()
            ->recordUrl(null)
            ->columns([
                TextColumn::make('client.name')
                    ->label('Nama Klien')
                    ->formatStateUsing(fn(string $state): string => Str::title($state))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Nama User')
                    ->formatStateUsing(fn(string $state): string => Str::title($state))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('number')
                    ->label('No Invoice')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Tgl Invoice')
                    ->date()
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                IconColumn::make('status')
                    ->icon(fn(string $state): string => match ($state) {
                        'unpaid' => 'heroicon-s-x-circle',
                        'paid' => 'heroicon-s-check-badge',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'unpaid' => 'danger',
                        'paid' => 'success',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->label('')
                    ->tooltip('Edit')
                    ->color('warning'),
                DeleteAction::make()
                    ->label('')
                    ->tooltip('Delete')
                    ->color('danger'),
                Action::make('downloadPdf')
                    ->label('')
                    ->tooltip('View')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn(Invoice $record): string => route('invoices.previewPdf', $record->id))
                    // Sekarang kita bisa dengan aman menggunakan openUrlInNewTab()
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'edit' => EditInvoice::route('/{record}/edit'),
        ];
    }
}
