<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Invoice berhasil ditambahkan!');
    }

    protected function getFormActions(): array
    {
        return [
            parent::getCreateFormAction()
                ->label('Save')
                ->icon('heroicon-s-check-circle'),
            parent::getCancelFormAction()
                ->icon('heroicon-s-backspace')
                ->color('danger'),
        ];
    }
}
