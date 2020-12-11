<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_document';

    public function statusn(){
        return $this->belongsTo(StatusSale::class,'id_status','id_status');
    }
}
