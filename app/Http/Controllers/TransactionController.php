<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $transactions = Transaction::where("uid", $uid)->orWhere("to_uid", $uid)
        ->join("users", "transactions.uid", "users.id")
        ->leftJoin('users as to_user', 'transactions.to_uid', 'to_user.id')
        ->select("transactions.created_at", "amount",
        DB::raw("case when transction_type = 'Deposit' then 'Credit' when transction_type = 'Withdraw' then 'Debit' when transction_type = 'transfer' and transactions.uid = $uid then 'Debit' else 'Credit'  end as type"),
        DB::raw("case when transction_type = 'Transfer' and transactions.uid = $uid then concat('Transfered to ', to_user.email) when transction_type = 'Transfer' and transactions.to_uid = $uid then concat('Transfered from ', users.email) else transction_type  end as details"),
        DB::raw("case when transactions.uid = $uid then transactions.balance else transactions.to_user_balance end as balance"))
        ->paginate(5);
        return view('transaction')->with(["transactions" => $transactions, "i" => 0]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = ["uid" => Auth::user()->id ];

        if(!empty($request->input("transction_type"))) $data["transction_type"] = $request->input("transction_type");
        if(!empty($request->input("to_uid"))) $data["to_uid"] = $request->input("to_uid");
        if(!empty($request->input("amount"))) $data["amount"] = $request->input("amount");
        DB::beginTransaction();
        if($data['transction_type'] == "Deposit")
        {
            User::where("id", $data["uid"])->increment('balance', $data['amount']);
        }
        else if($data['transction_type'] == "Withdraw")
        {
            $balance = User::where("id", $data["uid"])->value('balance');
            if($balance < $data['amount'])
            {
                return redirect()->route($data['transction_type'])->withError("You don't have enough balance");
            }
            else
            {
                User::where("id", $data["uid"])->decrement('balance', $data['amount']);
            }
        }
        else if($data['transction_type'] == "Transfer")
        {
            $balance = User::where("id", $data["uid"])->value('balance');
            if($balance < $data['amount'])
            {
                return redirect()->route($data['transction_type'])->withError("You don't have enough balance");
            }
            else
            {
                User::where("id", $data["uid"])->decrement('balance', $data['amount']);
                User::where("id", $data["to_uid"])->increment('balance', $data['amount']);
            }
        }
        $data['balance'] = User::where("id", $data["uid"])->value('balance');
        if(!empty($data["to_uid"])) $data['to_user_balance'] = User::where("id", $data["to_uid"])->value('balance');
        Transaction::create($data);
        DB::commit();
        return redirect()->route($data['transction_type'])->withSuccess($data['transction_type'] . " Successfull");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
