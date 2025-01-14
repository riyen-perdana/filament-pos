<?php

namespace App\Filament\Resources\AssetResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Filament\Resources\AssetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;
    protected static ?string $breadcrumb = 'Tambah Tarif Layanan Asset';
    protected static ?string $title = 'Tambah Tarif Layanan Asset';
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreateFormAction(): Action
    {
        return Actions\CreateAction::make()
            ->label('Tambah')
            ->color('success')
            ->icon('heroicon-o-plus')
            ->submit('create');
    }

    protected function getCancelFormAction(): Action
    {
        return Actions\Action::make('cancel')
            ->label('Batal')
            ->color('danger')
            ->icon('heroicon-o-x-mark')
            ->extraAttributes(['onclick' => 'window.history.back()']);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Sukses')
            ->body('Data Tarif Layanan Asset Berhasil Ditambahkan');
    }
}
