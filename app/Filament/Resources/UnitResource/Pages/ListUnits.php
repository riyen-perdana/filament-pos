<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Models\Unit;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\UnitResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUnits extends ListRecords
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Unit')
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
                ->badge(Unit::where('is_aktif', 'Y')->count())
                ->icon('heroicon-o-check-circle')
                ->badgeColor('success'),
            'Tidak Aktif' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_aktif', 'N'))
                ->badge(Unit::where('is_aktif', 'N')->count())
                ->icon('heroicon-o-x-circle')
                ->badgeColor('warning'),
        ];
    }
}
