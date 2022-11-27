<?php

namespace App\Http\Controllers;

use App\Models\LoanDetails;
use App\Http\Requests\StoreLoanDetailsRequest;
use App\Http\Requests\UpdateLoanDetailsRequest;
use Illuminate\Http\Request;
use App\Models\Schedules;
use App\Models\Loans;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Roles;
use Auth;

class LoanDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data =  LoanDetails::where('user_id',Auth::user()->id)->with('getUser');
        if ($data->get()) {
            $results = $data->get();
            foreach($results as $row){
                if($row->status==0){
                    $row->status = "Pending";
                }elseif($row->status==1){
                    $row->status= "Accepted";
                }elseif($row->status==2){
                    $row->status="declined";
                }
            }
            $this->success('success', ["results"=>$results]);
        } else {
            $this->error('error', "error !");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $loanDetails = new LoanDetails();
        $loanDetails->user_id = Auth::user()->id;
        $loanDetails->contact = $request->contact;
        $loanDetails->address = $request->address;
        $loanDetails->amount = $request->amount;
        $loanDetails->term = $request->term;
        $loanDetails->status = $request->status;
        if ($loanDetails->save()) {
            $this->success('success', "Successfully applied the loan wait for verify your loan");
        } else {
            $this->error('error', "error !");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLoanDetailsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoanDetailsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoanDetails  $loanDetails
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $results = LoanDetails::with('getLoan')->with("getUser")->where('id',$request->id)->where('user_id',Auth::user()->id)->get();
            if(!$results->isEmpty()){
                foreach($results as $row){
                    if($row->status==0){
                        $row->status = "Pending";
                    }elseif($row->status==1){
                        $row->status= "Accepted";
                    }elseif($row->status==2){
                        $row->status="declined";
                    }
                }
                 $this->success('success', ["results"=>$results]);
            }else{
                $this->error('error', "error !");
            }
    }

    public function declined(Request $request){
            if(Auth::user()->role_id==1){
            $loanDetails = LoanDetails::where("id",$request->id)->first();
            $loanDetails->status ='2';
            $loanDetails->update();
            $this->success('success', "successfully declined !");
            }else{
                $this->error('error', "error !");
            }
    }

   public function listoftheloan(Request $request){
        if(Auth::user()->where('role_id','1')->first()){
        $data =  LoanDetails::with('getUser');
            if ($data->get()) {
                $results = $data->get();
                foreach($results as $row){
                    if($row->status==0){
                        $row->status = "Pending";
                    }elseif($row->status==1){
                        $row->status= "Accepted";
                    }elseif($row->status==2){
                        $row->status="declined";
                    }
                }
                $this->success('success', ["results"=>$results]);
            } else {
                $this->error('error', "error !");
            }
        }else{
            $this->error('error', "your are not Authorized !");
        }
    }

    public function close(Request $request){
            if(Auth::user()->where('role_id','1')->first()){
                $data1 = Schedules::where('loan_id',$request->id)->where('status','0')->get();
                if( sizeof($data1) == 0){
                    $data =  Loans::find($request->id);
                    $data->status='1';
                    $data->update();
                    if ($data->get()) {
                        $results = $data->get();
                        foreach($results as $row){
                            if($row->status==1){
                                $row->status = "Closed";
                            }
                        }
                    $this->success('success', ["results"=>$results]);
                    }else{
                        $this->error('error', "All EMI is not paid!");
                }
            }else{
                $this->error('error', "your are not Authorized !");
            }
        }
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoanDetails  $loanDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanDetails $loanDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLoanDetailsRequest  $request
     * @param  \App\Models\LoanDetails  $loanDetails
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLoanDetailsRequest $request, LoanDetails $loanDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoanDetails  $loanDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanDetails $loanDetails)
    {
        //
    }
}
