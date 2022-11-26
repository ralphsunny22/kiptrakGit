<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Models\Order;
use App\Models\OrderLabel;
use App\Models\OrderProduct;
use App\Models\OrderBump;
use App\Models\UpSell;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allOrders()
    {
        $orders = Order::all();
        return view('pages.orders.allOrders', compact('orders'));
    }

    public function addOrder()
    {
        return view('pages.orders.addOrder');
    }

    //addOrder POST
    public function addOrderPost(Request $request)
    {
        $request->validate([
            'order_heading' => 'required|string',
            'order_subheading' => 'required|string',
            // 'product_id' => 'required|string',
            'orderbump_heading' => 'nullable|string',
            'orderbump_subheading' => 'nullable|string',
            'upsell_heading' => 'nullable|string',
            'upsell_subheading' => 'nullable|string',
            'staff_assigned' => 'required|string',
            'thankyou_heading' => 'required|string',
            'thankyou_subheading' => 'required|string',
        ]);
        
        $data = $request->all();
        
        //save OrderLabel
        $orderLabel = new OrderLabel();
        $orderLabel->order_heading = $data['order_heading'];
        $orderLabel->order_subheading = $data['order_subheading'];
        
        //if orderbump is added
        if( ($data['orderbump_status']=='true') && (!empty($data['select_orderbump_product'])) ){
            $orderLabel->orderbump_heading = $data['orderbump_heading'];
            $orderLabel->orderbump_subheading = $data['orderbump_subheading'];
        }
        //if upsell is added
        if( ($data['upsell_status']=='true') && (!empty($data['select_upsell_product'])) ){
            $orderLabel->upsell_heading = $data['upsell_heading'];
            $orderLabel->upsell_subheading = $data['upsell_subheading'];
        }
        
        //customer label
        $orderLabel->customer_firstname_label = $data['firstname'];
        $orderLabel->customer_lastname_label = $data['lastname'];
        $orderLabel->customer_phone_label = $data['phone'];
        $orderLabel->customer_email_label = $data['email'];
        $orderLabel->customer_state_label = $data['state'];
        $orderLabel->customer_address_label = $data['address'];

        //thank you
        $orderLabel->thankyou_heading = $data['thankyou_heading'];
        $orderLabel->thankyou_subheading = $data['thankyou_subheading'];

        $orderLabel->save();

        //--------------------------------------
        //save products - OrderBumps & UpSell
        $orderbump_expected_sum = '';
        if( ($data['orderbump_status']=='true') && (!empty($data['select_orderbump_product'])) ){
            $orderBump = new OrderBump();

            //explode orderbump product
            $select_orderbump_product = explode('-', $data['select_orderbump_product']);
            $orderbump_product_id = (int) json_decode(json_encode($select_orderbump_product[0]));
            // $orderbump_product_quantity = $select_orderbump_product[1];
            $orderbump_product_unitprice = (int) json_decode(json_encode($select_orderbump_product[2])); //array to string, then int

            $orderbump_expected_sum = $data['orderbump_product_quantity'] * $orderbump_product_unitprice;

            $orderBump->product_id = $orderbump_product_id;
            $orderBump->orderbump_discount = !empty($data['orderbump_discount']) ? $data['orderbump_discount'] : null;
            $orderBump->product_expected_quantity_to_be_sold = $data['orderbump_product_quantity'];
            $orderBump->product_expected_amount = $orderbump_expected_sum;
            $orderBump->save();
        }

        $upsell_expected_sum = '';
        if( ($data['upsell_status']=='true') && (!empty($data['select_upsell_product'])) ){
            $upSell = new UpSell();

            //explode upsell product
            $select_upsell_product = explode('-', $data['select_upsell_product']);
            $upsell_product_id = (int) json_decode(json_encode($select_upsell_product[0]));
            // $upsell_product_quantity = $select_upsell_product[1];
            $upsell_product_unitprice = (int) json_decode(json_encode($select_upsell_product[2])); //array to string, then int

            $upsell_expected_sum = $data['upsell_product_quantity'] * $upsell_product_unitprice;

            $upSell->product_id = $upsell_product_id;
            $upSell->upsell_discount = !empty($data['upsell_discount']) ? $data['upsell_discount'] : null;
            $upSell->product_expected_quantity_to_be_sold = $data['upsell_product_quantity'];
            $upSell->product_expected_amount = $upsell_expected_sum;
            $upSell->save();
        }
        //-------------------------------------------------------------------------

        //-----------------------------------
        //save Order
        $order = new Order();
        //if orderbump is added
        if( ($data['orderbump_status']=='true') && (!empty($data['select_orderbump_product'])) ){
            $order->orderbump_id = $orderBump->id;
        }
        //if upsell is added
        if( ($data['upsell_status']=='true') && (!empty($data['select_upsell_product'])) ){
            $order->upsell_id = $upSell->id;
        }
        
        $order->staff_assigned_id = $data['staff_assigned'];
        $order->order_label_id = $orderLabel->id;
        // $order->amount_expected = '35050'; //updated after OrderProduct
        $order->save();

        //update orderLabel
        $orderLabel->update(['order_id'=>$order->id]);

        //update orderBump & upSell
        if( ($data['orderbump_status']=='true') && (!empty($data['select_orderbump_product'])) ){
            $orderBump->update(['order_id'=>$order->id]);
        }
        if( ($data['upsell_status']=='true') && (!empty($data['select_upsell_product'])) ){
            $upSell->update(['order_id'=>$order->id]);
        }
        
        //save OrderProduct, first-phase products only
        
        foreach ($data['product_quantity'] as $key => $qty) {
            if (!empty($qty)) {
                $string = $data['product_id'][$key]; //'1-1000-10
                $product_id = substr($string, 0, strpos($string, '-')); //before first '-'
                $product_unitprice = substr($string, strrpos($string, '-' )+1); //before last '-'
                //$val = '1-10-1000'
                // $product_val = explode('-', $val);
                // $product_id = (int) json_decode(json_encode($product_val[0])); //array to string
                // $product_id = $product_val[0];
                // $product_quantity = $product_val[1];
                // $product_unitprice = '5000';

                $orderProduct = new OrderProduct();
                $orderProduct->product_id = $product_id; //product_id

                $orderProduct->discount = !empty($data['product_discount'][$key]) ? $data['product_discount'][$key] : null;
                $orderProduct->product_expected_quantity_to_be_sold = $qty;
                $orderProduct->product_expected_amount = (int) $qty * $product_unitprice; //discount will be added later
                
                $orderProduct->order_id = $order->id;
                $orderProduct->save();
            }
            
            // return '123x';
        }
        
        
        //update Order
        $expected_sum = OrderProduct::where('order_id', $order->id)->sum('product_expected_amount');
        if($orderbump_expected_sum !== ''){
            $expected_sum = $expected_sum + $orderbump_expected_sum;
        }
        if($upsell_expected_sum !== ''){
            $expected_sum = $expected_sum + $upsell_expected_sum;
        }
        $order->update(['amount_expected'=>$expected_sum]);

        return back()->with('success', 'Order Form Created Successfully');

    }

    //orderForm
    public function singleOrder($unique_key)
    {
        $order = Order::where('unique_key', $unique_key);
        if(count($order->get()) !== 1) {
            abort(404);
        }

        $order = $order->first();
        $url = env('APP_URL').'/'.$order->url;
        return view('pages.orders.singleOrder', compact('url', 'order'));
    }

    //orderForm
    public function customerOrderForm($unique_key)
    {
        $order = Order::where('unique_key', $unique_key);
        if(count($order->get()) !== 1) {
            abort(404);
        }

        $order = $order->first();
        $url = env('APP_URL').'/'.$order->url;
        return view('pages.orders.customerOrderForm');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
