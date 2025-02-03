<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Categories;
class Incomes extends Model
{
    use HasFactory;
    // Inisialisasi Tabel
    protected $table = 'incomes';
    protected $primaryKey = 'id_income'; // 🔥 Pastikan ini ada!
    protected $secondaryTable = 'balance';

    // Fill Tabel
    public $timestamps = false;
    protected $fillable = [
        'amount', 'description', 'date', 'id_category', 'created_at'
    ];

    // Get All Data
    public static function getAll()
    {
        return Incomes::all();
    }

    // Get Data by ID
    public static function getById($id)
    {
        return Incomes::where('id_income', $id)->first();
    }

    // Insert Data
    public static function insert($data)
    {
        // Ambil amount dari $data untuk dijadikan variabel baru
        // Tambahkan ke tabel balance
        $amount = $data['amount'];

        // Tambahkan description ke tabel balance
        $description = "Pemasukan";

        // Tambahkan data ke tabel balance
        $balance = Balance::create([
            'amount' => $amount,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Jika penambahan data ke tabel balance berhasil
        // lanjutkan untuk menambahkan data ke tabel Incomes
        if ($balance) {
            // Tambahkan data ke tabel Incomes
            $income = Incomes::create($data);
            return $income;
        }

        // Jika penambahan data ke tabel balance gagal, return false
        return false;
    }

    // Update Data
    public static function updateData($id_income, $data)
    {
        return Incomes::where('id_income', $id_income)->update($data);
    }

    // Delete Data
    public static function deleteData($id)
    {
        try {
            DB::beginTransaction();

            // Ambil data income berdasarkan id_income
            $income = Incomes::where('id_income', $id)->first();

            if (!$income) {
                Log::error("❌ Data income tidak ditemukan dengan id_income: " . $id);
                DB::rollBack();
                return false;
            }

            Log::info("✅ Data income ditemukan: ", $income->toArray());

            // Hapus balance terlebih dahulu
            $balanceDeleted = Balance::where('updated_at', $income->created_at)->delete();

            // Hapus income menggunakan id_income (karena default delete() pakai 'id')
            $incomeDeleted = Incomes::where('id_income', $id)->delete();

            Log::info("🗑️ Income deleted: " . ($incomeDeleted ? "Success" : "Failed"));
            Log::info("🗑️ Balance deleted: " . ($balanceDeleted ? "Success" : "Failed"));

            if ($incomeDeleted) {
                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("🔥 Error saat menghapus data: " . $e->getMessage());
            return false;
        }
    }

    // Total Incomes
    public static function totalIncomes()
    {
        $incomes = Incomes::getAll();
        $total_incomes = 0;
        foreach ($incomes as $income) {
            $total_incomes += $income->amount;
        }
        return $total_incomes;
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'id_category', 'id_category');
    }
}
