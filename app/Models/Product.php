<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_product';

    public function expense(){
        return $this->belongsTo(ExpenseType::class,'id_type_expense','id_type_expense');
    }
}
