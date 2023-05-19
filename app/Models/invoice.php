<?php

namespace App\Models;

use App\Models\sections;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class invoice extends Model
{
use SoftDeletes;
    use HasFactory;

    protected $dates = ['deleted_at'];
    protected $fillable = [
    'invoice_number',
    'invoice_Date',
    'Due_date',
    'product',
    'section_id','section_name',
    'Amount_collection',
    'Amount_Commission',
    'Discount',
    'Value_VAT',
    'Rate_VAT',
    'Total',
    'Status',
    'Value_Status',
    'note',
    'Payment_Date',
];



    public function section()
    {

    return $this->belongsTo(sections::class);
    }
}
