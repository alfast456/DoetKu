<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBills extends Model
{
    use HasFactory;

    protected $table = 'hutang_detail';
    protected $primaryKey = 'id';
    protected $fillable = [
        'hutang_id',
        'tanggal_cicilan',
        'jumlah_cicilan',
        'status_cicilan',
        'keterangan',
    ];

    public function bills()
    {
        return $this->belongsTo(Bills::class, 'hutang_id');
    }

    public static function createMany($data)
    {
        return DetailBills::insert($data);
    }
}
