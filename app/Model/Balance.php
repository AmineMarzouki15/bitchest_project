<?php


namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{

    public $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
