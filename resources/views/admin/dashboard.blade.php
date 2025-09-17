@extends('admin.layout')

@php
$admin = Auth::guard('admin')->user();
if (!empty($admin->role)) {
$permissions = $admin->role->permissions;
$permissions = json_decode($permissions, true);
}
@endphp

@section('styles')
<!-- CSS customizado do dashboard -->
<link rel="stylesheet" href="{{ asset('assets/admin/css/dashboard-custom.css') }}">
<style>
  .card-secondary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }

  .filter-section {
    background: #fff;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .filter-section .card-body {
    padding: 15px;
  }

  .form-group-sm {
    margin-bottom: 15px;
  }

  .form-label-sm {
    font-size: 12px;
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
    display: block;
  }

  .form-control-sm {
    height: 32px;
    padding: 4px 8px;
    font-size: 13px;
    border-radius: 4px;
  }

  .btn-sm {
    padding: 4px 12px;
    font-size: 12px;
    border-radius: 4px;
  }

  .stats-card {
    transition: transform 0.3s ease;
  }

  .stats-card:hover {
    transform: translateY(-5px);
  }

  .table-responsive {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .badge {
    font-size: 0.85em;
  }

  .btn-group-sm .btn {
    padding: 4px 10px;
    font-size: 11px;
  }

  .card-header {
    padding: 12px 20px;
  }

  .card-header .btn-group {
    margin-top: -2px;
  }

  @media (max-width: 768px) {
    .btn-group-vertical .btn {
      margin-bottom: 5px;
    }

    .card-header .col-md-4 {
      text-align: center !important;
      margin-top: 10px;
    }
  }
</style>
@endsection

@section('content')
<div class="mt-2 mb-4">
  <h2 class="{{ request()->cookie('admin-theme') == 'dark' ? 'text-white' : 'text-dark' }} pb-2">
    {{ __('Welcome back,') }}
    {{ Auth::guard('admin')->user()->first_name }}
    {{ Auth::guard('admin')->user()->last_name }}!
  </h2>
</div>

<!-- Filtros -->
<div class="card mb-4 filter-section">
  <div class="card-header">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h4 class="card-title mb-0"><i class="fas fa-filter"></i> Filtros</h4>
      </div>
      <div class="col-md-4 text-right">
        <div class="btn-group btn-group-sm" role="group">
          <a href="{{ route('admin.dashboard.export', array_merge($filters, ['format' => 'csv'])) }}"
            class="btn btn-success btn-sm">
            <i class="fas fa-file-csv"></i> CSV
          </a>
          <a href="{{ route('admin.dashboard.export', array_merge($filters, ['format' => 'excel'])) }}"
            class="btn btn-primary btn-sm">
            <i class="fas fa-file-excel"></i> Excel
          </a>
          <button type="button" class="btn btn-info btn-sm" onclick="window.print()">
            <i class="fas fa-print"></i> Print
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="row">
      <div class="col-md-2 col-sm-6">
        <div class="form-group form-group-sm">
          <label for="start_date" class="form-label-sm">Data Início</label>
          <input type="date" name="start_date" id="start_date" class="form-control form-control-sm"
            value="{{ $filters['start_date'] }}">
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="form-group form-group-sm">
          <label for="end_date" class="form-label-sm">Data Fim</label>
          <input type="date" name="end_date" id="end_date" class="form-control form-control-sm"
            value="{{ $filters['end_date'] }}">
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="form-group form-group-sm">
          <label for="user_id" class="form-label-sm">Usuário/Loja</label>
          <select name="user_id" id="user_id" class="form-control form-control-sm">
            <option value="">Todos os usuários</option>
            @foreach($users_filter as $user)
            <option value="{{ $user->id }}" {{ $filters['user_id'] == $user->id ? 'selected' : '' }}>
              {{ $user->username }} @if($user->shop_name)- {{ $user->shop_name }}@endif
            </option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="form-group form-group-sm">
          <label for="package_id" class="form-label-sm">Plano</label>
          <select name="package_id" id="package_id" class="form-control form-control-sm">
            <option value="">Todos</option>
            @foreach($packages as $package)
            <option value="{{ $package->id }}" {{ $filters['package_id'] == $package->id ? 'selected' : '' }}>
              {{ $package->title }}@if($package->term) ({{ $package->term }})@endif
            </option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="form-group form-group-sm">
          <label for="payment_status" class="form-label-sm">Status</label>
          <select name="payment_status" id="payment_status" class="form-control form-control-sm">
            <option value="">Todos</option>
            <option value="active" {{ $filters['payment_status'] == 'active' ? 'selected' : '' }}>
              Ativo
            </option>
            <option value="expired" {{ $filters['payment_status'] == 'expired' ? 'selected' : '' }}>
              Expirado
            </option>
            <option value="pending" {{ $filters['payment_status'] == 'pending' ? 'selected' : '' }}>
              Pendente
            </option>
          </select>
        </div>
      </div>
      <div class="col-md-1 col-sm-12">
        <div class="form-group form-group-sm">
          <label class="form-label-sm">&nbsp;</label>
          <div class="btn-group-vertical btn-group-sm d-block">
            <button type="submit" class="btn btn-primary btn-sm mb-1">Filtrar</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">Limpar</a>
          </div>
        </div>
      </div>
    </form>

    <!-- Filtros Rápidos -->
    <div class="row mt-3">
      <div class="col-12">
        <small class="text-muted">Filtros Rápidos:</small>
        <div class="btn-group btn-group-sm ml-2" role="group">
          <button type="button" class="btn btn-outline-secondary btn-sm" id="quick-today">Hoje</button>
          <button type="button" class="btn btn-outline-secondary btn-sm" id="quick-week">7 dias</button>
          <button type="button" class="btn btn-outline-secondary btn-sm" id="quick-month">Mês atual</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Estatísticas Gerais -->
<div class="row mb-4">
  <div class="col-md-3">
    <div class="card card-stats card-default card-round">
      <div class="card-body">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="fas fa-dollar-sign"></i>
            </div>
          </div>
          <div class="col-7 col-stats">
            <div class="numbers">
              <p class="card-category">Receita Total</p>
              <h4 class="card-title">${{ number_format($total_revenue, 2) }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-stats card-default card-round">
      <div class="card-body">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="fas fa-users"></i>
            </div>
          </div>
          <div class="col-7 col-stats">
            <div class="numbers">
              <p class="card-category">Total Lojas</p>
              <h4 class="card-title">{{ $total_users }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-stats card-default card-round">
      <div class="card-body">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="fas fa-user-check"></i>
            </div>
          </div>
          <div class="col-7 col-stats">
            <div class="numbers">
              <p class="card-category">Lojas Ativas</p>
              <h4 class="card-title">{{ $total_active_users }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-stats card-default card-round">
      <div class="card-body">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="fas fa-credit-card"></i>
            </div>
          </div>
          <div class="col-7 col-stats">
            <div class="numbers">
              <p class="card-category">Memberships Ativas</p>
              <h4 class="card-title">{{ $total_memberships }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Tabela de Lojas -->
<div class="card mb-4">
  <div class="card-header card-secondary">
    <h4 class="card-title">Lojas Cadastradas</h4>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover custom-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Loja</th>
            <th>Nome da Loja</th>
            <th>Email</th>
            <th>Plano Atual</th>
            <th>Status Pagamento</th>
            <th>Data Expiração</th>
            <th>Receita Total</th>
            <th>Data Registro</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users_list as $user)
          @php
          $currentMembership = $user->memberships->first();
          $isActive = $currentMembership &&
          $currentMembership->status == 1 &&
          $currentMembership->start_date <= now()->format('Y-m-d') &&
            $currentMembership->expire_date >= now()->format('Y-m-d');
            $isExpired = $currentMembership &&
            $currentMembership->expire_date < now()->format('Y-m-d');
              $totalRevenue = $user->memberships->where('status', 1)->sum('price');
              @endphp
              <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->shop_name ?? 'N/A' }}</td>
                <td>{{ $user->email }}</td>
                <td>
                  @if($currentMembership && $currentMembership->package)
                  <span class="badge badge-info">
                    {{ $currentMembership->package->title }} ({{ $currentMembership->package->term }})
                  </span>
                  @else
                  <span class="badge badge-secondary">Sem Plano</span>
                  @endif
                </td>
                <td>
                  @if($isActive)
                  <span class="badge badge-success">Ativo</span>
                  @elseif($isExpired)
                  <span class="badge badge-danger">Expirado</span>
                  @elseif($currentMembership && $currentMembership->status == 0)
                  <span class="badge badge-warning">Pendente</span>
                  @else
                  <span class="badge badge-secondary">Inativo</span>
                  @endif
                </td>
                <td>
                  @if($currentMembership)
                  {{ $currentMembership->expire_date }}
                  @if($currentMembership->expire_date < now()->format('Y-m-d'))
                    <small class="text-danger">(Atrasado)</small>
                    @endif
                    @else
                    N/A
                    @endif
                </td>
                <td>${{ number_format($totalRevenue, 2) }}</td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="9" class="text-center">Nenhuma loja encontrada</td>
              </tr>
              @endforelse
        </tbody>
      </table>
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-3 mb-3">
      {{ $users_list->appends(request()->query())->links() }}
    </div>
  </div>
</div>

<!-- Gráficos -->
<div class="row mt-4">
  @if (empty($admin->role) || (!empty($permissions) && in_array('Payment Log', $permissions)))
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Receita por Período</div>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  @endif

  @if (empty($admin->role) || (!empty($permissions) && in_array('Users Management', $permissions)))
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Novas Lojas por Período</div>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="usersChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>

<!-- Gráfico de Receitas por Plano -->
<div class="row mt-4">
  @if (!empty($chart_data['revenues_by_package']) && count($chart_data['revenues_by_package']) > 0)
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Receita por Plano</div>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="packageRevenueChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection

@section('scripts')
<!-- Chart JS -->
<script src="{{ asset('assets/admin/js/plugin/chart.min.js') }}"></script>
<script>
  "use strict";

  console.log('Chart data:', @json($chart_data));

  // Dados para gráfico de receita
  var chartData = @json($chart_data);
  var revenueData = chartData.revenues || [];
  var revenueLabels = revenueData.map(item => item.date);
  var revenueValues = revenueData.map(item => parseFloat(item.total) || 0);

  // Dados para gráfico de usuários
  var usersData = chartData.new_users || [];
  var usersLabels = usersData.map(item => item.date);
  var usersValues = usersData.map(item => parseInt(item.total) || 0);

  // Dados para gráfico de receitas por plano
  var packageRevenueData = chartData.revenues_by_package || [];
  var packageLabels = packageRevenueData.map(item => item.package_name);
  var packageValues = packageRevenueData.map(item => parseFloat(item.total) || 0);

  // Cores para gráfico de planos
  var packageColors = [
    '#1f8ef1', '#59d05d', '#ff9500', '#ff4757', '#3742fa',
    '#2ed573', '#ffa502', '#ff4757', '#747d8c', '#5352ed'
  ];

  // Gráfico de Receita
  if (document.getElementById('revenueChart')) {
    var revenueCtx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(revenueCtx, {
      type: 'line',
      data: {
        labels: revenueLabels,
        datasets: [{
          label: 'Receita ($)',
          data: revenueValues,
          borderColor: '#1f8ef1',
          backgroundColor: 'rgba(31, 142, 241, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top'
          },
          tooltip: {
            mode: 'index',
            intersect: false
          }
        },
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: 'Data'
            }
          },
          y: {
            beginAtZero: true,
            display: true,
            title: {
              display: true,
              text: 'Receita ($)'
            },
            ticks: {
              callback: function(value) {
                return '$' + value.toFixed(2);
              }
            }
          }
        },
        interaction: {
          mode: 'nearest',
          axis: 'x',
          intersect: false
        }
      }
    });
  }

  // Gráfico de Usuários
  if (document.getElementById('usersChart')) {
    var usersCtx = document.getElementById('usersChart').getContext('2d');
    var usersChart = new Chart(usersCtx, {
      type: 'bar',
      data: {
        labels: usersLabels,
        datasets: [{
          label: 'Novas Lojas',
          data: usersValues,
          backgroundColor: '#59d05d',
          borderColor: '#59d05d',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        },
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: 'Data'
            }
          },
          y: {
            beginAtZero: true,
            display: true,
            title: {
              display: true,
              text: 'Quantidade de Lojas'
            },
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });
  }

  // Gráfico de Receitas por Plano
  if (document.getElementById('packageRevenueChart') && packageRevenueData.length > 0) {
    var packageCtx = document.getElementById('packageRevenueChart').getContext('2d');
    var packageChart = new Chart(packageCtx, {
      type: 'doughnut',
      data: {
        labels: packageLabels,
        datasets: [{
          label: 'Receita por Plano ($)',
          data: packageValues,
          backgroundColor: packageColors.slice(0, packageLabels.length),
          borderColor: '#ffffff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'right'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                var label = context.label || '';
                var value = context.raw || 0;
                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                var percentage = ((value / total) * 100).toFixed(1);
                return label + ': $' + value.toFixed(2) + ' (' + percentage + '%)';
              }
            }
          }
        }
      }
    });
  }

  // Funcionalidades extras para filtros
  $(document).ready(function() {
    // Filtros rápidos
    $('#quick-today').click(function() {
      var today = new Date().toISOString().split('T')[0];
      $('#start_date').val(today);
      $('#end_date').val(today);
    });

    $('#quick-week').click(function() {
      var today = new Date();
      var weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
      $('#start_date').val(weekAgo.toISOString().split('T')[0]);
      $('#end_date').val(today.toISOString().split('T')[0]);
    });

    $('#quick-month').click(function() {
      var today = new Date();
      var monthAgo = new Date(today.getFullYear(), today.getMonth(), 1);
      $('#start_date').val(monthAgo.toISOString().split('T')[0]);
      $('#end_date').val(today.toISOString().split('T')[0]);
    });

    // Tooltip para badges
    $('.badge').tooltip();

    // Confirmação para exportação
    $('.export-buttons a').click(function(e) {
      var format = $(this).text().includes('CSV') ? 'CSV' : 'Excel';
      if (!confirm('Exportar dados em formato ' + format + '?')) {
        e.preventDefault();
      }
    });

    // Debug: Verificar se os dados estão sendo passados corretamente
    if (revenueData.length === 0) {
      console.warn('Nenhum dado de receita encontrado para o período selecionado');
    }
    if (usersData.length === 0) {
      console.warn('Nenhum dado de usuários encontrado para o período selecionado');
    }
  });
</script>
@endsection