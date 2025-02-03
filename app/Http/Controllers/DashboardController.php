<?php

namespace App\Http\Controllers;
use App\Models\Incomes;
use App\Models\Expenses;
use App\Models\Balance;
use App\Models\Categories;
use PDF;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Ambil total pemasukan dari model
        $total_incomes = Incomes::totalIncomes();

        // Ambil total balance dari model
        $total_balance = Balance::totalBalance();

        // Ambil total kategori dari model
        $total_categories = Categories::totalCategories();

        // Ambil total pengeluaran dari model
        $total_expenses = Expenses::totalExpenses();

        // Ambil semua data pemasukan dan pengeluaran dari model balance
        $incomesAndExpenses = Balance::getAllIncomesAndExpenses();
        // dd ($incomesAndExpenses);

        // Ambil semua data kategori dari model categories
        $categories = Categories::getAll();

        // Pemasukan dan pengeluaran hari ini
        $today_incomes = Incomes::whereDate('date', today())->sum('amount');
        $today_expenses = Expenses::whereDate('date', today())->sum('amount');

        // Pemasukan dan pengeluaran bulan ini
        $monthly_incomes = Incomes::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
        $monthly_expenses = Expenses::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        // Pemasukan dan pengeluaran tahun ini
        $yearly_incomes = Incomes::whereYear('date', now()->year)->sum('amount');
        $yearly_expenses = Expenses::whereYear('date', now()->year)->sum('amount');

        $categoryData = Categories::withCount(['incomes', 'expenses'])
        ->withSum('incomes', 'amount')
        ->withSum('expenses', 'amount')
        ->get();

        // Label kategori
        $categoryLabels = $categoryData->pluck('name_category');

        // Jumlah transaksi per kategori
        $categoryIncomes = $categoryData->pluck('incomes_count');
        $categoryExpenses = $categoryData->pluck('expenses_count');

        // Total uang yang dipakai di setiap kategori
        $totalIncomes = $categoryData->pluck('incomes_sum_amount'); // Total pemasukan per kategori
        $totalExpenses = $categoryData->pluck('expenses_sum_amount'); // Total pengeluaran per kategori

        // format $totalIncomes dan $totalExpenses ke dalam format rupiah
        $totalIncomes = $totalIncomes->map(fn($value) => 'Rp' . number_format($value, 0, ',', '.'));
        $totalExpenses = $totalExpenses->map(fn($value) => 'Rp' . number_format($value, 0, ',', '.'));
        $data = [
            'total_incomes' => $total_incomes,
            'total_balance' => $total_balance,
            'total_categories' => $total_categories,
            'total_expenses' => $total_expenses,
            'incomesAndExpenses' => $incomesAndExpenses,
            'categories' => $categories,
            'today_incomes' => $today_incomes,
            'today_expenses' => $today_expenses,
            'monthly_incomes' => $monthly_incomes,
            'monthly_expenses' => $monthly_expenses,
            'yearly_incomes' => $yearly_incomes,
            'yearly_expenses' => $yearly_expenses
        ];

        $chartData = [
            'labels' => ['Hari Ini', 'Bulan Ini', 'Tahun Ini', 'Total'],
            'incomes' => [$today_incomes, $monthly_incomes, $yearly_incomes, $total_incomes],
            'expenses' => [$today_expenses, $monthly_expenses, $yearly_expenses, $total_expenses],
            'categoryLabels' => $categoryLabels,
            'categoryIncomes' => $categoryIncomes,
            'categoryExpenses' => $categoryExpenses,
            'totalIncomes' => $totalIncomes,
            'totalExpenses' => $totalExpenses
        ];
        // dd($chartData);
        // Kirim data total pemasukan ke view
        return view('dashboard/index', $data, compact('chartData'));
    }

    public function generatePdf()
    {
        $incomesAndExpenses = Balance::getAllIncomesAndExpenses();
        $categories = Categories::all(); // Ganti sesuai query Anda

        $pdf = PDF::loadView('dashboard/pdf', compact('incomesAndExpenses', 'categories'));
        return $pdf->download('income-expense-report.pdf');
    }
}
