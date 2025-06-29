<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages\{ListClients, CreateClient, EditClient};
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Components\{Hidden, Textarea, Grid, TextInput};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction};
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationGroup = 'Invoice Management';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-s-users';

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
                Hidden::make('user_id')->default(fn() => auth()->id()),
                Grid::make(3)->schema([
                    TextInput::make('name')
                        ->label('Nama Klien')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email')
                        ->email(),
                    TextInput::make('phone')
                        ->label('No Telp / Whatsapp')
                        ->numeric(),
                ]),
                Textarea::make('address')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->striped()
            ->recordUrl(null)
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Owner')
                    ->formatStateUsing(fn(string $state): string => Str::title($state))
                    ->searchable()
                    ->sortable()
                    ->visible(fn(): bool => auth()->user()->is_admin),
                TextColumn::make('name')
                    ->label('Nama Klien')
                    ->formatStateUsing(fn(string $state): string => Str::title($state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email Klien')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('No Telp/Whatsapp')
                    ->searchable()
                    ->sortable(),
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
            'index' => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'edit' => EditClient::route('/{record}/edit'),
        ];
    }
}
