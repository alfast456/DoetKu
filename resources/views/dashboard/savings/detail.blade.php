@extends('layouts.app')

@section('content')
<div class="container-fluid px-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Detail Tabungan</h1>
        </div>

        <!-- Kartu Informasi Tabungan -->
        <div class="col-md-4">
            <div class="card shadow text-center py-4">
                <div class="card-body">
                    <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Target</h5>
                    <p class="card-text font-weight-bold">Rp {{ number_format($saving->target_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow text-center py-4">
                <div class="card-body">
                    <i class="fas fa-wallet fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Jumlah Saat Ini</h5>
                    <p class="card-text font-weight-bold">Rp {{ number_format($saving->current_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow text-center py-4">
                <div class="card-body">
                    <i class="fas fa-calendar-alt fa-3x text-danger mb-3"></i>
                    <h5 class="card-title">Deadline</h5>
                    <p class="card-text font-weight-bold">{{ $saving->deadline }}</p>
                </div>
            </div>
        </div>

        <!-- Tombol Tambah Transaksi -->
        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="text-dark">Riwayat Transaksi</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#addTransactionModal">
                    <i class="fas fa-plus"></i> Tambah Transaksi
                </button>
            </div>
        </div>

        <!-- Tabel Riwayat Transaksi -->
        <div class="col-md-12 mt-3">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-bordered text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Jenis</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($saving->transactions->isNotEmpty())
                            @foreach($saving->transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_date }}</td>
                                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-{{ $transaction->type == 'deposit' ? 'success' : 'danger' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editTransactionModal" data-transaction="{{ $transaction }}" data-savings="{{ $saving }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteTransactionModal" data-transaction="{{ json_encode($transaction) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-center">Belum ada transaksi</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <a href="{{ route('savings') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>

<!-- Modal Tambah Transaksi -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('transactions.insert') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_saving" value="{{ $saving->id }}">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="transaction_date" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jenis</label>
                        <select class="form-control" name="type" required>
                            <option value="deposit">Deposit</option>
                            <option value="withdrawal">Penarikan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Transaksi -->
<div class="modal fade" id="editTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTransactionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_saving" id="id_saving">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="transaction_date" id="transaction_date" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" name="amount" id="amount" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jenis</label>
                        <select class="form-control" name="type" id="type" required>
                            <option value="deposit">Deposit</option>
                            <option value="withdrawal">Penarikan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Hapus Transaksi -->
<div class="modal fade" id="deleteTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteTransactionForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id_saving" id="delete_id_saving">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#editTransactionModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var transaction = button.data('transaction');

            var modal = $(this);
            modal.find('#editTransactionForm').attr('action', '/savings/transactions/update/' + transaction.id);
            modal.find('#id_saving').val(transaction.saving_id);
            modal.find('#transaction_date').val(transaction.transaction_date);
            modal.find('#amount').val(transaction.amount);
            modal.find('#type').val(transaction.type);
            modal.find('#description').val(transaction.description);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#deleteTransactionModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var transaction = button.data('transaction');

            var modal = $(this);
            modal.find('#deleteTransactionForm').attr('action', '/savings/transactions/delete/' + transaction.id);
            modal.find('#delete_id_saving').val(transaction.saving_id);
        });
    });
</script>


@endsection