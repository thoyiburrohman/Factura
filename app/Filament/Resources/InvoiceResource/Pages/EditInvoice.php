<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Invoice berhasil diperbarui!');
    }

    protected function getFormActions(): array
    {
        return [
            parent::getSaveFormAction()
                ->label('Save')
                ->icon('heroicon-s-check-circle'),
            parent::getCancelFormAction()
                ->icon('heroicon-s-backspace')
                ->color('danger'),
        ];
    }
}
