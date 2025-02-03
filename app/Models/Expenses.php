<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class Expenses extends Model
{
    use HasFactory;
    // Inisialisasi Tabel
    protected $table = 'expenses';
    protected $primaryKey = 'id_expense'; // ✅ Pastikan Laravel mengenali primary key
    protected $secondaryTable = 'balance';
    public $timestamps = false;

    // Fill Tabel
    protected $fillable = [
        'amount', 'description', 'date', 'id_category', 'created_at'
    ];

    // Get All Data
    public static function getAll()
    {
        return Expenses::all();
    }

    // Get Data by ID
    public static function getById($id)
    {
        return Expenses::where('id_expense', $id)->first();
    }

    // Insert Data
    public static function insert($data)
    {
        // Ambil amount dari $data
        $amount = $data['amount'];

        // tambahkan description ke tabel balance
        $description = "Pengeluaran";

        // tambahkan data ke tabel balance
        $balance = Balance::create([
            // Kurangi jumlah balance dengan amount dari $data
            'amount' => -1 * $amount,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Jika penambahan data ke tabel balance berhasil
        if ($balance) {
            // Tambahkan data ke tabel Incomes
            $expense = Expenses::create($data);
            return $expense;
        }

        // Jika penambahan data ke tabel balance gagal, return false
        return false;
    }

    // Total Pengeluaran
    public static function totalExpenses()
    {
        // Ambil semua data dari model
        $expenses = Expenses::all();

        // Set variabel total_expenses
        $total_expenses = 0;

        // Looping data
        foreach ($expenses as $expense) {
            // Tambahkan amount ke variabel total_expenses
            $total_expenses += $expense->amount;
        }

        // Return total_expenses
        return $total_expenses;
    }

    // delete
    public static function deleteData($id)
    {
        try {
            DB::beginTransaction();

            // Ambil data expense berdasarkan id_expense
            $expense = Expenses::where('id_expense', $id)->first();

            if (!$expense) {
                Log::error("❌ Data expense tidak ditemukan dengan id_expense: " . $id);
                DB::rollBack();
                return false;
            }

            // Cek apakah created_at tersedia
            if (!$expense->created_at) {
                Log::error("❌ created_at untuk expense ID " . $id . " kosong!");
                DB::rollBack();
                return false;
            }

            // Hapus balance terlebih dahulu jika ada
            $balanceDeleted = Balance::where('updated_at', $expense->created_at)->delete();

            // Hapus expense setelah balance dihapus
            $expenseDeleted = Expenses::where('id_expense', $id)->delete();

            Log::info("🗑️ Expense deleted: " . ($expenseDeleted ? "Success" : "Failed"));
            Log::info("🗑️ Balance deleted: " . ($balanceDeleted ? "Success" : "Failed"));

            if ($expenseDeleted) {
                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            Log::error("🔥 Error saat menghapus data: " . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'id_category', 'id_category');
    }
}
