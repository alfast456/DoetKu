@extends('layouts.app')

@section('content')
<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="alert border-left-secondary shadow alert-warning alert-dismissible fade shadow show" role="alert">
        <strong>Selamat Datang!</strong> Anda telah masuk sebagai <strong>{{ Auth::user()->name }}</strong>.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
  </div>

  <div class="row">
    @php
    $cards = [
    ['title' => 'Pemasukan Hari Ini', 'value' => $today_incomes, 'icon' => 'fa-calendar-day', 'color' => 'success'],
    ['title' => 'Pemasukan Bulan Ini', 'value' => $monthly_incomes, 'icon' => 'fa-calendar-alt', 'color' => 'success'],
    ['title' => 'Pemasukan Tahun Ini', 'value' => $yearly_incomes, 'icon' => 'fa-calendar', 'color' => 'success'],
    ['title' => 'Total Pemasukan', 'value' => $total_incomes, 'icon' => 'fa-wallet', 'color' => 'success'],
    ['title' => 'Pengeluaran Hari Ini', 'value' => $today_expenses, 'icon' => 'fa-calendar-day', 'color' => 'danger'],
    ['title' => 'Pengeluaran Bulan Ini', 'value' => $monthly_expenses, 'icon' => 'fa-calendar-alt', 'color' => 'danger'],
    ['title' => 'Pengeluaran Tahun Ini', 'value' => $yearly_expenses, 'icon' => 'fa-calendar', 'color' => 'danger'],
    ['title' => 'Total Pengeluaran', 'value' => $total_expenses, 'icon' => 'fa-wallet', 'color' => 'danger'],
    ];
    @endphp

    @foreach ($cards as $card)
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card border-left-{{ $card['color'] }} shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                {{ $card['title'] }}
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                {{ number_format($card['value'], 0, ',', '.') }}
              </div>
            </div>
            <div class="col-auto">
              <i class="fas {{ $card['icon'] }} fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>


  <div class="row">
    <!-- Grafik Pemasukan & Pengeluaran -->
    <div class="col-lg-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Grafik Pemasukan & Pengeluaran</h6>
        </div>
        <div class="card-body">
          <canvas id="incomeExpenseChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-sm-12">
      <div class="table">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <!-- div beetween -->
            <div class="d-sm-flex align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Data Pemasukan & Pengeluaran</h6>
              <a href="{{ route('generate.pdf') }}" class="btn btn-primary">Download PDF</a>

            </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                  <thead>
                    <tr>
                      <th>Amount</th>
                      <th>Description</th>
                      <th>Date</th>
                      <th>Category</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($incomesAndExpenses as $incomeAndExpense)
                    <tr>
                      <td>{{ number_format($incomeAndExpense->amount, 0, ',', '.') }}</td>
                      <td>{{ $incomeAndExpense->description }}</td>
                      <td>{{ $incomeAndExpense->date }}</td>
                      <td>{{ $categories->where('id_category', $incomeAndExpense->id_category)->first()->name_category ?? 'Tidak Diketahui' }}</td>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
      // DataTable
      $(document).ready(function() {
        $('#dataTable').DataTable({
          "paging": true, // Aktifkan pagination
          "searching": true, // Aktifkan pencarian
          "ordering": true, // Aktifkan sorting kolom
          "responsive": true, // Responsive untuk mobile
          "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
          }
        });
      });
    </script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('incomeExpenseChart').getContext('2d');
        const incomeExpenseChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Pemasukan',
                data: @json($chartData['incomes']),
                backgroundColor: 'rgba(40, 167, 69, 0.6)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
              },
              {
                label: 'Pengeluaran',
                data: @json($chartData['expenses']),
                backgroundColor: 'rgba(220, 53, 69, 0.6)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      });
    </script>

    @endsection