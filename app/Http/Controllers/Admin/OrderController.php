<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\UserOrder;
use App\Models\User\OrderStatus;
use App\Models\User\UserOrderItem;
use App\Models\User\ProductVariantOption;
use App\Models\User\UserItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:user_orders,id',
                'order_status_id' => 'required|exists:order_statuses,id'
            ]);

            $po = UserOrder::find($request->order_id);

            if (!$po) {
                return response()->json(['success' => false, 'message' => 'Pedido não encontrado'], 404);
            }

            // Add to stock if order is rejected
            if ($request->has('order_status_id')) {
                $newStatus = OrderStatus::find($request->order_status_id);
                
                // Se mudando para rejeitado, adiciona à estoque
                if ($newStatus && $newStatus->code === 'cancelado') {
                    $order_items = UserOrderItem::where('user_order_id', $po->id)->get();
                    foreach ($order_items as $order_item) {
                        if (!is_null($order_item->variations)) {
                            $order_variations = json_decode($order_item->variations, true);
                            foreach ($order_variations as $order_variation) {
                                $option = ProductVariantOption::where('id', $order_variation['option_id'])->first();
                                if ($option) {
                                    $option->stock = $option->stock + $order_item->qty;
                                    $option->save();
                                }
                            }
                        } else {
                            $product = UserItem::where('id', $order_item->item_id)->first();
                            if ($product) {
                                $product->stock = $product->stock + $order_item->qty;
                                $product->save();
                            }
                        }
                    }
                }
            }

            // Update both order_status_id and order_status (for legacy compatibility)
            if ($request->has('order_status_id')) {
                $po->order_status_id = $request->order_status_id;
                $status = OrderStatus::find($request->order_status_id);
                if ($status) {
                    $po->order_status = $status->code;
                    // Se status for 'concluido', também aprova o pagamento
                    if ($status->code === 'concluido' || $status->code === 'aprovado') {
                        $po->payment_status = 'Completed';
                    }
                }
            }

            $po->save();

            return response()->json([
                'success' => true,
                'message' => 'Status do pedido atualizado com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }
}
