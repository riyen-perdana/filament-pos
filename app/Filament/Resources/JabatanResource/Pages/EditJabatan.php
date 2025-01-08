<?php

namespace App\Filament\Resources\JabatanResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\JabatanResource;

class EditJabatan extends EditRecord
{
    protected static string $resource = JabatanResource::class;
    protected static ?string $breadcrumb = 'Ubah Jabatan';
    protected static ?string $title = 'Ubah Jabatan';

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
