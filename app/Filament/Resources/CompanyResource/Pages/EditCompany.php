<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Perusahaan berhasil diperbarui!');
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
