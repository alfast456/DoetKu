@extends('layouts.app')

@section('content')

<style>
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    .saving-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 20px;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        text-align: center;
    }

    .saving-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .saving-card h5 {
        font-size: 1.4rem;
        font-weight: bold;
        color: #4e73df;
        margin-top: 10px;
    }

    .currency {
        font-weight: bold;
        color: #28a745;
        font-size: 1.2rem;
    }

    .btn-action {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 15px;
    }

    .img-fluid {
        width: 100%;
        height: 50vh;
        object-fit: cover;
        border-radius: 12px;
    }

    .modal-body img {
        width: 100%;
        height: auto;
        border-radius: 12px;
        margin-bottom: 15px;
    }
</style>

<div class="container-fluid px-5">
    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">x</button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>

        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800">Tabungan Saya</h1>
                <a href="{{ route('savings.addPage') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Tabungan
                </a>
            </div>

            <div class="card-container">
                @forelse($savings as $saving)
                <div class="saving-card">
                    <img src="{{ asset('storage/'.$saving->image) }}" alt="{{ $saving->goal_name }}" class="img-fluid mb-3">
                    <h5>{{ $saving->goal_name }}</h5>
                    <p class="currency">Rp {{ number_format($saving->target_amount, 0, ',', '.') }}</p>
                    <p><strong>Deadline:</strong> {{ $saving->deadline }}</p>
                    <div class="btn-action">
                        <a href="{{ route('savings.detail', $saving->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSavingModal"
                            data-transaction="{{ $saving }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteSavingModal"
                            data-transaction="{{ $saving }}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada tabungan yang ditambahkan.</p>
                    <a href="{{ route('savings.addPage') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus"></i> Tambah Tabungan Pertama Anda
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editSavingModal" tabindex="-1" aria-labelledby="editSavingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSavingModalLabel">Edit Tabungan</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSavingForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_goal_name">Nama Tujuan</label>
                        <input type="text" class="form-control" id="edit_goal_name" name="goal_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_target_amount">Target Jumlah</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="edit_target_amount" name="target_amount" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_deadline">Batas Waktu</label>
                        <input type="date" class="form-control" id="edit_deadline" name="deadline" required>
                    </div>
                    <!-- image show -->
                    <div class="form-group">
                        <label for="edit_image">Gambar</label>
                        <img src="" id="edit_image_preview" class="img-fluid mb-2" style="display: none;">
                        <input type="file" class="form-control-file" id="edit_image" name="image">
                        <input type="hidden" name="old_image" id="edit_old_image">
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteSavingModal" tabindex="-1" aria-labelledby="deleteSavingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSavingModalLabel">Hapus Tabungan</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteSavingForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <input type="hidden" name="id" id="delete_id">
                    <p>Apakah Anda yakin ingin menghapus tabungan ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#editSavingModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var saving = button.data('transaction');
            var modal = $(this);

            modal.find('.modal-body #edit_id').val(saving.id);
            modal.find('.modal-body #edit_goal_name').val(saving.goal_name);
            modal.find('.modal-body #edit_target_amount').val(saving.target_amount);
            modal.find('.modal-body #edit_deadline').val(saving.deadline);
            modal.find('.modal-body #edit_old_image').val(saving.image);
            modal.find('.modal-body #edit_image_preview').attr('src', '/storage/' + saving.image).show();

            $('#editSavingForm').attr('action', '/savings/update/' + saving.id);
        });
    });

    $(document).ready(function() {
        $('#deleteSavingModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var saving = button.data('transaction');
            var modal = $(this);

            modal.find('.modal-body #delete_id').val(saving.id);

            $('#deleteSavingForm').attr('action', '/savings/delete/' + saving.id);
        });
    });
</script>

<script>
    $(document).ready(function() {
        function formatCurrency(input) {
            let value = input.val().replace(/[^0-9]/g, '');
            if (value) {
                input.val('Rp ' + parseInt(value, 10).toLocaleString('id-ID'));
            }
        }

        function removeFormat(input) {
            let value = input.val().replace(/[^0-9]/g, '');
            input.val(value);
        }

        $('input[name="current_amount"]').on('blur', function() {
            formatCurrency($(this));
        });

        $('input[name="current_amount"]').on('focus', function() {
            removeFormat($(this));
        });

        // Pastikan nilai yang dikirim ke server adalah angka tanpa format
        $('form').on('submit', function() {
            $('input[name="current_amount"]').each(function() {
                removeFormat($(this));
            });
        });
    });
</script>
@endsection