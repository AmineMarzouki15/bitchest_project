<?php


namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    public $guarded = [];

    public function user() {
        $this->belongsTo(User::class);
    }

    public function cour() {
        return $this->belongsTo(Cour::class);
    }
}
