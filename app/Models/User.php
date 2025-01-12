<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\IsAktif;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip',
        'name',
        'email',
        'no_hp',
        'password',
        'glr_dpn',
        'glr_blkg',
        'is_aktif',
        'jabatan_id',
        'unit_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_aktif' => IsAktif::class
        ];
    }

    protected $appends = ['full_nm_user'];

    public function getFullNmUserAttribute()
    {
        if ($this->glr_dpn == NULL || strlen(trim($this->glr_dpn) == 0))
        {
            return "{$this->name}.{$this->glr_blkg}";
        } elseif ($this->glr_blkg == NULL || strlen(trim($this->glr_blkg) == 0))
        {
            return "{$this->glr_dpn}. {$this->name}";
        } elseif (($this->glr_dpn != NULL || strlen(trim($this->glr_dpn) != 0)) && ($this->glr_blkg != null || strlen(trim($this->glr_blkg) != 0)))
        {
            return "{$this->glr_dpn}. {$this->name}.{$this->glr_blkg}";
        } else
        {
            return "{$this->name}";
        }
    }

    public function jabatan() : BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

}
