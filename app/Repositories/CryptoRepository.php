<?php


namespace App\Repositories;


use App\Model\Crypto;

class CryptoRepository
{

    /**
     * @var Crypto
     */
    private $crypto;

    public function __construct(Crypto $crypto)
    {
        $this->crypto = $crypto;
    }

    public function getAllCrypto() {
        return $this->crypto::all();
    }

    public function getCurrentAllCrypto()
    {
        return $this->crypto::with(['cours' => function ($query) { $query->orderBy('created_at', 'DESC'); }])->get();
    }

    public function getCryptoInformation($id)
    {
        return $this->crypto::where(['id' => $id])->with(['cours' => function ($query) { $query->orderBy('created_at', 'DESC'); }])->first();
    }

}
