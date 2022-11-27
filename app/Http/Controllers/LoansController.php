<?php

namespace App\Http\Controllers;

use App\Models\Loans;
use App\Models\LoanDetails;
use App\Models\Schedules;
use App\Http\Requests\StoreLoansRequest;
use App\Http\Requests\UpdateLoansRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Roles;
use Auth;

class LoansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLoansRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoansRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function show(Loans $loans)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function edit(Loans $loans)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLoansRequest  $request
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function Approved(Request $request)
    {
        if(Auth::user()->role_id == 1 && LoanDetails::where("id",$request->id)->where('status','!=','1')->first()){
            $loanDetails = LoanDetails::where("id",$request->id)->first();
            $loanDetails->status ='1';
            $loanDetails->update();
            $loan = new Loans();
            $loan->user_id= $loanDetails->user_id;
            $loan->loan_detail_id = $loanDetails->id;
            $loan->account_id = Auth::user()->id.Str::random(8).date('Y').$loanDetails->id;
            $loan->amount = $loanDetails->amount;
            $loan->emi = ($loanDetails->amount)/$loanDetails->term;
            $loan->status= '0';
            $loan->save();

            for($i=0; $i<$loanDetails->term; $i++){
                $schedules = new Schedules();
                $schedules->loan_detail_id = $loanDetails->id;
                $schedules->loan_id = $loan->id;
                $schedules->emi = $loan->emi;
                $schedules->pay_date = date('Y-m-d');
                $schedules->save();
            }
                $this->success('success', "Successfully approved loan");
        }else{
            if(LoanDetails::where("id",$request->id)->where('status','=','1')->first()){
                $this->error('error', "Already Approved loan!");
            }else{
                $this->error('error', "error !");
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loans $loans)
    {
        //
    }
}
