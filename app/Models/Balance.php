<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use App\Models\Incomes;
use App\Models\Expense;
class Balance extends Model
{
    use HasFactory;
    // Inisilisasi Table
    protected $table = 'balance';
    public $timestamps = false;

    // Table Pemasukan dan Pengeluaran
    protected $incomesTable = 'incomes';
    protected $expensesTable = 'expenses'; 

    // Fill Table
    protected $fillable = [
        'amount', 'description', 'updated_at'
    ];

    // Get All Data
    public static function getAll()
    {
        return Balance::all();
    }

    // Get Data by ID
    public static function getById($id)
    {
        return Balance::where('id_balance', $id)->first();
    }

    // Total Balance
    public static function totalBalance()
    {
        // Ambil semua data balance
        $balances = Balance::all();

        // Inisialisasi variabel total
        $totalBalance = 0;

        // Looping data balance
        foreach ($balances as $balance) {
            // Tambahkan amount ke variabel total
            $totalBalance += $balance->amount;
        }
        // Return total balance
        return $totalBalance;
    }

    // Mengambil semua data pemasukan dan pengeluaran
    public static function getAllIncomesAndExpenses()
    {
        // Ambil semua data pemasukan dengan tambahan kolom 'type' sebagai 'income'
        $incomes = Incomes::select('amount', 'description', 'date', 'id_category')
        ->with('category')
        ->addSelect(\DB::raw("'income' as type"));

        // Ambil semua data pengeluaran dengan tambahan kolom 'type' sebagai 'expense'
        $expenses = Expenses::select('amount', 'description', 'date', 'id_category')
        ->with('category')
        ->addSelect(\DB::raw("'expense' as type"));

        // Gabungkan query pemasukan dan pengeluaran menggunakan UNION
        $transactions = $incomes->union($expenses)->orderByDesc('date')->get();

        return $transactions;
    }
}
