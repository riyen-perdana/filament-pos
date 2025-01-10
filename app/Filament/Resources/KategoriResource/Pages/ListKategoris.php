<?php

namespace App\Filament\Resources\KategoriResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Builder;

class ListKategoris extends ListRecords
{
    protected static string $resource = KategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kategori')
                ->color('success')
                ->icon('heroicon-s-plus')
            ,
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
                ->badge(Kategori::where('is_aktif', 'Y')->count())
                ->icon('heroicon-o-check-circle')
                ->badgeColor('success'),
            'Tidak Aktif' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_aktif', 'N'))
                ->badge(Kategori::where('is_aktif', 'N')->count())
                ->icon('heroicon-o-x-circle')
                ->badgeColor('warning'),
        ];
    }
}
