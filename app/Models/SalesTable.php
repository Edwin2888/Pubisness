<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTable extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_sale';

    public function detalle(){
        return $this->belongsTo(SalesTableDetail::class,'id_sale','id_sale');
    }
    public function statusn(){
        return $this->belongsTo(StatusSale::class,'status','id_status');
    }
}
