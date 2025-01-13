<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Konsumen;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\View\TablesRenderHook;
use App\Filament\Resources\KonsumenResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KonsumenResource\RelationManagers;

class KonsumenResource extends Resource
{
    protected static ?string $model = Konsumen::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Konsumen';
    protected static ?string $pluralModelLabel = 'Konsumen';
    protected static ?string $slug = 'konsumen';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getCustomKonsumenForm());
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
                Tables\Columns\TextColumn::make('konsumen_card')
                    ->label('Identitas Konsumen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('konsumen_nama')
                    ->label('Nama Konsumen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('konsumen_no_hp')
                    ->label('Nomor Handphone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('konsumen_email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('konsumen_alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
                            ->body('Data Konsumen Berhasil Dihapus')
                    )
                    ->after(
                        fn() =>
                        redirect(KonsumenResource::getUrl('index'))
                    )
                    ->modalHeading(fn(Konsumen $record) => 'Hapus Konsumen ' . $record->konsumen_nama . '')
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
            'index' => Pages\ListKonsumens::route('/'),
            'create' => Pages\CreateKonsumen::route('/create'),
            'edit' => Pages\EditKonsumen::route('/{record}/edit'),
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

    public static function getCustomKonsumenForm(): array
    {
        return [
            Forms\Components\TextInput::make('konsumen_card')
                ->autofocus()
                ->required()
                ->unique(ignoreRecord: true)
                ->label('Identitas Konsumen')
                ->regex('^\\d{16}$^')
                ->validationMessages([
                    'required' => 'Kolom Identitas Konsumen Harus Diisi',
                    'unique' => 'Kolom Identitas Konsumen Sudah Digunakan, Isikan Yang Lain',
                    'regex' => 'Kolom Identitas Konsumen Harus Berupa 16 Angka'
                ]),
            Forms\Components\TextInput::make('konsumen_nama')
                ->required()
                ->label('Nama Konsumen')
                ->validationMessages([
                    'required' => 'Kolom Nama Konsumen Harus Diisi',
                ]),
            Forms\Components\TextInput::make('konsumen_email')
                ->required()
                ->email()
                ->unique(ignoreRecord: true)
                ->label('Email Konsumen')
                ->validationMessages([
                    'required' => 'Kolom Email Konsumen Harus Diisi',
                    'email' => 'Kolom Email Konsumen Harus Berupa Email',
                    'unique' => 'Kolom Email Konsumen Sudah Digunakan, Isikan Yang Lain'
                ]),
            Forms\Components\TextInput::make('konsumen_no_hp')
                ->required()
                ->regex('^08[1-9][0-9]{6,9}$^')
                ->unique(ignoreRecord: true)
                ->label('No. Hp Konsumen')
                ->validationMessages([
                    'required' => 'Kolom No. Hp Konsumen Harus Diisi',
                    'regex' => 'Kolom No. Hp Konsumen Tidak Sesuai Format',
                    'unique' => 'Kolom No. Hp Konsumen Sudah Digunakan, Isikan Yang Lain'
                ]),
            Forms\Components\Textarea::make('konsumen_alamat')
                ->cols(4)
                ->autosize(true)
                ->required()
                ->label('Alamat Konsumen')
                ->validationMessages([
                    'required' => 'Kolom Alamat Konsumen Harus Diisi',
                ]),
        ];
    }
}
