<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Actions;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UnitResource\Pages\CreateUnit;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Pengguna dan Otorisasi';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna';
    protected static ?string $slug = 'pengguna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nip')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->minLength(18)
                    ->label('NIP ASN')
                    ->validationMessages([
                        'required' => 'Kolom NIP ASN Harus Diisi',
                        'unique' => 'Kolom NIP ASN Sudah Digunakan, Isikan Yang Lain',
                        'minLength' => 'Kolom NIP ASN Harus 18 Karakter',
                    ]),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama ASN')
                    ->validationMessages([
                        'required' => 'Kolom Nama ASN Harus Diisi',
                        'maxLength' => 'Kolom Nama ASN Maksimal 255 Karakter',
                    ]),
                Forms\Components\TextInput::make('glr_dpn')
                    ->maxLength(255)
                    ->label('Gelar Depan')
                    ->validationMessages([
                        'maxLength' => 'Kolom Gelar Depan ASN Maksimal 255 Karakter',
                    ]),
                Forms\Components\TextInput::make('glr_blkg')
                    ->maxLength(255)
                    ->label('Gelar Belakang')
                    ->validationMessages([
                        'maxLength' => 'Kolom Gelar Belakang ASN Maksimal 255 Karakter',
                    ]),
                Forms\Components\TextInput::make('password')
                    ->required()
                    ->password()
                    ->label('Kata Kunci')
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Kolom Kata Kunci Harus Diisi',
                        'maxLength' => 'Kolom Kata Kunci Maksimal 255 Karakter',
                    ])
                    ->hiddenOn('edit'),
                Forms\Components\TextInput::make('password_confirmation')
                    ->required()
                    ->password()
                    ->label('Konfirmasi Kata Kunci')
                    ->maxLength(255)
                    ->same('password')
                    ->dehydrated(false)
                    ->validationMessages([
                        'required' => 'Kolom Kata Kunci Harus Diisi',
                        'maxLength' => 'Kolom Kata Kunci Maksimal 255 Karakter',
                        'same' => 'Kolom Konfirmasi Kata Kunci Harus Sama Dengan Kata Kunci'
                    ])
                    ->hiddenOn('edit'),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->label('Email ASN')
                    ->validationMessages([
                        'unique' => 'Kolom Email ASN Sudah Digunakan, Isikan Yang Lain',
                        'required' => 'Kolom Email ASN Harus Diisi',
                        'email' => 'Kolom Email ASN Harus Valid'
                    ]),
                Forms\Components\TextInput::make('no_hp')
                    ->required()
                    ->label('No. Handphone')
                    ->validationMessages([
                        'required' => 'Kolom No. Handphone Harus Diisi'
                    ]),
                Forms\Components\Select::make('jabatan_id')
                    ->required()
                    ->label('Jabatan')
                    ->relationship('Jabatan', 'jabatan_nama', modifyQueryUsing: fn (Builder $query) => $query->where('is_aktif', 'Y'))
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "$record->jabatan_nama")
                    ->searchable()
                    ->preload()
                    ->createOptionForm(
                        JabatanResource::getCustomJabatanForm()
                    )
                    ->createOptionAction(fn(Actions\Action $action) => 
                        $action
                            ->modalHeading('Tambah Jabatan')
                            ->modalFooterActionsAlignment('end')
                            ->modalSubmitAction(fn (StaticAction $action) => $action->label('Tambah')->icon('heroicon-o-plus')->color('success'))
                            ->modalCancelAction(fn (StaticAction $action) => $action->label('Batal')->icon('heroicon-o-x-mark')->color('danger'))
                            ->closeModalByClickingAway(false)
                            ->modalAutofocus(false)
                    )
                    ->placeholder('Pilih Jabatan Terkait'),
                Forms\Components\Select::make('unit_id')
                    ->required()
                    ->label('Unit')
                    ->relationship('Unit', 'unit_nama')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "$record->unit_nama")
                    ->searchable()
                    ->preload()
                    ->placeholder('Pilih Unit Terkait')
                    ->createOptionForm(
                        UnitResource::getCustomUnitForm()
                    )
                    ->createOptionAction(fn(Actions\Action $action) => 
                        $action
                            ->modalHeading('Tambah Unit')
                            ->modalFooterActionsAlignment('end')
                            ->modalSubmitAction(fn (StaticAction $action) => $action->label('Tambah')->icon('heroicon-o-plus')->color('success'))
                            ->modalCancelAction(fn (StaticAction $action) => $action->label('Batal')->icon('heroicon-o-x-mark')->color('danger'))
                            ->closeModalByClickingAway(false)
                            ->modalAutofocus(false)
                    ),
                Forms\Components\Select::make('is_aktif')
                    ->required()
                    ->label('Status')
                    ->placeholder('Pilih Status')
                    ->options([
                        'Y' => 'Aktif',
                        'N' => 'Tidak Aktif'
                    ])
                    ->validationMessages([
                        'required' => 'Kolom Status Harus Diisi',
                    ]),
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
                Tables\Columns\TextColumn::make('nip') 
                    ->label('NIP ASN/Email')
                    ->searchable()
                    ->sortable()
                    ->description(
                        fn (User $record): string => $record->email
                    ),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama ASN')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('glr_dpn')
                    ->label('Gelar Depan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('glr_blkg')
                    ->label('Gelar Belakang')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. Handphone')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Jabatan.jabatan_nama')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Unit.unit_nama')
                    ->label('Unit')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => Carbon::parse($state)->format('d F Y H:i:s'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal Diubah')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => Carbon::parse($state)->format('d F Y H:i:s'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('is_aktif')
                    ->label('Status')
                    ->searchable()
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->is_aktif->value == 'Y' ? 'Aktif' : 'Tidak Aktif')
                    ->color(fn($record) => $record->is_aktif->value == 'Y' ? 'success' : 'danger'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
