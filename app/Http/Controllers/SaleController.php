<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Models\Country;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\Product;
use App\Models\IncomingStock;
use App\Models\OutgoingStock;
use App\Models\Purchase;
use App\Models\WareHouse;
use App\Models\Customer;
use App\Models\Order;

class SaleController extends Controller
{
    
    public function allSale()
    {
        $sales = Sale::where('parent_id', null)->get();
        
        return view('pages.sales.allSale', compact('sales'));
    }

    
    public function addSale()
    {
        $products = Product::all();
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        $sale_code = 'kps-' . date("Ymd") . '-'. date("his");
        
        return view('pages.sales.addSale', compact('products', 'customers', 'sale_code', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function addSalePost(Request $request)
    {
        $request->validate([
            'customer' => 'required|string',
            'warehouse' => 'required|string',
            'sale_date' => 'required|string',
            'product' => 'required|string',
            'sale_status' => 'required|string',
            'payment_status' => 'required|string',
            'note' => 'nullable|string',
            'attached_document' => 'nullable|mimes:jpg, jpeg, png, pdf, csv, docx, xlsx, txt, gif, svg, webp|max:2048',
        ]);

        $data = $request->all();
        $sale_code = 'kps-' . date("Ymd") . '-'. date("his");

        $imageName = '';
        if ($request->attached_document) {
            //image
            $imageName = time().'.'.$request->attached_document->extension();
            //store products in folder
            $request->attached_document->storeAs('sale', $imageName, 'public');
        }

        //save Order
        $order = new Order();
        $order->source_type = 'sale_module';
        $order->products = serialize($data['product_id']);
        $order->save();

        foreach ($data['product_id'] as $key => $id) {
            if(!empty($id)){
                $parent_sale = Sale::where('sale_code', $data['sale_code']);

                //update product price
                Product::where(['id'=>$id])->update(['price'=>$data['unit_price'][$key]]);

                //update product stock
                $outgoingStock = new OutgoingStock();
                $outgoingStock->product_id = $id;
                $outgoingStock->order_id = $order->id;
                $outgoingStock->quantity_removed = $data['product_qty'][$key];
                $outgoingStock->amount_accrued = $data['product_qty'][$key] * $data['unit_price'][$key];
                $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
                $outgoingStock->quantity_returned = 0; //by default
                $outgoingStock->created_by = 1;
                $outgoingStock->status = 'true';
                $outgoingStock->save();
                
                $sale = new Sale();
                $sale->sale_code = $data['sale_code'];
                $sale->parent_id = $parent_sale->exists() ? $parent_sale->first()->id : null;
                $sale->customer_id = $data['customer'];
                $sale->warehouse_id = $data['warehouse'];
                $sale->sale_date = $data['sale_date'];

                $sale->product_id = $id;

                $sale->product_qty_sold = $data['product_qty'][$key];
                $sale->outgoing_stock_id = $outgoingStock->id;
                $sale->amount_due = $data['product_qty'][$key] * $data['unit_price'][$key];
                $sale->amount_paid = 0;

                $sale->payment_status = $data['payment_status'];
                $sale->note = !empty($data['note']) ? $data['note'] : null;

                $sale->attached_document = $imageName == '' ? null : $imageName;

                $sale->created_by = 1;
                $sale->status = $data['sale_status'];

                $sale->save();

                
            }
        }

        return back()->with('success', 'Sale Order Added Successfully');

        
    }

    public function singleSale($unique_key)
    {
        return '123';
    }

    public function editSale($unique_key)
    {
        $sale = Sale::where('unique_key', $unique_key);
        // $sale_code = $sale->first()->sale_code;
        if(!$sale->exists()){
            abort(404);
        }
        $sale_code = $sale->first()->sale_code;
        $products = Product::all();
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        $sales = Sale::where('sale_code', $sale_code)->get();
        $sale = $sale->first();
        
        return view('pages.sales.editSale', compact('products', 'customers', 'warehouses', 'sale', 'sales'));
    }

    public function editSalePost(Request $request, $unique_key)
    {
        $sale = Sale::where('unique_key', $unique_key);
        if(!$sale->exists()){
            abort(404);
        }
        $sale = $sale->first();
        $request->validate([
            'customer' => 'required|string',
            'warehouse' => 'required|string',
            'sale_date' => 'required|string',
            'product' => 'nullable|string',
            'sale_status' => 'required|string',
            'payment_status' => 'required|string',
            'note' => 'nullable|string',
            'attached_document' => 'nullable|mimes:jpg, jpeg, png, pdf, csv, docx, xlsx, txt, gif, svg, webp|max:2048',
        ]);


        $data = $request->all();

        $sale_code = $sale->sale_code;

        //file
        $imageName = '';
        if ($request->attached_document) {
            $oldImage = $sale->attached_document; //1.jpg
            if(Storage::disk('public')->exists('sale/'.$oldImage)){
                Storage::disk('public')->delete('sale/'.$oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time().'.'.$request->attached_document->extension();
            //store file in folder
            $request->attached_document->storeAs('sale', $imageName, 'public');
            //$sale->attached_document = $imageName;
        }
        //

        //update order
        $order_id = $sale->outgoingStock->order_id;
        $order = Order::find($order_id);
        $order->source_type = 'sale_module';
        $order->products = serialize($data['product_id']);
        $order->save();

        foreach ($data['product_id'] as $key => $id) {
            if(!empty($id)){
                
                $parent_sale = Sale::where('sale_code', $sale->sale_code);

                Product::where(['id'=>$id])->update(['price'=>$data['unit_price'][$key]]);

                $existing_sale = $parent_sale->where('product_id', $id);
                if ($existing_sale->exists()) {
                    
                    $existing_sale->update([
                        'customer_id' => $data['customer'],
                        'warehouse_id' => $data['warehouse'],
                        'sale_date' => $data['sale_date'],
                        'product_id' => $id,
                        'product_qty_sold' => $data['product_qty'][$key],
                        'amount_due' => $data['product_qty'][$key] * $data['unit_price'][$key],
                        'amount_paid' => 0,

                        'payment_status' => $data['payment_status'],
                        'note' => !empty($data['note']) ? $data['note'] : null,

                        'attached_document' => $imageName == '' ? null : $imageName,
                        'created_by' => 1,
                        'status' => $data['sale_status'],
                    ]);
                    OutgoingStock::where(['id'=>$data['outgoing_stock_id'][$key]])->update([
                     'product_id' => $id,
                     'quantity_removed' => $data['product_qty'][$key],
                     'amount_accrued' => $data['product_qty'][$key] * $data['unit_price'][$key],
                     'reason_removed' => 'as_order_firstphase',
                     'created_by' => 1,
                     'status' => 'true'
                    ]);
                } else {
                    
                    $imageName = '';
                    if ($request->attached_document) {
                        //image
                        $imageName = time().'.'.$request->attached_document->extension();
                        //store products in folder
                        $request->attached_document->storeAs('sale', $imageName, 'public');
                    }

                    //update product stock
                    $outgoingStock = new OutgoingStock();
                    $outgoingStock->product_id = $id;
                    $outgoingStock->order_id = $order->id;
                    $outgoingStock->quantity_removed = $data['product_qty'][$key];
                    $outgoingStock->amount_accrued = $data['product_qty'][$key] * $data['unit_price'][$key];
                    $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
                    $outgoingStock->quantity_returned = 0; //by default
                    $outgoingStock->created_by = 1;
                    $outgoingStock->status = 'true';
                    $outgoingStock->save();
                    
                    //incase of new added
                    $sale = new Sale();
                    $sale->sale_code = $sale_code;
                    $sale->parent_id = $parent_sale->exists() ? $parent_sale->first()->id : null;
                    $sale->customer_id = $data['customer'];
                    $sale->warehouse_id = $data['warehouse'];
                    $sale->sale_date = $data['sale_date'];

                    $sale->product_id = $id;

                    $sale->product_qty_sold = $data['product_qty'][$key];
                    $sale->outgoing_stock_id = $outgoingStock->id;
                    $sale->amount_due = $data['product_qty'][$key] * $data['unit_price'][$key];
                    $sale->amount_paid = 0;

                    $sale->payment_status = $data['payment_status'];
                    $sale->note = !empty($data['note']) ? $data['note'] : null;

                    $sale->attached_document = $imageName == '' ? null : $imageName;

                    $sale->created_by = 1;
                    $sale->status = $data['sale_status'];

                    $sale->save();;

                    
                }
                

                
            }
        }

        return back()->with('success', 'Sales Updated Successfully');
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
