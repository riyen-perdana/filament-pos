<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Asset;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\View\TablesRenderHook;
use App\Filament\Resources\TransaksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiResource\RelationManagers;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Badan Layanan Umum';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $pluralModelLabel = 'Transaksi';
    protected static ?string $slug = 'transaksi';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Transaksi')
                            ->schema([
                                Forms\Components\TextInput::make('transaksi_kode')
                                    ->required()
                                    ->default(function () {
                                        return 'TR-' . date('Y') . '' . date('m') . '' . date('d') . '' . date('H') . '' . date('i') . '' . date('s');
                                    }),
                                Forms\Components\Select::make('konsumen_id')
                                    ->preload()
                                    ->placeholder('Pilih Konsumen')
                                    ->searchable()
                                    ->required()
                                    ->relationship('konsumen', 'konsumen_card')
                                    ->label('Identitas Konsumen')
                                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "$record->konsumen_card - $record->konsumen_nama")
                                    ->validationMessages([
                                        'required' => 'Kolom Identitas Konsumen Harus Diisi',
                                    ]),
                                Forms\Components\DatePicker::make('transaksi_tanggal')
                                    ->required()
                                    ->label('Tanggal Transaksi')
                                    ->default(now())
                                    ->readOnly(),
                                Forms\Components\TextInput::make('transaksi_total')
                                    ->required()
                                    ->numeric()
                                    ->label('Total Transaksi')
                                    ->default(0)
                                    // ->readOnly()
                            ])
                            ->columns(2),
                        Forms\Components\Section::make('Detail Transaksi')
                            ->schema([
                                Forms\Components\Repeater::make('transaksiDetail')
                                    ->hiddenLabel()
                                    ->relationship()
                                    ->defaultItems(0)
                                    ->addActionLabel('Tambah Transaksi')
                                    ->schema([
                                        Forms\Components\Select::make('asset_id')
                                            ->required()
                                            ->live()
                                            ->preload()
                                            ->placeholder('Pilih Asset')
                                            ->searchable()
                                            ->relationship('asset','asset_nama')
                                            ->label('Asset')
                                            ->getOptionLabelFromRecordUsing(fn(Model $record) => "$record->asset_nama")
                                            ->columnSpan(2)
                                            ->afterStateUpdated(
                                                function (Set $set, ?int $state) {
                                                    $harga = Asset::where('id', $state)->first();
                                                    // dd($harga->asset_harga);
                                                    return $set('harga', $harga->asset_harga);
                                                }
                                            ),
                                        Forms\Components\TextInput::make('jumlah')
                                            ->required()
                                            ->numeric()
                                            ->label('Jumlah')
                                            ->default(0)
                                            ->columnSpan(1)
                                            ->live()
                                            ->afterStateUpdated(
                                                // fn(Set $set, Get $get, ?int $state) => $set('Total', $state * $get('harga')); $set
                                                function (Set $set, Get $get, ?int $state) {
                                                    $set('Total', $state * $get('harga'));
                                                    $set ('transaksi_total', 100000);
                                                }
                                            ),
                                        Forms\Components\TextInput::make('harga')
                                            ->required()
                                            ->readOnly()
                                            ->columnSpan(1)
                                            ->prefix('Rp. '),
                                        Forms\Components\TextInput::make('Total')
                                            ->required()
                                            ->readOnly()
                                            ->columnSpan(1)
                                            ->default(0)
                                            ->prefix('Rp. '),
                                    ])
                                    ->columns(5)
                                    ->columnSpanFull()
                            ])
                            ->live(),
                    ])
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Data Tidak Ditemukan')
            ->emptyStateDescription('Kami Sudah Mencari Keseluruh Sumber Data, Namun Data Tidak Ditemukan')
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
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

    public static function updateTotal(Get $get, Set $set) : void
    {
        $transaksi = collect($get('transaksiDetail'))->filter(fn($item) => !empty($item['asset_id']) && !empty($item['jumlah']));
        $set('transaksi_total', $transaksi->sum('Total'));

    }
}
