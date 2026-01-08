<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_number' , 'description' , 'amount' , 'admin_id'];

    public static function booted()
    {
        static::creating(function (Invoice $invoice){
            $invoice->invoice_number = Invoice::getNextNumberInvoice() ;
            $invoice->admin_id = 2 ;
        }) ;
    }


    public static function getNextNumberInvoice()
    {
        $year = Carbon::now()->year ;
        $latest_invoice = Invoice::whereYear('created_at' , $year )->max('invoice_number') ;
        if($latest_invoice){
            return $latest_invoice + 1 ;
        }
        return $year . '0001' ;
    }


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id') ;
    }
}
