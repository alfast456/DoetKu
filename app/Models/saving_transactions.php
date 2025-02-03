<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\savings;

class saving_transactions extends Model
{
    use HasFactory;

    protected $table = 'saving_transactions';
    protected $secondaryTable = 'savings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'saving_id', 'transaction_date', 'amount', 'type', 'description'
    ];

    public static function insert($data)
    {
        return self::create($data);
    }

    public static function updateData($id, $data)
    {
        return saving_transactions::where('id', $id)->update($data);
    }

    public static function deleteData($id)
    {
        return saving_transactions::where('id', $id)->delete();
    }

    public static function detail($id)
    {
        return saving_transactions::where('id', $id)->first();
    }

    public function savings()
    {
        return $this->belongsTo(savings::class, 'saving_id', 'id');
    }

    



}
