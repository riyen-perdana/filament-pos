<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Pengguna')
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
                ->badge($this->getModel()::where('is_aktif', 'Y')->count())
                ->icon('heroicon-o-check-circle')
                ->badgeColor('success'),
            'Tidak Aktif' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_aktif', 'N'))
                ->badge($this->getModel()::where('is_aktif', 'N')->count())
                ->icon('heroicon-o-x-circle')
                ->badgeColor('warning'),
        ];
    }
}
