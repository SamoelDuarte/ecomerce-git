<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\EcommerceExport;
use App\Exports\MembershipExport;
use App\Models\Customer;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserNewsletterSubscriber;
use App\Models\User\UserOrder;
use App\Models\User\UserPage;
use App\Models\User\Blog;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DashboardController extends Controller
{
  public function dashboard(Request $request)
  {
    // Validação dos filtros
    $request->validate([
      'start_date' => 'nullable|date',
      'end_date' => 'nullable|date|after_or_equal:start_date',
      'user_id' => 'nullable|exists:users,id',
      'package_id' => 'nullable|exists:packages,id',
      'payment_status' => 'nullable|in:active,expired,pending'
    ]);

    // Filtros de período
    $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
    $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
    
    // Filtros por loja/usuário
    $userId = $request->get('user_id');
    
    // Filtro por plano
    $packageId = $request->get('package_id');
    
    // Filtro por status de pagamento
    $paymentStatus = $request->get('payment_status');

    // Query base de receitas com filtros (incluindo todos os planos)
    $incomesQuery = Membership::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(price) total'))
      ->where('status', 1)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->groupBy('date')
      ->orderBy('date');

    // Aplicar filtros apenas se especificados
    if ($userId) {
      $incomesQuery->where('user_id', $userId);
    }

    if ($packageId) {
      $incomesQuery->where('package_id', $packageId);
    }

    $data['incomes'] = $incomesQuery->get();

    // Receita total no período (todos os planos por padrão)
    $data['total_revenue'] = Membership::where('status', 1)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->when($userId, function($query) use ($userId) {
        return $query->where('user_id', $userId);
      })
      ->when($packageId, function($query) use ($packageId) {
        return $query->where('package_id', $packageId);
      })
      ->sum('price');

    // Usuários premium por mês
    $data['users'] = User::join('memberships', 'users.id', '=', 'memberships.user_id')
      ->select(DB::raw('MONTH(users.created_at) month'), DB::raw('count(DISTINCT users.id) total'))
      ->groupBy('month')
      ->whereYear('users.created_at', date('Y'))
      ->where([
        ['memberships.status', '=', 1],
        ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
        ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
      ])
      ->get();

    // Total de usuários lojistas
    $data['total_users'] = User::count();
    $data['total_active_users'] = User::where('status', 1)->count();
    $data['total_memberships'] = Membership::where('status', 1)->count();

    // Lista de todos os usuários lojistas com seus planos e status
    $usersQuery = User::with(['memberships' => function($query) {
        $query->where('status', 1)
              ->where('start_date', '<=', now())
              ->where('expire_date', '>=', now())
              ->latest();
      }, 'memberships.package'])
      ->when($userId, function($query) use ($userId) {
        return $query->where('id', $userId);
      })
      ->when($packageId, function($query) use ($packageId) {
        return $query->whereHas('memberships', function($q) use ($packageId) {
          $q->where('package_id', $packageId)->where('status', 1);
        });
      })
      ->when($paymentStatus, function($query) use ($paymentStatus) {
        if ($paymentStatus == 'active') {
          return $query->whereHas('memberships', function($q) {
            $q->where('status', 1)
              ->where('start_date', '<=', now())
              ->where('expire_date', '>=', now());
          });
        } elseif ($paymentStatus == 'expired') {
          return $query->whereHas('memberships', function($q) {
            $q->where('status', 1)
              ->where('expire_date', '<', now());
          });
        } elseif ($paymentStatus == 'pending') {
          return $query->whereHas('memberships', function($q) {
            $q->where('status', 0);
          });
        }
      })
      ->orderBy('created_at', 'desc');

    $data['users_list'] = $usersQuery->paginate(20);

    // Lista de pacotes para filtro
    $data['packages'] = Package::where('status', '1')->orderBy('title')->get();
    
    // Lista de usuários para filtro
    $data['users_filter'] = User::select('id', 'username', 'shop_name')
      ->where('status', 1)
      ->orderBy('username')
      ->get();

    // Estatísticas por plano
    $data['package_stats'] = Package::withCount(['memberships as active_memberships_count' => function($query) {
        $query->where('status', 1)
              ->where('start_date', '<=', now())
              ->where('expire_date', '>=', now());
      }])
      ->withSum(['memberships as total_revenue' => function($query) {
        $query->where('status', 1);
      }], 'price')
      ->where('status', '1')
      ->get();

    $data['defaultLang'] = Language::where('is_default', 1)->first();

    // Dados para gráficos
    $data['chart_data'] = $this->getChartData($startDate, $endDate, $userId, $packageId);

    // Parâmetros de filtro para manter na view
    $data['filters'] = [
      'start_date' => $startDate,
      'end_date' => $endDate,
      'user_id' => $userId,
      'package_id' => $packageId,
      'payment_status' => $paymentStatus
    ];

    return view('admin.dashboard', $data);
  }

  private function getChartData($startDate, $endDate, $userId = null, $packageId = null)
  {
    // Dados para gráfico de receitas por dia (todos os planos)
    $revenues = Membership::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(price) as total'))
      ->where('status', 1)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->when($userId, function($query) use ($userId) {
        return $query->where('user_id', $userId);
      })
      ->when($packageId, function($query) use ($packageId) {
        return $query->where('package_id', $packageId);
      })
      ->groupBy('date')
      ->orderBy('date')
      ->get();

    // Dados de receitas por plano
    $revenuesByPackage = Membership::with('package')
      ->select('package_id', DB::raw('SUM(price) as total'))
      ->where('status', 1)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->when($userId, function($query) use ($userId) {
        return $query->where('user_id', $userId);
      })
      ->when($packageId, function($query) use ($packageId) {
        return $query->where('package_id', $packageId);
      })
      ->groupBy('package_id')
      ->get()
      ->map(function($item) {
        return [
          'package_name' => $item->package->title ?? 'Sem plano',
          'total' => $item->total,
          'package_id' => $item->package_id
        ];
      });

    // Dados para gráfico de novos usuários por dia
    $newUsers = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
      ->whereBetween('created_at', [$startDate, $endDate])
      ->groupBy('date')
      ->orderBy('date')
      ->get();

    // Preenchimento de datas vazias com zero
    $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
    $revenuesFormatted = [];
    $newUsersFormatted = [];
    
    foreach ($period as $date) {
      $dateStr = $date->format('Y-m-d');
      
      // Receitas
      $revenueItem = $revenues->firstWhere('date', $dateStr);
      $revenuesFormatted[] = [
        'date' => $dateStr,
        'total' => $revenueItem ? floatval($revenueItem->total) : 0
      ];
      
      // Usuários
      $userItem = $newUsers->firstWhere('date', $dateStr);
      $newUsersFormatted[] = [
        'date' => $dateStr,
        'total' => $userItem ? intval($userItem->total) : 0
      ];
    }

    return [
      'revenues' => $revenuesFormatted,
      'new_users' => $newUsersFormatted,
      'revenues_by_package' => $revenuesByPackage
    ];
  }

  public function exportData(Request $request)
  {
    // Validação dos parâmetros
    $request->validate([
      'start_date' => 'nullable|date',
      'end_date' => 'nullable|date|after_or_equal:start_date',
      'user_id' => 'nullable|exists:users,id',
      'package_id' => 'nullable|exists:packages,id',
      'format' => 'required|in:csv,excel'
    ]);

    $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
    $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
    $userId = $request->get('user_id');
    $packageId = $request->get('package_id');
    $format = $request->get('format', 'csv');

    $data = Membership::with(['user', 'package'])
      ->where('status', 1)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->when($userId, function($query) use ($userId) {
        return $query->where('user_id', $userId);
      })
      ->when($packageId, function($query) use ($packageId) {
        return $query->where('package_id', $packageId);
      })
      ->orderBy('created_at', 'desc')
      ->get();

    if ($format === 'csv') {
      return $this->exportToCSV($data);
    } elseif ($format === 'excel') {
      return $this->exportToExcel($data);
    }

    return response()->json(['error' => 'Formato inválido'], 400);
  }

  private function exportToCSV($data)
  {
    $filename = 'memberships_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
    
    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function() use ($data) {
      $file = fopen('php://output', 'w');
      
      // Cabeçalhos
      fputcsv($file, [
        'ID',
        'Usuário',
        'Nome da Loja',
        'Plano',
        'Preço',
        'Status',
        'Data Início',
        'Data Expiração',
        'Método Pagamento',
        'Data Criação'
      ]);

      // Dados
      foreach ($data as $membership) {
        fputcsv($file, [
          $membership->id,
          $membership->user->username ?? '',
          $membership->user->shop_name ?? '',
          $membership->package->title ?? '',
          $membership->price,
          $membership->status == 1 ? 'Ativo' : 'Inativo',
          $membership->start_date,
          $membership->expire_date,
          $membership->payment_method,
          $membership->created_at->format('Y-m-d H:i:s')
        ]);
      }
      
      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  private function exportToExcel($data)
  {
    $filename = 'memberships_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Definir cabeçalhos
    $headers = [
      'ID',
      'Usuário',
      'Nome da Loja',
      'Plano',
      'Preço',
      'Status',
      'Data Início',
      'Data Expiração',
      'Método Pagamento',
      'Data Criação'
    ];
    
    // Escrever cabeçalhos
    $col = 'A';
    foreach ($headers as $header) {
      $sheet->setCellValue($col . '1', $header);
      $sheet->getStyle($col . '1')->getFont()->setBold(true);
      $sheet->getColumnDimension($col)->setAutoSize(true);
      $col++;
    }
    
    // Escrever dados
    $row = 2;
    foreach ($data as $membership) {
      $sheet->setCellValue('A' . $row, $membership->id);
      $sheet->setCellValue('B' . $row, $membership->user->username ?? '');
      $sheet->setCellValue('C' . $row, $membership->user->shop_name ?? '');
      $sheet->setCellValue('D' . $row, $membership->package->title ?? '');
      $sheet->setCellValue('E' . $row, $membership->price);
      $sheet->setCellValue('F' . $row, $membership->status == 1 ? 'Ativo' : 'Inativo');
      $sheet->setCellValue('G' . $row, $membership->start_date);
      $sheet->setCellValue('H' . $row, $membership->expire_date);
      $sheet->setCellValue('I' . $row, $membership->payment_method);
      $sheet->setCellValue('J' . $row, $membership->created_at->format('Y-m-d H:i:s'));
      $row++;
    }
    
    // Aplicar estilo ao cabeçalho
    $sheet->getStyle('A1:J1')->applyFromArray([
      'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4CAF50']
      ],
      'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF']
      ]
    ]);
    
    // Criar o writer
    $writer = new Xlsx($spreadsheet);
    
    // Headers para download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
  }

  // public function ecommerce()
  // {
  //   $user = Auth::guard('web')->user();
  //   $data['user'] = $user;
  //   $data['blogs'] = $user->blogs()->count();
  //   $data['memberships'] = Membership::query()->where('user_id', Auth::guard('web')->user()->id)
  //     ->orderBy('id', 'DESC')
  //     ->limit(10)->get();

  //   $data['users'] = [];

  //   $nextPackageCount = Membership::query()->where([
  //     ['user_id', $user->id],
  //     ['expire_date', '>=', Carbon::now()->toDateString()]
  //   ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
  //   //current package
  //   $data['current_membership'] = Membership::query()->where([
  //     ['user_id', $user->id],
  //     ['start_date', '<=', Carbon::now()->toDateString()],
  //     ['expire_date', '>=', Carbon::now()->toDateString()]
  //   ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
  //   if ($data['current_membership']) {
  //     $countCurrMem = Membership::query()->where([
  //       ['user_id', $user->id],
  //       ['start_date', '<=', Carbon::now()->toDateString()],
  //       ['expire_date', '>=', Carbon::now()->toDateString()]
  //     ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
  //     if ($countCurrMem > 1) {
  //       $data['next_membership'] = Membership::query()->where([
  //         ['user_id', $user->id],
  //         ['start_date', '<=', Carbon::now()->toDateString()],
  //         ['expire_date', '>=', Carbon::now()->toDateString()]
  //       ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
  //     } else {
  //       $data['next_membership'] = Membership::query()->where([
  //         ['user_id', $user->id],
  //         ['start_date', '>', $data['current_membership']->expire_date]
  //       ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
  //     }
  //     $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
  //   }
  //   $data['current_package'] = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
  //   $data['package_count'] = $nextPackageCount;

  //   $user_currency = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
  //   if (empty($user_currency)) {
  //     $user_currency = UserCurrency::where('user_id', $user->id)->first();
  //     if ($user_currency) {
  //       $user_currency->is_default = 1;
  //       $user_currency->save();
  //     }
  //   }

  //   $data['total_items'] = UserItem::where('user_id', $user->id)->count();
  //   $data['total_orders'] = UserOrder::where('user_id', $user->id)->count();
  //   $data['total_customers'] = Customer::where('user_id', $user->id)->count();
  //   $data['total_custom_pages'] = UserPage::where('user_id', $user->id)->count();
  //   $data['total_subscribers'] = UserNewsletterSubscriber::where('user_id', $user->id)->count();

  //   $data['orders'] = UserOrder::where('user_id', $user->id)
  //     ->orderBy('id', 'DESC')->limit(10)->get();
  //   return view('admin.ecommerce.dashboard', $data);
  // }

  public function ecommerce(Request $request)
  {
    // Validação dos filtros

    $request->validate([
      'start_date' => 'nullable|date_format:Y-m-d\TH:i',
      'end_date' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start_date',
      'user_id' => 'nullable|exists:users,id',
      'order_status' => 'nullable|in:completed,pending,processing,rejected'
    ]);

    // Filtros de período (datetime)
    $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d 00:00'));
    $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d 23:59'));

    // Ajusta formato para o Eloquent (se vier do input datetime-local)
    if ($request->filled('start_date')) {
      $startDate = str_replace('T', ' ', $request->get('start_date'));
    }
    if ($request->filled('end_date')) {
      $endDate = str_replace('T', ' ', $request->get('end_date'));
    }
    
    // Filtros por loja/usuário
    $userId = $request->get('user_id');
    
    // Filtro por status do pedido
    $orderStatus = $request->get('order_status');

    $data = [];

    // Contadores globais com filtros de período
    $data['blogs'] = Blog::count();
    
    // Total de itens com filtro por período
    $itemsQuery = UserItem::whereBetween('created_at', [$startDate, $endDate]);
    if ($userId) {
      $itemsQuery->where('user_id', $userId);
    }
    $data['total_items'] = $itemsQuery->count();

    // Total de pedidos com filtros
    $ordersQuery = UserOrder::whereBetween('created_at', [$startDate, $endDate]);
    if ($userId) {
      $ordersQuery->where('user_id', $userId);
    }
    if ($orderStatus) {
      $ordersQuery->where('order_status', $orderStatus);
    }
    $data['total_orders'] = $ordersQuery->count();

    // Receita total do período
    $revenueQuery = UserOrder::where('order_status', 'completed')
      ->whereBetween('created_at', [$startDate, $endDate]);
    if ($userId) {
      $revenueQuery->where('user_id', $userId);
    }
    $data['total_revenue'] = $revenueQuery->sum('total');

    // Contadores sem filtro de período
    $data['total_customers'] = Customer::count();
    $data['total_custom_pages'] = UserPage::count();
    $data['total_subscribers'] = UserNewsletterSubscriber::count();

    // Lojas ativas (que têm pedidos)
    $data['active_stores'] = User::whereHas('orders')->count();

    // Produtos vendidos no período
    $data['products_sold'] = DB::table('user_order_items')
      ->join('user_orders', 'user_order_items.user_order_id', '=', 'user_orders.id')
      ->where('user_orders.order_status', 'completed')
      ->whereBetween('user_orders.created_at', [$startDate, $endDate])
      ->when($userId, function($query) use ($userId) {
        return $query->where('user_orders.user_id', $userId);
      })
      ->sum('user_order_items.qty');

    // Últimos pedidos com filtros e nome da loja
    $ordersListQuery = UserOrder::with(['user' => function($query) {
        $query->select('id', 'username', 'shop_name', 'email');
      }, 'orderitems' => function($query) {
        $query->select('id', 'user_order_id', 'title', 'qty');
      }, 'status' => function($query) {
        $query->select('id', 'name', 'code');
      }])
      ->whereBetween('created_at', [$startDate, $endDate]);
    
    if ($userId) {
      $ordersListQuery->where('user_id', $userId);
    }
    if ($orderStatus) {
      $ordersListQuery->where('order_status', $orderStatus);
    }
    
    $data['orders'] = $ordersListQuery->orderBy('id', 'DESC')->limit(20)->get();

    // Memberships recentes (últimos 10)
    $data['memberships'] = Membership::orderBy('id', 'DESC')->limit(10)->get();

    // Pacotes ativos no momento
    $data['current_membership'] = Membership::where('start_date', '<=', now())
      ->where('expire_date', '>=', now())
      ->where('status', 1)
      ->whereYear('start_date', '<>', '9999')
      ->first();

    $data['next_membership'] = Membership::where('start_date', '>', now())
      ->whereYear('start_date', '<>', '9999')
      ->where('status', '<>', 2)
      ->orderBy('start_date')
      ->first();

    $data['current_package'] = $data['current_membership']
      ? Package::find($data['current_membership']->package_id)
      : null;

    $data['next_package'] = $data['next_membership']
      ? Package::find($data['next_membership']->package_id)
      : null;

    $data['package_count'] = Membership::where('expire_date', '>=', now())
      ->whereYear('start_date', '<>', '9999')
      ->where('status', '<>', 2)
      ->count();

    // Lista de usuários para filtro com nomes das lojas
    $data['stores'] = User::select('id', 'username', 'shop_name', 'email')
      ->where('status', 1)
      ->whereHas('orders')
      ->orderBy('username')
      ->get();

    // Dados para gráficos
    $data['chart_data'] = $this->getEcommerceChartData($startDate, $endDate, $userId, $orderStatus);

    // Parâmetros de filtro para manter na view
    $data['filters'] = [
      'start_date' => $startDate,
      'end_date' => $endDate,
      'user_id' => $userId,
      'order_status' => $orderStatus
    ];

    return view('admin.ecommerce.dashboard', $data);
  }

  private function getEcommerceChartData($startDate, $endDate, $userId = null, $orderStatus = null)
  {
    // Dados para gráfico de vendas por dia
    $salesQuery = UserOrder::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
      ->where('order_status', 'completed')
      ->whereBetween('created_at', [$startDate, $endDate]);
    
    if ($userId) {
      $salesQuery->where('user_id', $userId);
    }
    
    $sales = $salesQuery->groupBy('date')->orderBy('date')->get();

    // Dados para gráfico de produtos mais vendidos
    $topProductsQuery = DB::table('user_order_items')
      ->join('user_orders', 'user_order_items.user_order_id', '=', 'user_orders.id')
      ->join('users', 'user_orders.user_id', '=', 'users.id')
      ->select('user_order_items.title', 'users.shop_name', DB::raw('SUM(user_order_items.qty) as total_sold'))
      ->where('user_orders.order_status', 'completed')
      ->whereBetween('user_orders.created_at', [$startDate, $endDate]);
    
    if ($userId) {
      $topProductsQuery->where('user_orders.user_id', $userId);
    }
    
    $topProducts = $topProductsQuery->groupBy('user_order_items.item_id', 'user_order_items.title', 'users.shop_name')
      ->orderBy('total_sold', 'desc')
      ->limit(10)
      ->get();

    // Dados para gráfico de vendas por loja
    $salesByStoreQuery = UserOrder::join('users', 'user_orders.user_id', '=', 'users.id')
      ->select('users.shop_name', 'users.username', DB::raw('SUM(user_orders.total) as total_sales'), DB::raw('COUNT(user_orders.id) as total_orders'))
      ->where('user_orders.order_status', 'completed')
      ->whereBetween('user_orders.created_at', [$startDate, $endDate]);
    
    if ($userId) {
      $salesByStoreQuery->where('user_orders.user_id', $userId);
    }
    
    $salesByStore = $salesByStoreQuery->groupBy('users.id', 'users.shop_name', 'users.username')
      ->orderBy('total_sales', 'desc')
      ->limit(10)
      ->get();

    // Dados para gráfico de status dos pedidos
    $orderStatusQuery = UserOrder::select('order_status', DB::raw('COUNT(*) as count'))
      ->whereBetween('created_at', [$startDate, $endDate]);
    
    if ($userId) {
      $orderStatusQuery->where('user_id', $userId);
    }
    
    $orderStatusData = $orderStatusQuery->groupBy('order_status')->get();

    // Preenchimento de datas vazias com zero
    $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
    $salesFormatted = [];
    
    foreach ($period as $date) {
      $dateStr = $date->format('Y-m-d');
      $saleItem = $sales->firstWhere('date', $dateStr);
      $salesFormatted[] = [
        'date' => $dateStr,
        'total' => $saleItem ? floatval($saleItem->total) : 0,
        'count' => $saleItem ? intval($saleItem->count) : 0
      ];
    }

    // Formatação dos dados para os gráficos
    return [
      'daily_sales' => [
        'labels' => collect($salesFormatted)->pluck('date')->toArray(),
        'data' => collect($salesFormatted)->pluck('total')->toArray()
      ],
      'top_products' => [
        'labels' => $topProducts->pluck('title')->map(function($title) {
          return strlen($title) > 20 ? substr($title, 0, 17) . '...' : $title;
        })->toArray(),
        'data' => $topProducts->pluck('total_sold')->toArray()
      ],
      'sales_by_store' => [
        'labels' => $salesByStore->pluck('username')->map(function($name) {
          $storeName = $name ?: 'Sem nome';
          return strlen($storeName) > 15 ? substr($storeName, 0, 12) . '...' : $storeName;
        })->toArray(),
        'data' => $salesByStore->pluck('total_sales')->toArray()
      ],
      'order_status' => [
        'labels' => $orderStatusData->pluck('order_status')->map(function($status) {
          return ucfirst($status);
        })->toArray(),
        'data' => $orderStatusData->pluck('count')->toArray()
      ]
    ];
  }

  public function exportEcommerceData(Request $request)
  {
    // Validação dos parâmetros
    $request->validate([
      'start_date' => 'nullable|date',
      'end_date' => 'nullable|date|after_or_equal:start_date',
      'user_id' => 'nullable|exists:users,id',
      'order_status' => 'nullable|in:completed,pending,processing,rejected',
      'chart_type' => 'required|in:daily_sales,top_products,sales_by_store,order_status,general',
      'format' => 'required|in:csv,excel'
    ]);

    $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
    $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
    $userId = $request->get('user_id');
    $orderStatus = $request->get('order_status');
    $chartType = $request->get('chart_type');
    $format = $request->get('format', 'excel');

    // Buscar dados baseado no tipo de gráfico
    switch ($chartType) {
      case 'daily_sales':
        $data = $this->getDailySalesData($startDate, $endDate, $userId);
        $filename = 'vendas_diarias_' . now()->format('Y-m-d_H-i-s');
        $headers = ['Data', 'Total Vendas (R$)', 'Quantidade Pedidos'];
        break;

      case 'top_products':
        $data = $this->getTopProductsData($startDate, $endDate, $userId);
        $filename = 'produtos_mais_vendidos_' . now()->format('Y-m-d_H-i-s');
        $headers = ['Produto', 'Loja', 'Quantidade Vendida'];
        break;

      case 'sales_by_store':
        $data = $this->getSalesByStoreData($startDate, $endDate, $userId);
        $filename = 'vendas_por_loja_' . now()->format('Y-m-d_H-i-s');
        $headers = ['Loja', 'Total Vendas (R$)', 'Quantidade Pedidos'];
        break;

      case 'order_status':
        $data = $this->getOrderStatusData($startDate, $endDate, $userId);
        $filename = 'status_pedidos_' . now()->format('Y-m-d_H-i-s');
        $headers = ['Status', 'Quantidade'];
        break;

      case 'general':
      default:
        $data = $this->getGeneralEcommerceData($startDate, $endDate, $userId, $orderStatus);
        $filename = 'relatorio_ecommerce_geral_' . now()->format('Y-m-d_H-i-s');
        $headers = ['Número Pedido', 'Loja', 'Itens', 'Endereço Entrega', 'Total (R$)', 'Status Pedido', 'Status Pagamento', 'Data'];
        break;
    }

    if ($format === 'csv') {
      return $this->exportEcommerceToCSV($data, $headers, $filename);
    } else {
      return $this->exportEcommerceToExcel($data, $headers, $filename, $chartType);
    }
  }

  private function getDailySalesData($startDate, $endDate, $userId = null)
  {
    $salesQuery = UserOrder::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
      ->where('order_status', 'completed')
      ->whereBetween('created_at', [$startDate, $endDate]);
    
    if ($userId) {
      $salesQuery->where('user_id', $userId);
    }
    
    return $salesQuery->groupBy('date')->orderBy('date')->get();
  }

  private function getTopProductsData($startDate, $endDate, $userId = null)
  {
    $topProductsQuery = DB::table('user_order_items')
      ->join('user_orders', 'user_order_items.user_order_id', '=', 'user_orders.id')
      ->join('users', 'user_orders.user_id', '=', 'users.id')
      ->select('user_order_items.title', 'users.shop_name', 'users.username', DB::raw('SUM(user_order_items.qty) as total_sold'))
      ->where('user_orders.order_status', 'completed')
      ->whereBetween('user_orders.created_at', [$startDate, $endDate]);
    
    if ($userId) {
      $topProductsQuery->where('user_orders.user_id', $userId);
    }
    
    return $topProductsQuery->groupBy('user_order_items.item_id', 'user_order_items.title', 'users.shop_name', 'users.username')
      ->orderBy('total_sold', 'desc')
      ->get();
  }

  private function getSalesByStoreData($startDate, $endDate, $userId = null)
  {
    $salesByStoreQuery = UserOrder::join('users', 'user_orders.user_id', '=', 'users.id')
      ->select('users.shop_name', 'users.username', DB::raw('SUM(user_orders.total) as total_sales'), DB::raw('COUNT(user_orders.id) as total_orders'))
      ->where('user_orders.order_status', 'completed')
      ->whereBetween('user_orders.created_at', [$startDate, $endDate]);
    
    if ($userId) {
      $salesByStoreQuery->where('user_orders.user_id', $userId);
    }
    
    return $salesByStoreQuery->groupBy('users.id', 'users.shop_name', 'users.username')
      ->orderBy('total_sales', 'desc')
      ->get();
  }

  private function getOrderStatusData($startDate, $endDate, $userId = null)
  {
    $orderStatusQuery = UserOrder::select('order_status', DB::raw('COUNT(*) as count'))
      ->whereBetween('created_at', [$startDate, $endDate]);
    
    if ($userId) {
      $orderStatusQuery->where('user_id', $userId);
    }
    
    return $orderStatusQuery->groupBy('order_status')->get();
  }

  private function getGeneralEcommerceData($startDate, $endDate, $userId = null, $orderStatus = null)
  {
    $ordersQuery = UserOrder::with(['user' => function($query) {
        $query->select('id', 'username', 'shop_name', 'email');
      }, 'orderitems' => function($query) {
        $query->select('id', 'user_order_id', 'title', 'qty');
      }, 'status' => function($query) {
        $query->select('id', 'name', 'code');
      }])
      ->whereBetween('created_at', [$startDate, $endDate]);
    
    if ($userId) {
      $ordersQuery->where('user_id', $userId);
    }
    if ($orderStatus) {
      $ordersQuery->where('order_status', $orderStatus);
    }
    
    return $ordersQuery->orderBy('created_at', 'desc')->get();
  }

  private function exportEcommerceToCSV($data, $headers, $filename)
  {
    $filename = $filename . '.csv';
    
    $headers_response = [
      'Content-Type' => 'text/csv; charset=UTF-8',
      'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function() use ($data, $headers) {
      $file = fopen('php://output', 'w');
      
      // Adicionar BOM para UTF-8
      fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
      
      // Cabeçalhos
      fputcsv($file, $headers, ';');

      // Dados
      foreach ($data as $item) {
        $row = [];
        
        if (isset($item->date)) {
          // Vendas diárias
          $row = [
            \Carbon\Carbon::parse($item->date)->format('d/m/Y'),
            number_format($item->total, 2, ',', '.'),
            $item->count
          ];
        } elseif (isset($item->title)) {
          // Top produtos
          $row = [
            $item->title,
            $item->username ?: 'Sem nome',
            $item->total_sold
          ];
        } elseif (isset($item->shop_name) && isset($item->total_sales)) {
          // Vendas por loja
          $row = [
            $item->username ?: 'Sem nome',
            number_format($item->total_sales, 2, ',', '.'),
            $item->total_orders
          ];
        } elseif (isset($item->order_status) && isset($item->count)) {
          // Status pedidos
          $row = [
            ucfirst($item->order_status),
            $item->count
          ];
        } else {
          // Relatório geral
          $items = '';
          if (isset($item->orderitems) && $item->orderitems->count() > 0) {
            $items = implode(' | ', $item->orderitems->map(function($oi) {
              return $oi->title . ' (x' . $oi->qty . ')';
            })->toArray());
          }
          
          $address = '';
          if ($item->shipping_street) {
            $address = $item->shipping_street . ', ' . $item->shipping_number_address;
            if ($item->shipping_neighborhood) {
              $address .= ' - ' . $item->shipping_neighborhood;
            }
            if ($item->shipping_zip) {
              $address .= ' - CEP: ' . $item->shipping_zip;
            }
          }
          
          $row = [
            $item->order_number ?: 'N/A',
            $item->user->username ?: 'Sem nome',
            $items ?: 'Sem itens',
            $address ?: 'Sem endereço',
            number_format($item->total, 2, ',', '.'),
            ucfirst($item->order_status),
            $item->payment_status ?: 'N/A',
            $item->created_at->format('d/m/Y H:i')
          ];
        }
        
        fputcsv($file, $row, ';');
      }
      
      fclose($file);
    };

    return response()->stream($callback, 200, $headers_response);
  }

  private function exportEcommerceToExcel($data, $headers, $filename, $type = 'general')
  {
    $filename = $filename . '.xlsx';
    
    return Excel::download(new EcommerceExport($data, $headers, $type), $filename);
  }


  public function changeTheme(Request $request)
  {
    return redirect()->back()->withCookie(cookie()->forever('admin-theme', $request->theme));
  }
}
