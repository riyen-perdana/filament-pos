<?php

namespace App\Filament\Resources;

use Action\Action;
use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Asset;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Enums\JenisAsset;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Actions;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AssetResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AssetResource\RelationManagers;
use Filament\Forms\Components\Tabs\Tab;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Badan Layanan Umum';
    protected static ?string $navigationLabel = 'Tarif Layanan Asset';
    protected static ?string $pluralModelLabel = 'Tarif Layanan Asset';
    protected static ?string $slug = 'tarif-layanan-asset';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                self::getCustomAssetForm()
            );
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
                Tables\Columns\TextColumn::make('asset_kode')
                    ->label('Kode Layanan Asset')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('asset_nama')
                    ->label('Nama Layanan Asset')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('kategori.kategori_nama')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('unit.unit_nama')
                    ->label('Unit')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('asset_harga')
                    ->label('Tarif Layanan')
                    ->searchable()
                    ->sortable()
                    ->money('Rp.', true)
                    ->alignRight(true),
                Tables\Columns\TextColumn::make('asset_stok')
                    ->label('Stok')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => $record->asset_stok == 0 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('jenis_asset')
                    ->label('Jenis Asset')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('is_share')
                    ->label('Data Share')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color(fn($record) => $record->is_share ? 'success' : 'danger')
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->is_share->value === 'Y' ? 'Ya' : 'Tidak'),
                Tables\Columns\TextColumn::make('is_aktif')
                    ->label('Status Layanan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color(fn($record) => $record->is_aktif ? 'success' : 'danger')
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->is_aktif->value === 'Y' ? 'Aktif' : 'Tidak Aktif'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('unit.unit_nama', 'asc');
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }

    public static function getCustomAssetForm(): array
    {
        return [
            Forms\Components\TextInput::make('asset_kode')
                ->readOnly()
                ->required()
                ->label('Kode Layanan Asset')
                ->default(Str::uuid()->toString())
                ->extraAttributes(['readonly' => 'readonly']),
            Forms\Components\TextInput::make('asset_nama')
                ->autofocus()
                ->label('Nama Layanan Asset')
                ->required(),
            Forms\Components\Textarea::make('asset_deskripsi')
                ->columnSpan(2)
                ->label('Deskripsi Layanan Asset')
                ->cols(4)
                ->autosize(true),
            Forms\Components\Select::make('kategori_id')
                ->required()
                ->placeholder('Pilih Jenis Kategori')
                ->label('Jenis Kategori')
                ->relationship('kategori', 'kategori_nama', modifyQueryUsing: fn(Builder $query) => $query->where('is_aktif', 'Y'))
                ->getOptionLabelFromRecordUsing(fn($record) => $record->kategori_nama)
                ->searchable()
                ->preload()
                ->createOptionForm(
                    KategoriResource::getCustomKategoriForm()
                )
                ->createOptionAction(
                    fn(Actions\Action $action) =>
                    $action
                        ->modalHeading('Tambah Kategori')
                        ->modalFooterActionsAlignment('end')
                        ->modalSubmitAction(fn(StaticAction $action) => $action->label('Tambah')->icon('heroicon-o-plus')->color('success'))
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')->icon('heroicon-o-x-mark')->color('danger'))
                        ->closeModalByClickingAway(false)
                        ->modalAutofocus(false)
                ),
            Forms\Components\Select::make('unit_id')
                ->required()
                ->placeholder('Pilih Unit')
                ->label('Unit')
                ->relationship('unit', 'unit_nama', modifyQueryUsing: fn(Builder $query) => $query->where('is_aktif', 'Y'))
                ->getOptionLabelFromRecordUsing(fn($record) => $record->unit_nama)
                ->searchable()
                ->preload()
                ->createOptionForm(
                    UnitResource::getCustomUnitForm()
                )
                ->createOptionAction(
                    fn(Actions\Action $action) =>
                    $action
                        ->modalHeading('Tambah Unit')
                        ->modalFooterActionsAlignment('end')
                        ->modalSubmitAction(fn(StaticAction $action) => $action->label('Tambah')->icon('heroicon-o-plus')->color('success'))
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')->icon('heroicon-o-x-mark')->color('danger'))
                        ->closeModalByClickingAway(false)
                        ->modalAutofocus(false)
                ),
            Forms\Components\TextInput::make('asset_harga')
                ->required()
                ->numeric()
                ->label('Tarif Layanan Asset')
                ->validationMessages([
                    'numeric' => 'Harga Asset Harus Angka',
                    'required' => 'Kolom Harga Asset Harus Diisi'
                ])
                ->prefix('Rp. ')
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->default(0),
            Forms\Components\Select::make('jenis_asset')
                ->required()
                ->placeholder('Pilih Jenis Asset')
                ->options([
                    JenisAsset::Barang->value => JenisAsset::Barang->value,
                    JenisAsset::Jasa->value => JenisAsset::Jasa->value
                ])
                ->label('Jenis Asset')
                ->validationMessages([
                    'required' => 'Kolom Jenis Asset Harus Diisi'
                ])
                ->live()
                ->afterStateUpdated(
                    fn (Set $set, Get $get) => $set('asset_stok', $get('jenis_asset') === JenisAsset::Barang->value ? 0 : 0)
                ),
            Forms\Components\TextInput::make('asset_stok')
                ->required()
                ->readOnly(fn (Get $get) => $get('jenis_asset') === JenisAsset::Jasa->value)
                ->numeric()
                ->label('Stok')
                ->validationMessages([
                    'numeric' => 'Stok Asset Harus Angka',
                    'required' => 'Kolom Stok Asset Harus Diisi'
                ])
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->default(0),
            Forms\Components\Select::make('is_share')
                ->required()
                ->placeholder('Pilih Opsi Apakah Data Asset Dapat Dibagikan ?')
                ->label('Apakah Data Asset Dapat Dibagikan ?')
                ->options([
                    'Y' => 'Ya',
                    'N' => 'Tidak'
                ])
                ->validationMessages([
                    'required' => 'Kolom Apakah Data Asset Dapat Dibagikan ? Harus Diisi'
                ]),
            Forms\Components\Select::make('is_aktif')
                ->required()
                ->placeholder('Pilih Status Tarif Layanan Asset')
                ->label('Status Tarif Layanan Asset')
                ->options([
                    'Y' => 'Aktif',
                    'N' => 'Tidak Aktif'
                ])
                ->validationMessages([
                    'required' => 'Kolom Status Tarif Layanan Asset Harus Diisi'
                ]),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 0 ? 'warning' : 'danger';
    }   
}
