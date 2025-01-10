<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateUnit extends CreateRecord
{
    protected static string $resource = UnitResource::class;
    protected static ?string $breadcrumb = 'Tambah Unit';
    protected static ?string $title = 'Tambah Unit';
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
}
