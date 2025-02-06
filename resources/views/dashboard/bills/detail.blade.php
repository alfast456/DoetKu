@extends('layouts.app')

@section('content')
<div class="container-fluid px-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Detail Hutang</h1>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Detail Hutang</h6>
                        <a href="{{ route('bills') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama Tagihan</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Tanggal Hutang</th>
                                    <th scope="col">Tanggal Selesai</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{ $tagihan->category->name_category }}</td>
                                    <td>Rp {{ number_format($tagihan->total_hutang, 0, ',', '.') }}</td>
                                    <td>{{ $tagihan->tanggal_hutang }}</td>
                                    <td>{{ $tagihan->tanggal_selesai }}</td>
                                    <td>
                                        <span class="badge {{ $tagihan->status_hutang == 'Lunas' ? 'badge-success' : 'badge-danger' }}">
                                            {{ $tagihan->status_hutang }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Jatuh Tempo</th>
                                    <th scope="col">Jumlah Pembayaran</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detail as $index => $d)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $d->tanggal_cicilan }}</td>
                                    <td>Rp {{ number_format($d->jumlah_cicilan, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $d->status_cicilan == 'sudah bayar' ? 'badge-success' : 'badge-danger' }}">
                                            {{ $d->status_cicilan }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editPembayaranModal{{ $d->id }}">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deletePembayaranModal{{ $d->id }}">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($detail as $d)
<div class="modal fade" id="editPembayaranModal{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="editPembayaranModal{{ $d->id }}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPembayaranModal{{ $d->id }}Label">Edit Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('bills.detail.update', $d->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal_cicilan">Tanggal Cicilan</label>
                        <input type="date" name="tanggal_cicilan" id="tanggal_cicilan" class="form-control" value="{{ $d->tanggal_cicilan }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_cicilan">Jumlah Cicilan</label>
                        <input type="number" name="jumlah_cicilan" id="jumlah_cicilan" class="form-control" value="{{ $d->jumlah_cicilan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="status_cicilan">Status Cicilan</label>
                        <select name="status_cicilan" id="status_cicilan" class="form-control" required>
                            <option value="sudah bayar" {{ $d->status_cicilan == 'sudah bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                            <option value="belum bayar" {{ $d->status_cicilan == 'belum bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@foreach($detail as $d)
<div class="modal fade" id="deletePembayaranModal{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="deletePembayaranModal{{ $d->id }}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePembayaranModal{{ $d->id }}Label">Hapus Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pembayaran pada tanggal {{ $d->tanggal_cicilan }}?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="{{ route('bills.detail.delete', $d->id) }}" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>
@endforeach

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#deletePembayaranModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
        });
    });
</script>

@endsection