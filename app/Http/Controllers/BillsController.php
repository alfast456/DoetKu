<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bills;
use App\Models\Categories;
use App\Models\DetailBills;
class BillsController extends Controller
{
    public function index()
    {
        $tagihan = Bills::all();
        $categories = Categories::all();
        return view('dashboard.bills.list', compact('tagihan', 'categories'));
    }

    public function addPage()
    {
        $categories = Categories::all();

        return view('dashboard.bills.add', compact('categories'));
    }

    public function insert(Request $request)
    {
        // dd ($request->all());
        $request->validate([
            'jenis_tagihan' => 'required',
            'jumlah' => 'required|numeric',
            'jml_cicilan' => 'required|numeric',
            'tanggal_hutang' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'status' => 'required',
            'keterangan' => 'nullable',
        ]);

        $tagihan = new Bills();
        $category = Categories::findOrFail($request->jenis_tagihan);
        $tagihan->id_category = $category->id_category;
        $tagihan->total_hutang = $request->jumlah;
        $tagihan->tanggal_hutang = $request->tanggal_hutang;
        $tagihan->tanggal_selesai = $request->tanggal_selesai;
        $tagihan->status_hutang = $request->status;
        $tagihan->keterangan = $request->keterangan;

        $tagihan->save();

        $detail = new DetailBills();
        $idBills = Bills::latest()->first();
        $hutang_id = $idBills->id;
        $tgl_hutang = $request->tanggal_hutang;
        $tgl_selesai = $request->tanggal_selesai;
        $cicilan = $request->cicilan;
        $jml_cicilan = $request->jml_cicilan;
        $jml_hutang = $request->jumlah;
        // tanggal_cicilan = tgl_hutang + 1 bulan
        $tanggal_cicilan = date('Y-m-d', strtotime($tgl_hutang . ' +1 month'));
        // jumlah_cicilan = jml_hutang / jml_cicilan

        $detaDetail = [];
        for ($i = 1; $i <= $jml_cicilan; $i++) {
            $detaDetail[] = [
                'hutang_id' => $hutang_id,
                'tanggal_cicilan' => $tanggal_cicilan,
                'jumlah_cicilan' => $cicilan,
                'status_cicilan' => 'belum bayar',
            ];
            $tanggal_cicilan = date('Y-m-d', strtotime($tanggal_cicilan . ' +1 month'));
        }
        // dd ($tagihan, $detail, $detaDetail);
        if ($tagihan->save()) {
            $tagihan->detail()->createMany($detaDetail);
            return redirect()->route('bills')->with('success', 'Tagihan berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Tagihan gagal ditambahkan');
        }
    }

    public function editPage($id)
    {
        $tagihan = Bills::findOrFail($id);
        $categories = Categories::all();
        $detail = DetailBills::where('hutang_id', $id)->get();
        // dd ($detail);
        return view('dashboard.bills.detail', compact('tagihan', 'categories', 'detail'));
    }

    public function edit($id)
    {
        $tagihan = Bills::findOrFail($id);
        return view('dashboard.bills.edit', compact('tagihan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tagihan' => 'required',
            'jumlah' => 'required|numeric',
            'tanggal_jatuh_tempo' => 'required|date',
            'status' => 'required',
        ]);

        $tagihan = Bills::findOrFail($id);
        $tagihan->update($request->all());

        return redirect()->route('dashboard.bills.index')->with('success', 'Tagihan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tagihan = Bills::findOrFail($id);
        $tagihan->delete();

        return redirect()->route('dashboard.bills.index')->with('success', 'Tagihan berhasil dihapus');
    }
}
