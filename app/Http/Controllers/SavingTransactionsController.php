<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\saving_transactions;
use App\Models\savings;

class SavingTransactionsController extends Controller
{
    public function insert(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_saving' => 'required|exists:savings,id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:deposit,withdrawal',
            'description' => 'nullable|string|max:255',
        ]);
        // dd ($request->all());
        // Ambil data tabungan
        $saving = savings::findOrFail($request->id_saving);

        // Update jumlah saat ini berdasarkan jenis transaksi
        if ($request->type === 'deposit') {
            $saving->current_amount += $request->amount;
        } elseif ($request->type === 'withdrawal' && $saving->current_amount >= $request->amount) {
            $saving->current_amount -= $request->amount;
        } else {
            return redirect()->route('savings.detail', ['id' => $request->id_saving])
                ->with('error', 'Saldo tidak mencukupi untuk penarikan');
        }
        // dd ($saving);
        $saving->save();

        // Simpan transaksi
        $savingTransaction = saving_transactions::create([
            'saving_id' => $request->id_saving,
            'transaction_date' => $request->transaction_date,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        if ($savingTransaction) {
            return redirect()->route('savings.detail', ['id' => $request->id_saving])
                ->with('success', 'Data Berhasil Ditambahkan');
        } else {
            return redirect()->route('savings.detail', ['id' => $request->id_saving])
                ->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:deposit,withdrawal',
            'description' => 'nullable|string|max:255',
        ]);

        $savingTransaction = saving_transactions::findOrFail($id);
        $saving = savings::findOrFail($savingTransaction->saving_id);

        // Update jumlah saat ini berdasarkan jenis transaksi
        if ($savingTransaction->type === 'deposit') {
            $saving->current_amount -= $savingTransaction->amount;
        } elseif ($savingTransaction->type === 'withdrawal') {
            $saving->current_amount += $savingTransaction->amount;
        }

        $saving->save();

        $savingTransaction->transaction_date = $request->transaction_date;
        $savingTransaction->amount = $request->amount;
        $savingTransaction->type = $request->type;
        $savingTransaction->description = $request->description;
        $savingTransaction->save();

        return redirect()->route('savings.detail', ['id' => $savingTransaction->saving_id])
            ->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id)
    {
        $savingTransaction = saving_transactions::findOrFail($id);
        $saving = savings::findOrFail($savingTransaction->saving_id);
        
        // Update jumlah saat ini berdasarkan jenis transaksi
        if ($savingTransaction->type === 'deposit') {
            $saving->current_amount -= $savingTransaction->amount;
        } elseif ($savingTransaction->type === 'withdrawal') {
            $saving->current_amount += $savingTransaction->amount;
        }

        $saving->save();

        $savingTransaction->delete();

        return redirect()->route('savings.detail', ['id' => $savingTransaction->saving_id])
            ->with('success', 'Data Berhasil Dihapus');
    }
}
