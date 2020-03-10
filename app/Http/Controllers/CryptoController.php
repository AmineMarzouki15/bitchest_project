<?php

namespace App\Http\Controllers;

use App\Repositories\CryptoRepository;
use Illuminate\Http\Request;

class CryptoController extends Controller
{
    /**
     * @var CryptoRepository
     */
    private $cryptoRepository;

    public function __construct(CryptoRepository $cryptoRepository)
    {
        $this->middleware('permission:crypto-list', ['only' => ['index']]);
        $this->cryptoRepository = $cryptoRepository;
    }

    public function index() {
        $cryptos  =   $this->cryptoRepository->getCurrentAllCrypto();
        return view('crypto.index', ['cryptos' => $cryptos]);
    }

    public function detailCrypto($id) {
        if ($id) {
            $crypto =   $this->cryptoRepository->getCryptoInformation($id);
            if ($crypto) {
                return view('crypto.show', ['crypto' => $crypto]);
            }
            return redirect()->back();
        }
    }
}
