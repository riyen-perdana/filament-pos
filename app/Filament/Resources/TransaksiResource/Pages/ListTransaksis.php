<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TransaksiResource;

class ListTransaksis extends ListRecords
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Transaksi')
                ->color('success')
                ->icon('heroicon-s-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Seluruh Data' => Tab::make()
                ->icon('heroicon-o-archive-box')
                ->badge($this->getModel()::count())
                ->badgeColor('info'),
            'Menunggu Konfirmasi' => Tab::make()
                ->badge($this->getModel()::where('transaksi_status', 'Menunggu Konfirmasi')->count())
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->badgeColor('success'),
            'Dikonfirmasi' => Tab::make()
                ->badge($this->getModel()::where('transaksi_status', 'Dikonfirmasi')->count())
                ->icon('heroicon-o-bell-alert')
                ->badgeColor('warning'),
            'Selesai' => Tab::make()
                ->badge($this->getModel()::where('transaksi_status', 'Selesai')->count())
                ->icon('heroicon-o-document-check')
                ->badgeColor('gray'),
            'Dibatalkan' => Tab::make()
                ->badge($this->getModel()::where('transaksi_status', 'Dibatalkan')->count())
                ->icon('heroicon-o-archive-box-x-mark')
                ->badgeColor('danger'),
        ];
    }
}
