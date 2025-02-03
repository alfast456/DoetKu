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
      <h1 class="h3 mb-4 text-gray-800">Data Pemasukan</h1>
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <!-- div beetween-->
          <div class="d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Data Pemasukan</h6>
            <a href="{{ route('incomes.addPage') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
              <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data Pemasukan
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
                @foreach($incomes as $income)
                <tr>
                  <td>{{ $income->date }}</td>
                  <td>Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                  <td>
                    @foreach($categories as $category)
                    @if($category->id_category == $income->id_category)
                    <span class="badge badge-pill badge-primary">{{ $category->name_category }}</span>
                    @endif
                    @endforeach
                  </td>
                  <td>{{ $income->description }}</td>
                  <td>
                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editincomeModal{{ $income->id_income }}">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteincomeModal{{ $income->id_income }}">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th>Total</th>
                  <th colspan="4">Rp {{ number_format($total, 0, ',', '.') }}</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- edit modal -->
    @foreach($incomes as $income)
    <div class="modal fade" id="editincomeModal{{ $income->id_income }}" tabindex="-1" aria-labelledby="editincomeModalLabel{{ $income->id_income }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editincomeModalLabel{{ $income->id_income }}">Edit Data Pemasukan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{ route('incomes.update', $income->id_income) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="form-group">
                <label for="date{{ $income->id_income }}">Tanggal</label>
                <input type="date" class="form-control" id="date{{ $income->id_income }}" name="date" value="{{ $income->date }}" required>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="amount{{ $income->id_income }}">Jumlah</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" class="form-control" id="amount{{ $income->id_income }}" name="amount" value="{{ number_format($income->amount, 0, ',', '.') }}" required>
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="category{{ $income->id_income }}">Kategori</label>
                  <select class="form-control" id="category{{ $income->id_income }}" name="id_category" required>
                    <option value="">Pilih Kategori</option> <!-- Opsi default -->
                    @foreach($categories as $category)
                    <option value="{{ $category->id_category }}" {{ $category->id_category == $income->id_category ? 'selected' : '' }}>
                      {{ $category->name_category }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="description{{ $income->id_income }}">Deskripsi</label>
                <textarea class="form-control" id="description{{ $income->id_income }}" name="description" rows="3" required>{{ $income->description }}</textarea>
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
    @endforeach
    <!-- delete modal -->
    @foreach($incomes as $income)
    <div class="modal fade" id="deleteincomeModal{{ $income->id_income }}" tabindex="-1" aria-labelledby="deleteincomeModal{{ $income->id_income }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteincomeModal{{ $income->id_income }}">Hapus Data Kategori</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Apakah Anda yakin ingin menghapus kategori {{ $income->name_income }}?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <a href="{{ route('incomes.delete', $income->id_income) }}" class="btn btn-danger">Hapus</a>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

<!-- Tambahkan jQuery dan script untuk memformat input -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
  $(document).ready(function() {
    // Inisialisasi DataTable
    $('#dataTable').DataTable({
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json" // Opsional: Untuk bahasa Indonesia
      },
      "columnDefs": [{
          "orderable": false,
          "targets": [4]
        } // Non-aktifkan pengurutan untuk kolom "Aksi"
      ]
    });
  });
  $(document).ready(function() {
    // Fungsi untuk memformat input sebagai mata uang
    function formatCurrency(input) {
      // Hapus semua karakter selain angka
      let value = input.val().replace(/[^0-9]/g, '');
      // Format nilai sebagai mata uang
      if (value.length > 0) {
        value = parseInt(value, 10).toLocaleString('id-ID');
      }
      // Set nilai yang diformat kembali ke input
      input.val(value);
    }

    // Format saat input kehilangan fokus
    $('input[name="amount"]').on('blur', function() {
      formatCurrency($(this));
    });

    // Hapus format saat input difokuskan
    $('input[name="amount"]').on('focus', function() {
      let value = $(this).val().replace(/[^0-9]/g, '');
      $(this).val(value);
    });

    // Format nilai awal saat modal dibuka
    $('.modal').on('shown.bs.modal', function() {
      $('input[name="amount"]').each(function() {
        formatCurrency($(this));
      });
    });

    // Pastikan nilai yang dikirim ke server adalah angka tanpa format
    $('form').on('submit', function() {
      $('input[name="amount"]').each(function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(value);
      });
    });
  });
</script>
@endsection