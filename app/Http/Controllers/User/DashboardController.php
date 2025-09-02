<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\UserOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('web')->user();
        
        // Inicializa a query
        $query = UserOrder::where('user_id', $user->id);

        // Aplica filtros de data se fornecidos
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Busca os pedidos
        $orders = $query->latest()->take(10)->get();

        // Calcula as estatísticas
        $total_orders = $query->count();
        $total_sales = $query->sum('total');
        $average_ticket = $total_orders > 0 ? $total_sales / $total_orders : 0;

        return view('user.dashboard', compact(
            'orders',
            'total_orders',
            'total_sales',
            'average_ticket',
            'user'
        ));
    }
}
