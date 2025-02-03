<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class savings extends Model
{
    use HasFactory;

    protected $table = 'savings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'goal_name', 'target_amount', 'current_amount', 'deadline', 'image'
    ];

    public static function getAll()
    {
        return savings::all();
    }

    public static function insert($data)
    {
        return savings::create($data);
    }

    public static function updateData($id, $data)
    {
        return savings::where('id', $id)->update($data);
    }

    public static function deleteData($id)
    {
        return savings::where('id', $id)->delete();
    }

    public static function detail($id)
    {
        return savings::where('id', $id)->first();
    }

    public function transactions()
    {
        return $this->hasMany(saving_transactions::class, 'saving_id', 'id');
    }
}
