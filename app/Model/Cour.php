<?php


namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    public $guarded = [];

    public function crypto() {
        return $this->belongsTo(Crypto::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function sales() {
        return $this->hasMany(Sale::class);
    }
}
