<?php

namespace App\Filament\Resources\KonsumenResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\KonsumenResource;

class EditKonsumen extends EditRecord
{
    protected static string $resource = KonsumenResource::class;
    protected static ?string $breadcrumb = 'Ubah Konsumen';
    protected static ?string $title = 'Ubah Konsumen';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Sukses')
            ->body('Data Jabatan Berhasil Diubah');
    }

    protected function getCancelFormAction(): Action
    {
        return Actions\Action::make('cancel')
            ->label('Batal')
            ->color('danger')
            ->icon('heroicon-o-x-mark')
            ->extraAttributes(['onclick' => 'window.history.back()']);
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Simpan')
            ->color('success')
            ->icon('heroicon-o-check');
    }
}
