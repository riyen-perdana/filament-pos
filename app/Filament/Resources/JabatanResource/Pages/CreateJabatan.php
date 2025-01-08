<?php

namespace App\Filament\Resources\JabatanResource\Pages;

use App\Filament\Resources\JabatanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJabatan extends CreateRecord
{
    protected static string $resource = JabatanResource::class;
    protected static ?string $breadcrumb = 'Tambah Jabatan';
    protected static ?string $title = 'Tambah Jabatan';
    protected static bool $canCreateAnother = false;
}
