<?php

namespace App\Filament\Resources\UnitResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\UnitResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUnit extends EditRecord
{
    protected static string $resource = UnitResource::class;
    protected static ?string $breadcrumb = 'Ubah Unit';
    protected static ?string $title = 'Ubah Unit';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Sukses')
            ->body('Data Unit Berhasil Diubah');
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
