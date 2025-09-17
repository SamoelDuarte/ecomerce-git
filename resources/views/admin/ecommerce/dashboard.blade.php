@extends('admin.layout')

@section('content')
<div class="page-header">
    <div style="width:100%;">
        <div class="card filter-section" style="    background: #fff;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Dashboard Ecommerce</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.ecommerce.export') }}?{{ http_build_query(array_merge(request()->query(), ['chart_type' => 'general', 'format' => 'excel'])) }}"
                            class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel mr-1"></i> Exportar Excel Geral
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.ecommerce') }}" class="mb-3" id="ecommerceFilter">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <input type="date" name="start_date" class="form-control form-control-sm"
                                value="{{ $filters['start_date'] ?? '' }}" placeholder="Data Início">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" class="form-control form-control-sm"
                                value="{{ $filters['end_date'] ?? '' }}" placeholder="Data Fim">
                        </div>
                        <div class="col-md-3">
                            <select name="user_id" class="form-control form-control-sm">
                                <option value="">Todas as Lojas</option>
                                @if(isset($stores))
                                @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ ($filters['user_id'] ?? '') == $store->id ? 'selected' : '' }}>
                                    {{ $store->username }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="order_status" class="form-control form-control-sm">
                                <option value="">Todos os Status</option>
                                <option value="pending" {{ ($filters['order_status'] ?? '') == 'pending' ? 'selected' : '' }}>Pendente</option>
                                <option value="processing" {{ ($filters['order_status'] ?? '') == 'processing' ? 'selected' : '' }}>Processando</option>
                                <option value="completed" {{ ($filters['order_status'] ?? '') == 'completed' ? 'selected' : '' }}>Completo</option>
                                <option value="rejected" {{ ($filters['order_status'] ?? '') == 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-filter mr-1"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('admin.ecommerce') }}" class="btn btn-sm btn-light w-100">Limpar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Cards de Estatísticas --}}
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-default card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Total de Pedidos</p>
                            <h4 class="card-title">{{ $total_orders ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
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
                            <p class="card-category">Faturamento Total</p>
                            <h4 class="card-title">R$ {{ number_format($total_revenue ?? 0, 2, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-default card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-store"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Lojas Ativas</p>
                            <h4 class="card-title">{{ $active_stores ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-default card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Produtos Vendidos</p>
                            <h4 class="card-title">{{ $products_sold ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cards Adicionais --}}
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-default card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-store-alt"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Total Items</p>
                            <h4 class="card-title">{{ $total_items ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
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
                            <p class="card-category">Total Clientes</p>
                            <h4 class="card-title">{{ $total_customers ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-default card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-blog"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Blogs</p>
                            <h4 class="card-title">{{ $blogs ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-default card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Assinantes</p>
                            <h4 class="card-title">{{ $total_subscribers ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Gráfico de Vendas Diárias --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Vendas Diárias</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.ecommerce.export') }}?{{ http_build_query(array_merge(request()->query(), ['chart_type' => 'daily_sales', 'format' => 'excel'])) }}"
                            class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                        <a href="{{ route('admin.ecommerce.export') }}?{{ http_build_query(array_merge(request()->query(), ['chart_type' => 'daily_sales', 'format' => 'csv'])) }}"
                            class="btn btn-sm btn-info">
                            <i class="fas fa-file-csv"></i> CSV
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 300px;">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico de Top Produtos --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Produtos Mais Vendidos</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.ecommerce.export') }}?{{ http_build_query(array_merge(request()->query(), ['chart_type' => 'top_products', 'format' => 'excel'])) }}"
                            class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                        <a href="{{ route('admin.ecommerce.export') }}?{{ http_build_query(array_merge(request()->query(), ['chart_type' => 'top_products', 'format' => 'csv'])) }}"
                            class="btn btn-sm btn-info">
                            <i class="fas fa-file-csv"></i> CSV
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 300px;">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Gráfico de Vendas por Loja --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Vendas por Loja</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.ecommerce.export') }}?{{ http_build_query(array_merge(request()->query(), ['chart_type' => 'sales_by_store', 'format' => 'excel'])) }}"
                            class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                        <a href="{{ route('admin.ecommerce.export') }}?{{ http_build_query(array_merge(request()->query(), ['chart_type' => 'sales_by_store', 'format' => 'csv'])) }}"
                            class="btn btn-sm btn-info">
                            <i class="fas fa-file-csv"></i> CSV
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 350px;">
                    <canvas id="salesByStoreChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Status dos Pedidos --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Status dos Pedidos</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.ecommerce.export') }}?{{ http_build_query(array_merge(request()->query(), ['chart_type' => 'order_status', 'format' => 'excel'])) }}"
                            class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 350px;">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Lista de Pedidos Recentes --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <h4 class="card-title">Últimos Pedidos</h4>
                </div>
            </div>
            <div class="card-body">
                @if($orders->isEmpty())
                <div class="text-center py-4">
                    <h5 class="text-muted">Nenhum pedido encontrado</h5>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Loja</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>{{ $order->user->username }}</td>
                                <td>R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge 
                                                @if($order->order_status == 'pending') badge-warning
                                                @elseif($order->order_status == 'processing') badge-primary
                                                @elseif($order->order_status == 'completed') badge-success
                                                @else badge-danger @endif">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($order->user)
                                    <div class="dropdown">
                                        <button class="btn btn-info btn-sm dropdown-toggle"
                                            type="button" data-toggle="dropdown">
                                            Ações
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('register.user.secret_login', $order->user->id) }}?redirect={{ route('user.item.details', $order->id) }}"
                                                target="_blank">
                                                Ver Detalhes
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Logs de Pagamento Recentes --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <h4 class="card-title">Logs de Pagamento Recentes</h4>
                </div>
            </div>
            <div class="card-body">
                @if($memberships->isEmpty())
                <div class="text-center py-4">
                    <h5 class="text-muted">Nenhum log de pagamento encontrado</h5>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($memberships as $membership)
                            <tr>
                                <td>{{ Str::limit($membership->transaction_id, 30) }}</td>
                                <td>
                                    {{ $membership->price == 0 ? 'Grátis' : 'R$ ' . number_format($membership->price, 2, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge 
                                                @if($membership->status == 1) badge-success
                                                @elseif($membership->status == 0) badge-warning
                                                @elseif($membership->status == 2) badge-danger @endif">
                                        {{ $membership->status == 1 ? 'Sucesso' : ($membership->status == 0 ? 'Pendente' : 'Rejeitado') }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#detailsModal{{ $membership->id }}">
                                        Detalhes
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal de Detalhes --}}
                            <div class="modal fade" id="detailsModal{{ $membership->id }}"
                                tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalhes do Pagamento</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h4 class="text-warning">
                                                Detalhes do Membro</h4>
                                            <p><strong>Nome:</strong>
                                                {{ $membership->user->shop_name ?? $membership->user->username ?? '-' }}
                                            </p>
                                            <p><strong>Email:</strong>
                                                {{ $membership->user->email ?? '-' }}
                                            </p>
                                            <p><strong>Telefone:</strong>
                                                {{ $membership->user->phone ?? '-' }}
                                            </p>

                                            <h4 class="text-warning">
                                                Detalhes do Pagamento</h4>
                                            <p><strong>Valor:</strong>
                                                {{ $membership->price == 0 ? 'Grátis' : 'R$ ' . number_format($membership->price, 2, ',', '.') }}
                                            </p>
                                            <p><strong>Moeda:</strong>
                                                {{ $membership->currency }}
                                            </p>
                                            <p><strong>Método:</strong>
                                                {{ $membership->payment_method }}
                                            </p>

                                            <h4 class="text-warning">
                                                Detalhes do Pacote</h4>
                                            <p><strong>Título:</strong>
                                                {{ $membership->package->title ?? '-' }}
                                            </p>
                                            <p><strong>Termo:</strong>
                                                {{ $membership->package->term ?? '-' }}
                                            </p>
                                            <p><strong>Data de Início:</strong>
                                                @if (\Carbon\Carbon::parse($membership->start_date)->year == 9999)
                                                <span
                                                    class="badge badge-danger">Nunca Ativado</span>
                                                @else
                                                {{ \Carbon\Carbon::parse($membership->start_date)->format('d/m/Y') }}
                                                @endif
                                            </p>
                                            <p><strong>Data de Expiração:</strong>
                                                @if (\Carbon\Carbon::parse($membership->start_date)->year == 9999)
                                                -
                                                @elseif ($membership->package->term == 'vitalicio')
                                                Vitalício
                                                @else
                                                {{ \Carbon\Carbon::parse($membership->expire_date)->format('d/m/Y') }}
                                                @endif
                                            </p>
                                            <p><strong>Tipo de Compra:</strong>
                                                @if ($membership->is_trial == 1)
                                                Trial
                                                @else
                                                {{ $membership->price == 0 ? 'Grátis' : 'Regular' }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Dados dos gráficos do controller
        const chartData = @json($chart_data ?? []);

        console.log('Chart Data:', chartData);

        // Configurações comuns dos gráficos
        Chart.defaults.font.family = 'Nunito, -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.color = '#858796';

        // 1. Gráfico de Vendas Diárias
        if (chartData.daily_sales && chartData.daily_sales.labels && chartData.daily_sales.labels.length > 0) {
            const dailySalesCtx = document.getElementById('dailySalesChart');
            new Chart(dailySalesCtx, {
                type: 'line',
                data: {
                    labels: chartData.daily_sales.labels || [],
                    datasets: [{
                        label: 'Vendas (R$)',
                        data: chartData.daily_sales.data || [],
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR');
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    }
                }
            });
        }

        // 2. Gráfico de Top Produtos
        if (chartData.top_products && chartData.top_products.labels && chartData.top_products.labels.length > 0) {
            const topProductsCtx = document.getElementById('topProductsChart');
            new Chart(topProductsCtx, {
                type: 'bar',
                data: {
                    labels: chartData.top_products.labels || [],
                    datasets: [{
                        label: 'Quantidade Vendida',
                        data: chartData.top_products.data || [],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(199, 199, 199, 0.8)',
                            'rgba(83, 102, 255, 0.8)',
                            'rgba(255, 99, 255, 0.8)',
                            'rgba(99, 255, 132, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 205, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(199, 199, 199, 1)',
                            'rgba(83, 102, 255, 1)',
                            'rgba(255, 99, 255, 1)',
                            'rgba(99, 255, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
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
        }

        // 3. Gráfico de Vendas por Loja
        if (chartData.sales_by_store && chartData.sales_by_store.labels && chartData.sales_by_store.labels.length > 0) {
            const salesByStoreCtx = document.getElementById('salesByStoreChart');
            new Chart(salesByStoreCtx, {
                type: 'bar',
                data: {
                    labels: chartData.sales_by_store.labels || [],
                    datasets: [{
                        label: 'Faturamento (R$)',
                        data: chartData.sales_by_store.data || [],
                        backgroundColor: 'rgba(75, 192, 192, 0.8)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR');
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': R$ ' + context.parsed.x.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    }
                }
            });
        }

        // 4. Gráfico de Status dos Pedidos
        if (chartData.order_status && chartData.order_status.labels && chartData.order_status.labels.length > 0) {
            const orderStatusCtx = document.getElementById('orderStatusChart');
            new Chart(orderStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: chartData.order_status.labels || [],
                    datasets: [{
                        data: chartData.order_status.data || [],
                        backgroundColor: [
                            'rgba(255, 206, 84, 0.8)', // pending - amarelo
                            'rgba(54, 162, 235, 0.8)', // processing - azul
                            'rgba(75, 192, 192, 0.8)', // completed - verde
                            'rgba(255, 99, 132, 0.8)' // rejected - vermelho
                        ],
                        borderColor: [
                            'rgba(255, 206, 84, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
</script>
@endsection