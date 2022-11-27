<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDetails extends Model
{
    use HasFactory;


    public function getUser(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function getLoan(){
        return $this->hasOne(Loans::class,'loan_detail_id');
    }
}
