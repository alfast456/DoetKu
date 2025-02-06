@extends('layouts.app')

@section('content')
<div class="container-fluid px-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Tagihan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('bills.insert') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="jenis_tagihan">Jenis Tagihan</label>
                            <select name="jenis_tagihan" id="jenis_tagihan" class="form-control" required>
                                @foreach($categories as $jt)
                                <option value="{{ $jt->id_category }}">{{ $jt->name_category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jumlah">Jumlah Hutang</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="cicilan">Cicilan</label>
                            <input type="number" name="cicilan" id="cicilan" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="jml_cicilan">Jumlah Cicilan</label>
                            <input type="number" name="jml_cicilan" id="jml_cicilan" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_hutang">Tanggal Hutang</label>
                            <input type="date" name="tanggal_hutang" id="tanggal_hutang" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="Lunas">Lunas</option>
                                <option value="Belum Lunas">Belum Lunas</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" required></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('bills') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection