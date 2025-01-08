<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Jabatan;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\JabatanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JabatanResource\RelationManagers;

class JabatanResource extends Resource
{
    protected static ?string $model = Jabatan::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Jabatan';
    protected static ?string $pluralModelLabel = 'Jabatan';
    protected static ?string $slug = 'jabatan';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('jabatan_nama')
                ->autofocus()
                ->required()
                ->unique(ignoreRecord: true)
                ->label('Nama Jabatan')
                ->live(onBlur: true)
                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('jabatan_slug', Str::slug($state)))
                ->validationMessages([
                    'required' => 'Kolom Nama Jabatan Harus Diisi',
                    'unique' => 'Kolom Nama Jabatan Sudah Digunakan, Isikan Yang Lain'
                ]),
            Forms\Components\TextInput::make('jabatan_slug')
                ->readOnly(true),
            Forms\Components\Select::make('is_aktif')
                ->required()
                ->placeholder('Pilih Status Jabatan')
                ->label('Status Jabatan')
                ->options([
                    'Y' => 'Aktif',
                    'N' => 'Tidak Aktif'
                ])
                ->validationMessages([
                    'required' => 'Kolom Status Jabatan Harus Diisi',
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
                Tables\Columns\TextColumn::make('index')
                    ->rowIndex()
                    ->label('No.')
                    ->width('3%'),
                Tables\Columns\TextColumn::make('jabatan_nama')
                    ->label('Nama Jabatan')
                    ->searchable()
                    ->sortable()
                    ->width('90%'),
                Tables\Columns\TextColumn::make('is_aktif')
                    ->label('Status Kategori')
                    ->searchable()
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->is_aktif->value == 'Y' ? 'Aktif' : 'Tidak Aktif')
                    ->color(fn($record) => $record->is_aktif->value == 'Y' ? 'success' : 'danger')
            ])
            ->filters([
                Filter::make('filter')
                    ->form([
                        Select::make('is_aktif')
                            ->options([
                                'Y' => 'Aktif',
                                'N' => 'Tidak Aktif'
                            ])
                            ->label('Status Jabatan')
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['is_aktif'],
                                fn(Builder $query) => $query->where('is_aktif', $data['is_aktif'])
                        );
                    })
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
                            ->body('Data Jabatan Berhasil Dihapus')
                    )
                    ->after(
                        fn() =>
                        redirect(JabatanResource::getUrl('index'))
                    )
                    ->modalHeading(fn(Jabatan $record) => 'Hapus Jabatan ' . $record->jabatan_nama . '')
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
            'index' => Pages\ListJabatans::route('/'),
            'create' => Pages\CreateJabatan::route('/create'),
            'edit' => Pages\EditJabatan::route('/{record}/edit'),
        ];
    }
}
