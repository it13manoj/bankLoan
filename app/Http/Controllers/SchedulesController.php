<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchedulesRequest;
use App\Http\Requests\UpdateSchedulesRequest;
use Illuminate\Http\Request;
use App\Models\LoanDetails;
use Illuminate\Support\Str;
use App\Models\Schedules;
use App\Models\Loans;
use App\Models\User;
use App\Models\Roles;
use Auth;

class SchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreSchedulesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSchedulesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $results = Loans::where('id',$request->id)->first();
        if($results){
             $results->EMI = Schedules::where('loan_id',$results->id)->get();
                foreach($results->EMI as $row){
                    if($row->status==0){
                        $row->status = "Due";
                    }elseif($row->status=='1'){
                        $row->status= "Paid";
                    }
            }
             $this->success('success', ["results"=>$results]);
        }else{
            $this->error('error', "error !");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedules $schedules)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSchedulesRequest  $request
     * @param  \App\Models\Schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $loanId = Schedules::where('loan_id',$request->id)->where('status','0')->min('id');
        $maxid = Schedules::where('loan_id',$request->id)->where('status','1')->max('id');
        if( $loanId ){
        $loan = Schedules::find($loanId);
        if($loan->pay_date && $maxid > 0){
                $from = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d',strtotime(Schedules::find($maxid)->pay_date)));
            }elseif( $maxid > 0 ){
                $from = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d',strtotime(Schedules::find($maxid)->pay_date)));
            }else{
                $from = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d',strtotime($loan->created_at)));
            }
            $to = \Carbon\Carbon::createFromFormat('Y-m-d',date('Y-m-d'));
            $diff_in_days = $to->diffInDays($from);
            if($diff_in_days == 7 ){
                $interest = 0;
                $this->PayCalculator($loan,$interest,$request);
            }if($diff_in_days > 7){
                $interest = ($diff_in_days-7)*500;
                $this->PayCalculator($loan,$interest,$request);
            }else{
                // print_r($from);
                        $daysToAdd = 7;
                        $date = $from->addDays($daysToAdd);
                        $this->error('error', ["message"=>'Your due date is '. date('d-m-Y',strtotime($date))]);
            }
        }else{
            $this->success('success', "Your EMI has completed!");
        }
    }

 function PayCalculator($loan,$interest,$request){
                $loan->status='1';
                $loan->interest=$interest;
                $loan->emi=Loans::find($request->id)->emi;
                $loan->pay_date=date('Y-m-d');
                    if($loan->update()){
                        $this->success('success',"Your EMI Paid Successfully");
                    }else{
                            $this->error('error', "error !");
                    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedules $schedules)
    {
        //
    }
}
