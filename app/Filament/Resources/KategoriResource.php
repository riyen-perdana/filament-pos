<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\IsAktif;
use Filament\Forms\Set;
use App\Models\Kategori;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\KategoriResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KategoriResource\RelationManagers;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationGroup = 'Badan Layanan Umum';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';
    protected static ?string $slug = 'kategori';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getCustomKategoriForm());
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
                Tables\Columns\TextColumn::make('kategori_nama')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->width('90%'),
                Tables\Columns\TextColumn::make('asset_count')
                    ->label('Layanan Asset')
                    ->sortable()
                    ->counts('asset')
                    ->badge()
                    ->color('gray'),
                    // ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('is_aktif')
                    ->label('Status Kategori')
                    ->searchable()
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->is_aktif->value == 'Y' ? 'Aktif' : 'Tidak Aktif')
                    ->color(fn($record) => $record->is_aktif->value == 'Y' ? 'success' : 'danger')
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Sukses')
                            ->body('Data Kategori Berhasil Dihapus')
                    )
                    ->after(
                        fn() =>
                        redirect(KategoriResource::getUrl('index'))
                    )
                    ->modalHeading(fn(Kategori $record) => 'Hapus Kategori ' . $record->kategori_nama . '')
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
            'index' => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
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

    public static function getCustomKategoriForm(): array
    {
        return [
            Forms\Components\TextInput::make('kategori_nama')
                ->autofocus()
                ->required()
                ->unique(ignoreRecord: true)
                ->label('Nama Kategori')
                ->live(onBlur: true)
                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('kategori_slug', Str::slug($state)))
                ->validationMessages([
                    'required' => 'Kolom Nama Kategori Harus Diisi',
                    'unique' => 'Kolom Nama Kategori Sudah Digunakan, Isikan Yang Lain'
                ]),
            Forms\Components\TextInput::make('kategori_slug')
                ->readOnly(true),
            Forms\Components\Select::make('is_aktif')
                ->required()
                ->placeholder('Pilih Status Kategori')
                ->label('Status Kategori')
                ->options([
                    'Y' => 'Aktif',
                    'N' => 'Tidak Aktif'
                ])
                ->validationMessages([
                    'required' => 'Kolom Status Kategori Harus Diisi',
                ])
        ];
    }
}
