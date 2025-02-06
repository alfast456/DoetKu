<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;
use App\Models\DetailBills;

class Bills extends Model
{
    use HasFactory;

    protected $table = 'hutang';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_category',
        'total_hutang',
        'tanggal_hutang',
        'tanggal_selesai',
        'status_hutang',
        'keterangan',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'id_category');
    }

    public function detail()
    {
        return $this->hasMany(DetailBills::class, 'hutang_id');
    }

}
