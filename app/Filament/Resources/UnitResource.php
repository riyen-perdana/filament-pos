<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UnitResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UnitResource\RelationManagers;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Unit';
    protected static ?string $pluralModelLabel = 'Unit';
    protected static ?string $slug = 'unit';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getCustomUnitForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Data Tidak Ditemukan')
            ->emptyStateDescription('Kami Sudah Mencari Keseluruh Sumber Data, Namun Data Tidak Ditemukan')
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->rowIndex()
                    ->label('No.')
                    ->width('3%'),
                Tables\Columns\TextColumn::make('unit_nama')
                    ->label('Nama Unit')
                    ->searchable()
                    ->sortable()
                    ->width('90%'),
                Tables\Columns\TextColumn::make('asset_count')
                    ->label('Layanan Asset')
                    ->sortable()
                    ->counts('asset')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('is_aktif')
                    ->label('Status Unit')
                    ->searchable()
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->is_aktif->value == 'Y' ? 'Aktif' : 'Tidak Aktif')
                    ->color(fn($record) => $record->is_aktif->value == 'Y' ? 'success' : 'danger')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Sukses')
                            ->body('Data Jabatan Berhasil Dihapus')
                    )
                    ->after(
                        fn() =>
                        redirect(UnitResource::getUrl('index'))
                    )
                    ->modalHeading(fn(Unit $record) => 'Hapus Unit ' . $record->unit_nama . '')
                    ->modalDescription('Apakah Anda Yakin Menghapus Data Ini?')
                    ->modalCancelActionLabel('Tidak')
                    ->modalSubmitActionLabel('Ya, Hapus Data')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 0 ? 'success' : 'danger';
    }

    public static function getCustomUnitForm()
    {
        return [
            Forms\Components\TextInput::make('unit_nama')
                ->autofocus()
                ->required()
                ->unique(ignoreRecord: true)
                ->label('Nama Unit')
                ->live(onBlur: true)
                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('unit_slug', Str::slug($state)))
                ->validationMessages([
                    'required' => 'Kolom Nama Unit Harus Diisi',
                    'unique' => 'Kolom Nama Unit Sudah Digunakan, Isikan Yang Lain'
                ]),
            Forms\Components\TextInput::make('unit_slug')
                ->readOnly(true)
                ->unique(ignoreRecord: true)
                ->label('Slug')
                ->validationMessages([
                    'unique' => 'Kolom Nama Unit Sudah Digunakan, Isikan Yang Lain'
                ]),
            Forms\Components\Select::make('is_aktif')
                ->required()
                ->label('Status Unit')
                ->placeholder('Pilih Status Unit')
                ->options([
                    'Y' => 'Aktif',
                    'N' => 'Tidak Aktif'
                ])
                ->validationMessages([
                    'required' => 'Kolom Status Unit Harus Diisi'
                ])
        ];
    }
}
