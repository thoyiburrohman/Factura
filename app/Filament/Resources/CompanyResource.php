<?php

namespace App\Filament\Resources;

use App\Banks;
use App\Filament\Resources\CompanyResource\Pages\{ListCompanies, CreateCompany, EditCompany};
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Components\{Section, TextInput, Textarea, Select};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\{EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationGroup = 'Invoice Management';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $activeNavigationIcon = 'heroicon-s-building-office-2';

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
                Section::make('Informasi Perusahaan')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->email(),
                        TextInput::make('phone'),
                        Textarea::make('address')->columnSpanFull(),
                    ])->columns(3),
                Section::make('Informasi Pembayaran')
                    ->schema([
                        Select::make('bank_name')
                            ->label('Nama Bank')
                            ->options(Banks::toArray()) // <-- Memanggil method helper kita
                            ->searchable() // <-- Membuat dropdown bisa dicari
                            ->required(),
                        TextInput::make('bank_account_holder')->label('Atas Nama'),
                        TextInput::make('bank_account_number')->label('Nomor Rekening'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->striped()
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Perusahaan')
                    ->formatStateUsing(fn(string $state): string => Str::title($state))
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('No Telp')
                    ->searchable(),
                TextColumn::make('bank_name')
                    ->label('Nama Bank')
                    ->searchable(),
                TextColumn::make('bank_account_holder')
                    ->label('Nama Akun Bank')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->searchable(),
                TextColumn::make('bank_account_number')
                    ->label('No Rekening')
                    ->searchable(),
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
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }
}
