<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Product;
use App\Models\IncomingStock;
use App\Models\OutgoingStock;
use App\Models\Country;

class ProductController extends Controller
{
    public function addProduct()
    {
        // $pro = Product::find('1');
        // return unserialize($pro->features) == [null] ? 'noting' : 'yes';
        // $currencies = array(
        //  array("nationality" => "Nigerian", "currency" => "Naira", "symbol" => "₦",),
        //  array("nationality" => "Ghanian", "currency" => "Cedis", "symbol" => "GH₵",),
        //  array("nationality" => "Kenyan", "currency" => "Shilling", "symbol" => "KES",),
        //  array("nationality" => "US", "currency" => "Dollar", "symbol" => "$",),
        //  array("nationality" => "UK", "currency" => "Pound", "symbol" => "£",),
        // );

        $units = array(
            array("name" => "Kilogram", "symbol" => "Kg",),
            array("name" => "Gram", "symbol" => "g",),
            array("name" => "Milligram", "symbol" => "mg",),
            array("name" => "Volume", "symbol" => "Vol",),
            array("name" => "Litre", "symbol" => "L",),
            array("name" => "Centilitre", "symbol" => "Cl",),
            array("name" => "Sachet", "symbol" => "Sachet",),
            array("name" => "Container", "symbol" => "Container",),
            array("name" => "Bottle", "symbol" => "Bottle",),
            array("name" => "Packet", "symbol" => "Packet",),
            array("name" => "Item", "symbol" => "Item",),
        );
        $countries = Country::all();

        return view('pages.products.addProduct', compact('countries', 'units'));
    }

    public function addProductPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|numeric',
            // 'color' => 'nullable|string',
            // 'size' => 'nullable|string',
            'currency' => 'required',
            'price' => 'required|numeric',
            'code' => 'nullable|string|unique:products',
            // 'features' => 'nullable|array',
            //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
        ]);
        
        $code = $random = rand(1000, 9000);

        $data = $request->all();
        $product = new Product();
        $product->name = $data['name'];
        // $product->quantity = $data['quantity']; //handled by inComingStocks
        $product->color = !empty($data['color']) ? $data['color'] : null;
        $product->size = !empty($data['size']) ? $data['size'] : null;
        $product->country_id = $data['currency']; //country_id
        $product->price = $data['price'];

        if (empty($data['code'])) {
            $count = Product::count() + 1;
            $product->code = $count.'PRD'.$code;
        }else{
            $product->code = $data['code'];
        }
        
        $product->features = !empty($data['features']) ? serialize($data['features']) : null;
    
        $product->warehouse_id =  !empty($data['warehouse_id']) ? $data['warehouse_id'] : null;
        $product->created_by = 1;
        $product->status = 'true';
        
        //image
        $imageName = time().'.'.$request->image->extension();
        //store products in folder
        $request->image->storeAs('products', $imageName, 'public');
        $product->image = $imageName;
        $product->save();
        
        //incomingstocks
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = $product->id;
        $incomingStock->quantity_added = $data['quantity'];
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = '1';
        $incomingStock->status = 'true';
        $incomingStock->save();

        return back()->with('success', 'Product Created Successfully');

    }

    //allProducts
    public function allProducts()
    {
        $products = Product::all();
        return view('pages.products.allProducts', compact('products'));
    }

    //singleProduct
    public function singleProduct($unique_key)
    {
        $product = Product::where('unique_key', $unique_key)->first();
        if(!isset($product)){
            abort(404);
        }
        
        $currency_symbol = substr($product->country_id, strrpos($product->country_id, '-') + 1);
        $features = unserialize($product->features) == [null] ? '' : unserialize($product->features);

        //stock_available
        $stock_available = $product->stock_available();
        
        return view('pages.products.singleProduct', compact('product', 'currency_symbol', 'features', 'stock_available'));
    }

    //editProduct
    public function editProduct($unique_key)
    {
        $product = Product::where('unique_key', $unique_key)->first();
        if(!isset($product)){
            abort(404);
        }
        // $currencies = array(
        //     array("nationality" => "Nigerian", "currency" => "Naira", "symbol" => "₦",),
        //     array("nationality" => "Ghanian", "currency" => "Cedis", "symbol" => "GH₵",),
        //     array("nationality" => "Kenyan", "currency" => "Shilling", "symbol" => "KES",),
        //     array("nationality" => "US", "currency" => "Dollar", "symbol" => "$",),
        //     array("nationality" => "UK", "currency" => "Pound", "symbol" => "£",),
        // );
        //$currency_nationality = substr($product->country_id, 0, strpos($product->country_id, '-')); //before first '-'
        //$currency_symbol = substr($product->country_id, strrpos($product->country_id, '-') + 1);

        $currency_nationality = $product->country->name;
        $currency_symbol = $product->country->symbol;

        $countries = Country::all();
        $features = unserialize($product->features) == [null] ? '' : unserialize($product->features);

        //stock_available
        $stock_available = $product->stock_available();

        return view('pages.products.editProduct', compact('product', 'currency_symbol', 'features',
        'currencies', 'currency_nationality', 'stock_available'));
    }

    public function editProductPost(Request $request, $unique_key)
    {
        $product = Product::where('unique_key', $unique_key)->first();
        if(!isset($product)){
            abort(404);
        }
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|numeric',
            // 'color' => 'nullable|string',
            // 'size' => 'nullable|string',
            'currency' => 'required',
            'price' => 'required|numeric',
            'code' => 'nullable|string',
            // 'features' => 'nullable|array',
            //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
        ]);
    
        $data = $request->all();
        $product->name = $data['name'];
        // $product->quantity = $data['quantity'];
        $product->color = !empty($data['color']) ? $data['color'] : null;
        $product->size = !empty($data['size']) ? $data['size'] : null;
        $product->country_id = $data['currency'];
        $product->price = $data['price'];
        $product->code = !empty($data['code']) ? $data['code'] : null;

        $product->features = !empty($data['features']) ? serialize($data['features']) : null;
    
        $product->warehouse_id =  !empty($data['warehouse_id']) ? $data['warehouse_id'] : null;
        $product->created_by = '1';
        $product->status = 'true';
        
        //image
        if ($request->image) {
            $oldImage = $product->image; //1.jpg
            if(Storage::disk('public')->exists('products/'.$oldImage)){
                Storage::disk('public')->delete('products/'.$oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time().'.'.$request->image->extension();
            //store products in folder
            $request->image->storeAs('products', $imageName, 'public');
            $product->image = $imageName;
        }
        
        //return $oldImage;
        //Storage::disk('public')->delete($oldImagePath);
        $product->save();

        if(!empty($data['quantity']) && $data['quantity'] !== 0)
        {
            //incomingStock
            if ($data['quantity'] > 0) {
                //incomingstocks
                $incomingStock = new IncomingStock();
                $incomingStock->product_id = $product->id;
                $incomingStock->quantity_added = $data['quantity'];
                $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
                $incomingStock->created_by = '1';
                $incomingStock->status = 'true';
                $incomingStock->save();
            }

            //outgoingStock
            if ($data['quantity'] < 0) {
                $outgoingStock = new OutgoingStock();
                $outgoingStock->product_id = $product->id;
                $outgoingStock->quantity_removed = abs($data['quantity']); //stay +ve
                $outgoingStock->reason_removed = 'as_administrative'; //as_order, as_expired, as_damaged, as_administrative
                $outgoingStock->quantity_returned = 0; //by default
                $outgoingStock->created_by = '1';
                $outgoingStock->status = 'true';
                $outgoingStock->save();
            }
        }
        
        
        return back()->with('success', 'Product Updated Successfully');

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
