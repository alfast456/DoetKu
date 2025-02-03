<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\savings;
use Illuminate\Support\Facades\Auth;

class SavingsController extends Controller
{

    public function index()
    {
        $savings = savings::getAll();
        return view('dashboard.savings.list', compact('savings'));
    }

    public function addPage()
    {
        return view('dashboard.savings.add');
    }

    public function insert(Request $request)
    {
        
        // Bersihkan format angka sebelum menyimpan
        $targetAmount = str_replace(['.', ','], ['', '.'], $request->target_amount);
        
        // dd ($request->all() , $targetAmount);

        $image = $request->file('image');
        $goalNameParsed = str_replace(' ', '_', $request->goal_name);
        $imageName = $goalNameParsed . '.' . $image->extension();
        $imagePath = $image->storeAs('images', $imageName, 'public');
        // dd ($imagePath);
        $savings = savings::insert([
            'user_id' => Auth::user()->id,
            'goal_name' => $request->goal_name,
            'target_amount' => $targetAmount,
            'current_amount' => 0,
            'deadline' => $request->deadline,
            'image' => $imagePath,
            'created_at' => now(),
        ]);

        if ($savings) {
            return redirect()->route('savings')->with('success', 'Data Berhasil Ditambahkan');
        } else {
            return redirect()->route('savings')->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'goal_name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        // dd ($request->all());

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $goalNameParsed = str_replace(' ', '_', $request->goal_name);
            $imageName = $goalNameParsed . '.' . $image->extension();
            $imagePath = $image->storeAs('images', $imageName, 'public');
            unlink('storage/' . $request->old_image);
        } else {
            $imagePath = $request->old_image;
        }
        // dd ($imagePath);
        $savings = savings::updateData($id, [
            'goal_name' => $request->goal_name,
            'target_amount' => $request->target_amount,
            'deadline' => $request->deadline,
            'image' => $imagePath,
        ]);

        if ($savings) {
            return redirect()->route('savings')->with('success', 'Data Berhasil Diubah');
        } else {
            return redirect()->route('savings')->with('error', 'Data Gagal Diubah');
        }
    }

    public function detail($id)
    {
        $saving = savings::with('transactions')->findOrFail($id);
        return view('dashboard.savings.detail', compact('saving'));
    }


    public function delete($id)
    {
        $savings = savings::deleteData($id);
        if ($savings) {
            return redirect()->route('savings')->with('success', 'Data Berhasil Dihapus');
        } else {
            return redirect()->route('savings')->with('error', 'Data Gagal Dihapus');
        }
    }


}
