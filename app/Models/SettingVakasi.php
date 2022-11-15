<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingVakasi extends Model
{
    use HasFactory;
    protected $table = "setting_vakasis";
    protected $fillable = [
        'prodi',
        'program',
        'semester',
        'honor_soal',
        'honor_pengawas',
        'bonus',
        'bonus_lewat',
    ];
}
