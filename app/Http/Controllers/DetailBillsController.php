<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailBills;
class DetailBillsController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah_cicilan' => 'required',
            'status_cicilan' => 'required',
            'keterangan' => 'nullable',
        ]);

        $detail = DetailBills::findOrFail($id);
        $detail->jumlah_cicilan = $request->jumlah_cicilan;
        $detail->status_cicilan = $request->status_cicilan;
        $detail->keterangan = $request->keterangan;

        $detail->save();

        return redirect()->route('bills.editPage', $detail->bills->id);
    }

    public function delete($id)
    {
        $detail = DetailBills::findOrFail($id);
        $detail->delete();

        return redirect()->route('bills.editPage', $detail->bills->id);
    }
}
