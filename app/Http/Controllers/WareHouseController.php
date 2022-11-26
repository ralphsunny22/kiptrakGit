<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WareHouse;
use App\Models\User;
use App\Models\Country;

class WareHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allWarehouse()
    {
        $warehouses = WareHouse::all();
        return view('pages.warehouses.allWarehouse', compact('warehouses'));
    }

    //add
    public function addWarehouse()
    {
        $agents = User::where('type', 'agent')->get();
        $countries = Country::all();
        return view('pages.warehouses.addWarehouse', compact('agents', 'countries'));
    }

    //addpost
    public function addWarehousePost(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
        ]);

        $data = $request->all();
        $warehouse = new WareHouse();
        $warehouse->agent_id = !empty($data['agent_id']) ? $data['agent_id'] : null;
        $warehouse->name = $data['name'];
        $warehouse->city = !empty($data['city']) ? $data['city'] : null;
        $warehouse->state = !empty($data['state']) ? $data['state'] : null;
        $warehouse->country_id = !empty($data['country']) ? $data['country'] : null;
        $warehouse->address = !empty($data['address']) ? $data['address'] : null;
        $warehouse->created_by = 1;
        $warehouse->status = 'true';
        $warehouse->save();

        return back()->with('success', 'Warehouse Added Successfully');
    }

    public function singleWarehouse($unique_key)
    {
        $warehouse = WareHouse::where('unique_key', $unique_key)->first();
        if(!isset($warehouse)){
            abort(404);
        }
        return view('pages.warehouses.singleWarehouse', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editWarehouse($unique_key)
    {
        $warehouse = WareHouse::where('unique_key', $unique_key)->first();
        if(!isset($warehouse)){
            abort(404);
        }
        $agents = User::where('type', 'agent')->get();
        $countries = Country::all();
        return view('pages.warehouses.editWarehouse', compact('warehouse', 'agents', 'countries'));
    }

    
    public function editWarehousePost(Request $request, $unique_key)
    {
        $warehouse = WareHouse::where('unique_key', $unique_key)->first();
        if(!isset($warehouse)){
            abort(404);
        }
        $request->validate([
            'name' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
        ]);

        $data = $request->all();
        
        $warehouse->agent_id = !empty($data['agent_id']) ? $data['agent_id'] : null;
        $warehouse->name = $data['name'];
        $warehouse->city = !empty($data['city']) ? $data['city'] : null;
        $warehouse->state = !empty($data['state']) ? $data['state'] : null;
        $warehouse->country_id = !empty($data['country']) ? $data['country'] : null;
        $warehouse->address = !empty($data['address']) ? $data['address'] : null;
        $warehouse->created_by = 1;
        $warehouse->status = 'true';
        $warehouse->save();

        return back()->with('success', 'Warehouse Updated Successfully');
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
