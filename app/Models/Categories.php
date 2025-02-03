<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Incomes;
use App\Models\Expenses;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id_category';
    protected $fillable = ['name_category'];

    // Get All Data
    public static function getAll()
    {
        return Categories::all();
    }

    // Get Data by ID
    public static function getById($id)
    {
        return Categories::where('id_category', $id)->first();
    }

    // Insert Data
    public static function insert($data)
    {
        return Categories::create($data);
    }

    // Update Data
    public static function updateData($id_category, $data)
    {
        return Categories::where('id_category', $id_category)->update($data);
    }

    // Delete Data
    public static function deleteData($id)
    {
        return Categories::where('id_category', $id)->delete();
    }

    // Get Total Data
    public static function totalCategories()
    {
        return Categories::count();
    }

    public function incomes()
    {
        return $this->hasMany(Incomes::class, 'id_category', 'id_category');
    }

    public function expenses()
    {
        return $this->hasMany(Expenses::class, 'id_category', 'id_category');
    }
}
