@extends('layouts.app')

@section('content')
<div class="container-fluid px-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Tambah Tabungan</h1>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Tabungan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('savings.insert') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="goal_name">Nama Tujuan</label>
                            <input type="text" class="form-control" id="goal_name" name="goal_name" required>
                        </div>
                        <div class="form-group">
                            <label for="target_amount">Target Jumlah</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="target_amount" name="target_amount" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="deadline">Batas Waktu</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Gambar</label>
                            <input type="file" class="form-control-file" id="image" name="image" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('savings') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function cleanCurrency(value) {
            return value.replace(/\./g, '').replace(',', '.'); // Menghapus pemisah ribuan dan mengganti koma dengan titik
        }

        document.getElementById("target_amount").addEventListener("blur", function() {
            let cleanedValue = cleanCurrency(this.value);
            if (!isNaN(cleanedValue) && cleanedValue.length > 0) {
                this.value = parseFloat(cleanedValue).toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        });

        document.getElementById("target_amount").addEventListener("focus", function() {
            this.value = cleanCurrency(this.value); // Menghapus format saat fokus
        });
    });
</script>
@endsection