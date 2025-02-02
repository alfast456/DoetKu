@extends('layouts.app')

@section('content')

<style>
  #dataTable_wrapper {
    padding: 20px;
  }

  #dataTable th,
  #dataTable td {
    text-align: center;
  }

  .dataTables_filter input {
    margin-bottom: 10px;
  }

  .dataTables_length select {
    margin-bottom: 10px;
  }
</style>
<div class="container-fluid px-5">
  <div class="row">
    <div class="col-md-12">
      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
    </div>

    <div class="col-md-12">
      <h1 class="h3 mb-4 text-gray-800">Expenses</h1>
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <!-- div beetween-->
          <div class="d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran</h6>
            <a href="{{ route('expenses.addPage') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
              <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data Pengeluaran
            </a>
          </div>

        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Jumlah</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($expenses as $expense)
                <tr>
                  <td>{{ $expense->date }}</td>
                  <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                  <td>
                    @foreach($categories as $category)
                    @if($category->id_category == $expense->id_category)
                    <span class="badge badge-pill badge-primary">{{ $category->name_category }}</span>
                    @endif
                    @endforeach
                  </td>
                  <td>{{ $expense->description }}</td>
                  <td>
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteexpenseModal{{ $expense->id_expense }}">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th>Tanggal</th>
                  <th>Jumlah</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Modal -->
    @foreach($expenses as $expense)
    <div class="modal fade" id="deleteexpenseModal{{ $expense->id_expense }}" tabindex="-1" aria-labelledby="deleteexpenseModal{{ $expense->id_expense }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteexpenseModal{{ $expense->id_expense }}">Hapus Data Pengeluaran</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Apakah Anda yakin ingin menghapus pengeluaran dengan deskripsi "{{ $expense->description }}"?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <a href="{{ route('expenses.delete', $expense->id_expense) }}" class="btn btn-danger">Hapus</a>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

<!-- DataTables Script -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
  $(document).ready(function() {
    $('#dataTable').DataTable({
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
      },
      "columnDefs": [{
        "orderable": false,
        "targets": [4] // Non-aktifkan pengurutan untuk kolom "Aksi"
      }]
    });
  });
</script>
@endsection