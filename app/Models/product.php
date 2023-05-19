<?php

namespace App\Models;

use App\Models\sections;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product extends Model
{
    use HasFactory;
    protected $fillable=['product_name','id','description','sections_id'];
    public function section()
    {

    return $this->belongsTo(sections::class);
    }
}
