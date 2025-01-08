<?php

namespace App\Filament\Resources;

use stdClass;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use App\Models\Kategori;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KategoriResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KategoriResource\RelationManagers;
use Filament\Notifications\Notification;

use function Livewire\before;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';
    protected static ?string $slug = 'kategori';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->hidden(),
                Forms\Components\Select::make('is_aktif')
                    ->required()
                    ->placeholder('Pilih Status Kategori')
                    ->default('Y')
                    ->label('Status Kategori')
                    ->options([
                        'Y' => 'Aktif',
                        'N' => 'Tidak Aktif'
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Data Tidak Ditemukan')
            ->emptyStateDescription('Kami Sudah Mencari Keseluruh Sumber Data, Namun Data Tidak Ditemukan')
            ->columns([
                Tables\Columns\TextColumn::make('index')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            )).'.'
                        );
                    }
                )
                    ->label('No.')
                    ->width('3%'),
                Tables\Columns\TextColumn::make('kategori_nama')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->width('90%'),
                Tables\Columns\TextColumn::make('is_aktif')
                    ->label('Status Kategori')
                    ->searchable()
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->is_aktif->value == 'Y' ? 'Aktif' : 'Tidak Aktif')
                    ->color(fn ($record) => $record->is_aktif->value == 'Y' ? 'success' : 'danger')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Sukses')
                            ->body('Data Kategori Berhasil Dihapus')
                    )
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
}
