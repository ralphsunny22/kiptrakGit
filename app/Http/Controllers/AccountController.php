<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    public function allAccount()
    {
        $accounts = Account::all();
        return view('pages.accounts.allAccount', compact('accounts'));
    }

    public function addAccount()
    {
        $account_no = 'kpa-' . date("Ymd") . '-'. date("his");
        return view('pages.accounts.addAccount', compact('account_no'));
    }

    public function addAccountPost(Request $request)
    {
        $request->validate([
            'account_no' => 'required|string|unique:accounts',
            'account_name' => 'required|string',
            'initial_balance' => 'nullable',
            'amount' => 'required|numeric',
            'note' => 'required|string',
        ]);

        $data = $request->all();
        $account = new Account();
        $account->account_no = $data['account_no'];
        $account->name = $data['account_name'];
        $account->initial_balance = !empty($data['initial_balance']) ? $data['initial_balance'] : 0;
        $account->amount_added = $data['amount_added'];
        $account->total_balance = 0;
        $account->note = !empty($data['note']) ? $data['note'] : null;
        $account->created_by = 1;
        $account->status = 'true';
        $account->save();

        return back()->with('success', 'Account Added Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAccountAjaxPost(Request $request)
    {
        $data = $request->all();
        $account = new Account();
        $account->account_no = $data['account_no'];
        $account->name = $data['name'];
        
        if ($data['initial_balance'] == '' || $data['initial_balance'] == null) {
            $account->initial_balance = null;
        } else {
            $account->initial_balance = $data['initial_balance'];
        }

        $account->total_balance = 0;

        if ($data['note'] == '' || $data['note'] == null) {
            $account->note = null;
        } else {
            $account->note = $data['note'];
        }
         
        $account->created_by = 1;
        $account->status = 'true';
        $account->save();

        $data['account'] = $account;

        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editAccount($unique_key)
    {
        $account = Account::where('unique_key', $unique_key)->first();
        if (!isset($account)) {
            abort(404);
        }

        return view('pages.accounts.editAccount', compact('account'));
    }

    public function editAccountPost(Request $request, $unique_key)
    {
        $account = Account::where('unique_key', $unique_key)->first();
        if (!isset($account)) {
            abort(404);
        }

        $request->validate([
            'account_no' => 'required|string',
            'account_name' => 'required|string',
            'initial_balance' => 'nullable',
            'amount' => 'required|numeric',
            'note' => 'required|string',
        ]);

        $data = $request->all();
        
        $account->account_no = $data['account_no'];
        $account->name = $data['account_name'];
        $account->initial_balance = !empty($data['initial_balance']) ? $data['initial_balance'] : 0;
        $account->amount_added = $data['amount'];
        $account->total_balance = 0;
        $account->note = !empty($data['note']) ? $data['note'] : null;
        $account->created_by = 1;
        $account->status = 'true';
        $account->save();

        return back()->with('success', 'Account Updated Successfully');
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
