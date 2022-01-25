<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    //get data
    public function getProduct($id)
    {
        $product = DB::table('products') 
                    -> where('category_id', $id)
                    -> get();
        return response() -> json($product);
    }

    // order done
    public function orderDone(Request $request)
    {
        $validateData = $request -> validate([
            'customer_id'      => 'required',
            'payby'      => 'required',
        ]);

        $data = array();
        $data['customer_id']   = $request -> customer_id;
        $data['qty']   = $request -> qty;
        $data['subtotal']   = $request -> subtotal;
        $data['vat']   = $request -> vat;
        $data['total']   = $request -> total;
        $data['pay']   = $request -> pay;
        $data['due']   = $request -> due;
        $data['payby']   = $request -> payby;
        $data['order_date']   = date('d/m/Y');
        $data['order_month']   = date('F');
        $data['order_year']   = date('Y');

        $order_id = DB::table('orders') -> insertGetId($data);
        
        $contents = DB::table('pos') -> get();
        $orderDetials = array();
        foreach( $contents as $content){
            $orderDetials['order_id'] =  $order_id;
            $orderDetials['product_id'] =  $content -> pro_id;
            $orderDetials['product_quantity'] = $content -> pro_quantity;
            $orderDetials['product_price'] = $content -> pro_price;
            $orderDetials['sub_total'] = $content -> sub_total;

            DB::table('order_details') -> insert($orderDetials);

            DB::table('products')
                -> where('id',$content -> pro_id)
                -> update(['product_quantity' => DB::raw('product_quantity - ' . $content -> pro_quantity)]);
        }
        DB::table('pos') -> delete();
        return response('Done');
    }

    // serach by date
    public function searchOrderDate(Request $request)
    {
        $orderdate = $request -> date;

        $newdate = new DateTime($orderdate);
        $done  = $newdate -> format('d/m/Y');

        $order = DB::table('orders')
                -> join('customers', 'orders.customer_id', 'customers.id')
                -> select('customers.name', 'orders.*')
                -> where('orders.order_date', $done)
                -> get();
        return response() -> json($order);
    }

    // today sell
    public function todaySell()
    {
        $date = date('d/m/Y');
        
        $sell = DB::table('orders') -> where('order_date',$date) -> sum('total');
        return response() -> json($sell);
    }
    // today income
    public function todayIncome()
    {
        $date = date('d/m/Y');
        
        $income = DB::table('orders') -> where('order_date',$date) -> sum('pay');
        return response() -> json($income);
       
    }
    // today due
    public function todayDue()
    {
        $date = date('d/m/Y');
        
        $todayDue = DB::table('orders') -> where('order_date',$date) -> sum('due');
        return response() -> json($todayDue);
       
    }
    // today expense
    public function todayExpense()
    {
        $date = date('d/m/Y');
        
        $todayExpense = DB::table('expenses') -> where('expense_date',$date) -> sum('amount');
        return response() -> json($todayExpense);
       
    }
    // today stock Out product
    public function todayStockOut()
    {
        $product = DB::table('products') -> where('product_quantity', '<', '1') -> get();
        return response() -> json($product);
       
    }
}
