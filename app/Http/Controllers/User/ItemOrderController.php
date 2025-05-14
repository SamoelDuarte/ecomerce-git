<?php

namespace App\Http\Controllers\User;

use App\Exports\PorductOrderExport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\Common;
use App\Models\BasicExtended;
use App\Models\Customer;
use App\Models\User\Language;
use App\Models\User\ProductVariantOption;
use App\Models\User\UserEmailTemplate;
use App\Models\User\UserItem;
use App\Models\User\UserOfflineGateway;
use App\Models\User\UserOrder;
use App\Models\User\UserOrderItem;
use App\Models\User\UserPaymentGeteway;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ItemOrderController extends Controller
{
    public function all(Request $request)
    {
        $search = $request->search;
        $data['orders'] =
            UserOrder::when($search, function ($query, $search) {
                return $query->where('order_number', $search);
            })->where('user_id', Auth::guard('web')->user()->id)
            ->orderBy('id', 'DESC')->paginate(10);
        return view('user.item.order.index', $data);
    }

    public function pending(Request $request)
    {
        $search = $request->search;
        $data['orders'] = UserOrder::when($search, function ($query, $search) {
            return $query->where('order_number', $search);
        })->where('user_id', Auth::guard('web')->user()->id)
            ->where('order_status', 'pending')->orderBy('id', 'DESC')->paginate(10);
        return view('user.item.order.index', $data);
    }

    public function processing(Request $request)
    {
        $search = $request->search;
        $data['orders'] = UserOrder::where('order_status', 'processing')
            ->when($search, function ($query, $search) {
                return $query->where('order_number', $search);
            })->where('user_id', Auth::guard('web')->user()->id)
            ->orderBy('id', 'DESC')->paginate(10);
        return view('user.item.order.index', $data);
    }

    public function completed(Request $request)
    {
        $search = $request->search;
        $data['orders'] = UserOrder::where('order_status', 'completed')->when($search, function ($query, $search) {
            return $query->where('order_number', $search);
        })->where('user_id', Auth::guard('web')->user()->id)
            ->orderBy('id', 'DESC')->paginate(10);
        return view('user.item.order.index', $data);
    }

    public function rejected(Request $request)
    {
        $search = $request->search;
        $data['orders'] = UserOrder::where('order_status', 'rejected')->when($search, function ($query, $search) {
            return $query->where('order_number', $search);
        })->where('user_id', Auth::guard('web')->user()->id)
            ->orderBy('id', 'DESC')->paginate(10);
        return view('user.item.order.index', $data);
    }

    public function status(Request $request)
    {
        try {
            $root_user = Auth::guard('web')->user();
            $user_id = $root_user->id;
            $po = UserOrder::find($request->order_id);

            //add to stock if order is rejected
            if ($request->order_status == 'rejected') {
                //get order items
                $order_items = UserOrderItem::where([['user_order_id', $po->id], ['user_id', $user_id]])->get();
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
                        $product->stock = $product->stock + $order_item->qty;
                        $product->save();
                    }
                }
            }
            $po->order_status = $request->order_status;
            $po->save();

            //get customer information
            $customer = Customer::where('id', $po->customer_id)->first();
            if ($customer) {
                $f_name = $customer->first_name;
                $l_name = $customer->last_name;
                $email = $customer->email;
            } else {
                $f_name = $po->billing_fname;
                $l_name = $po->billing_lname;
                $email = $po->billing_email;
            }

            //reove pervious invoice and generate a new
            @unlink(public_path('assets/front/invoices/') . $po->invoice_number);
            $invoice = Common::generateInvoice($po, $root_user);
            $po->update(['invoice_number' => $invoice]);

            //send mail
            $mail_template = UserEmailTemplate::where([['user_id', $user_id], ['email_type', 'product_order_status']])->first();
            $mail_subject = $mail_template->email_subject;
            $mail_body = $mail_template->email_body;

            $info = DB::table('basic_extendeds')
                ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
                ->first();

            $mail_body = str_replace('{customer_name}', $f_name . ' ' . $l_name, $mail_body);
            $mail_body = str_replace('{order_status}', $request->order_status, $mail_body);
            $mail_body = str_replace('{website_title}', $root_user->shop_name ?? $root_user->username, $mail_body);

            $to = $email;

            /******** Send mail to user ********/
            $data = [];
            $data['smtp_status'] = $info->is_smtp;
            $data['smtp_host'] = $info->smtp_host;
            $data['smtp_port'] = $info->smtp_port;
            $data['encryption'] = $info->encryption;
            $data['smtp_username'] = $info->smtp_username;
            $data['smtp_password'] = $info->smtp_password;

            //mail info in array
            $data['from_mail'] = $info->from_mail;
            $data['recipient'] = $to;
            $data['subject'] = $mail_subject;
            $data['body'] = $mail_body;
            $data['invoice'] = public_path('assets/front/invoices/' . $po->invoice_number);
            BasicMailer::sendMail($data);

            Session::flash('success', __('Updated Successfully'));
            return back();
        } catch (\Exception $th) {
        }
    }

    public function mail(Request $request)
    {
        $rules = [
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $be = BasicExtended::first();
        $sub = $request->subject;
        $msg = $request->message;
        $to = $request->email;

        /******** Send mail to user ********/
        $data = [];
        $data['smtp_status'] = $be->is_smtp;
        $data['smtp_host'] = $be->smtp_host;
        $data['smtp_port'] = $be->smtp_port;
        $data['encryption'] = $be->encryption;
        $data['smtp_username'] = $be->smtp_username;
        $data['smtp_password'] = $be->smtp_password;

        //mail info in array
        $data['from_mail'] = $be->from_mail;
        $data['recipient'] = $to;
        $data['subject'] = $sub;
        $data['body'] = $msg;
        BasicMailer::sendMail($data);

        Session::flash('success', __('Sent successfully'));
        return "success";
    }

    public function details($id)
    {
        $itemLang = Language::where([['dashboard_default', 1], ['user_id', Auth::guard('web')->user()->id]])->select('id')->first();
        $order = UserOrder::findOrFail($id);
        return view('user.item.order.details', compact('order', 'itemLang'));
    }


    public function bulkOrderDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $order = UserOrder::findOrFail($id);
            @unlink(public_path('assets/front/invoices/') . $order->invoice_number);
            @unlink(public_path('assets/front/receipt/') . $order->receipt);
            foreach ($order->orderitems as $item) {
                $item->delete();
            }
            $order->delete();
        }

        Session::flash('success', __('Deleted successfully'));
        return "success";
    }

    public function orderDelete(Request $request)
    {
        $order = UserOrder::findOrFail($request->order_id);
        @unlink(public_path('assets/front/invoices/') . $order->invoice_number);
        @unlink(public_path('assets/front/receipt/') . $order->receipt);
        foreach ($order->orderitems as $item) {
            $item->delete();
        }
        $order->delete();

        Session::flash('success', __('Deleted successfully'));
        return back();
    }

    public function report(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $paymentStatus = $request->payment_status;
        $orderStatus = $request->order_status;
        $paymentMethod = $request->payment_method;

        if (!empty($fromDate) && !empty($toDate)) {
            $orders = UserOrder::when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
            })->when($toDate, function ($query, $toDate) {
                return $query->whereDate('created_at', '<=', Carbon::parse($toDate));
            })->when($paymentMethod, function ($query, $paymentMethod) {
                return $query->where('method', $paymentMethod);
            })->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })->when($orderStatus, function ($query, $orderStatus) {
                return $query->where('order_status', $orderStatus);
            })->where('user_id', Auth::guard('web')->user()->id)
                ->select('order_number', 'billing_fname', 'billing_email', 'billing_number', 'billing_city', 'billing_country', 'shipping_fname', 'shipping_email', 'shipping_number', 'shipping_city', 'shipping_country', 'method', 'shipping_method', 'cart_total', 'discount', 'tax', 'shipping_charge', 'total', 'created_at', 'payment_status', 'order_status')
                ->orderBy('id', 'DESC');

            Session::put('item_orders_report', $orders->get());
            $data['total'] = $orders->sum('total') + $orders->sum('shipping_charge');
            $data['discount'] = $orders->sum('discount');
            $data['shipping_charge'] = $orders->sum('shipping_charge');
            $data['tax'] = $orders->sum('tax');

            $data['orders'] = $orders->paginate(10);
        } else {
            Session::put('item_orders_report', []);
            $data['orders'] = [];
        }

        $data['onPms'] = UserPaymentGeteway::where('status', 1)->where('user_id', Auth::guard('web')->user()->id)->get();
        $data['offPms'] = UserOfflineGateway::where('item_checkout_status', 1)->where('user_id', Auth::guard('web')->user()->id)->get();


        return view('user.item.order.report', $data);
    }

    public function exportReport()
    {
        $orders = Session::get('item_orders_report');
        if (empty($orders) || count($orders) == 0) {
            Session::flash('warning', __('There are no orders available to export'));
            return back();
        }
        return Excel::download(new PorductOrderExport($orders), 'product-orders.csv');
    }
}
