<?php


namespace App\Model;


use App\Lib\Crypto as CryptoLib;
use Illuminate\Database\Eloquent\Model;

class Crypto extends Model
{

    protected $fillable = ['name', 'icon_name'];

    protected $appends = ['current_cour'];

    public function cours() {
        return $this->hasMany(Cour::class);
    }

    public function getCurrentCourAttribute() {
        $cours  =   $this->cours()->orderBy('created_at', 'DESC')->get();
        return $cours[0]->value;
    }
}
