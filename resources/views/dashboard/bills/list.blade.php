@extends('layouts.app')

@section('content')
<div class="container-fluid px-5">
    <div class="row">
        <div class="table-responsive shadow-sm p-3">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                <a href="{{ route('bills.addPage') }}" class="btn btn-sm btn-primary mb-3">
                    <i class="fas fa-plus"></i>
                    Tambah Tagihan
                </a>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nama Tagihan</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Tanggal Hutang</th>
                        <th scope="col">Tanggal Selesai</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tagihan as $index => $t)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @foreach($categories as $c)
                            @if($c->id_category == $t->id_category)
                            {{ $c->name_category }}
                            @endif
                            @endforeach
                        </td>
                        <td>Rp {{ number_format($t->total_hutang, 0, ',', '.') }}</td>
                        <td>{{ $t->tanggal_hutang }}</td>
                        <td>{{ $t->tanggal_selesai }}</td>
                        <td>
                            <span class="badge {{ $t->status_hutang == 'lunas' ? 'badge-success' : 'badge-danger' }}">
                                {{ $t->status_hutang }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('bills.editPage', $t->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                                Detail
                            </a>
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editTagihanModal{{ $t->id }}">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteTagihanModal{{ $t->id }}">
                                <i class="fas fa-trash"></i>
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @foreach($tagihan as $t)
        <div class="modal fade" id="deleteTagihanModal{{ $t->id }}" tabindex="-1" aria-labelledby="deleteTagihanModal{{ $t->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Data Tagihan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus tagihan {{ $t->nama_tagihan }}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="{{ route('bills.delete', $t->id) }}" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection