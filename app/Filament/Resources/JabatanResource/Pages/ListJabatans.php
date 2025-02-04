<?php

namespace App\Filament\Resources\JabatanResource\Pages;

use App\Filament\Resources\JabatanResource;
use App\Models\Jabatan;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListJabatans extends ListRecords
{
    protected static string $resource = JabatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Jabatan')
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
            'Aktif' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_aktif', 'Y'))
                ->badge(Jabatan::where('is_aktif', 'Y')->count())
                ->icon('heroicon-o-check-circle')
                ->badgeColor('success'),
            'Tidak Aktif' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_aktif', 'N'))
                ->badge(Jabatan::where('is_aktif', 'N')->count())
                ->icon('heroicon-o-x-circle')
                ->badgeColor('warning'),
        ];
    }
}
