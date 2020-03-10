<?php

namespace App\Http\Controllers;

use App\Repositories\CryptoRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{

    /**
     * @var CryptoRepository
     */
    private $cryptoRepository;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    public function __construct(CryptoRepository $cryptoRepository, PaymentRepository $paymentRepository)
    {
        $this->cryptoRepository = $cryptoRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function index() {
        $payments       =   $this->paymentRepository->getAllPayment(Auth::id());
        $arrPayments    =   $this->parseMyCryptoPayment($payments);
        return view('portefeuille.index', ['arrPayments' => $arrPayments]);
    }

    public function savePayment(Request $request) {
        $this->validate($request, [
            'quantity' => 'required',
            'crypto_id' => 'required'
        ],
            [
                'quantity.required' => 'Veuillez renseigner la quantité'
            ]
        );

        if ($request->get('quantity') > 0 ) {
            $crypto     =   $this->cryptoRepository->getCryptoInformation($request->get('crypto_id'));
            $amount     =   round(($request->get('quantity') * $crypto->current_cour), 2);

            if ($amount < session()->get('balance')) {
                $payment = $this->paymentRepository->savePayment(
                    [
                        'user_id'   =>  Auth::id(),
                        'cour_id'   =>  $crypto->cours[0]->id,
                        'quantity'  =>  $request->get('quantity'),
                        'amount'    =>  $amount
                    ]
                );

                if ($payment) {
                    session()->flash('success', 'votre achat a été effectué');
                } else {
                    session()->flash('error', 'une erreur est survenue lors de votre achat');
                }
            } else  {
                session()->flash('error', 'votre solde actuel est insuffisant pour cet achat, veuillez mettre à jour votre solde');
            }
        } else {
            session()->flash('error','veuillez entrer une quantité supérieur à 0');
            return redirect()->back();
        }
        return redirect()->route('crypto.index');
    }

    public function showDetail($crypto_id) {
        $payments   =   $this->paymentRepository->getPaymentDetail(Auth::id(), $crypto_id);
        $crypto     =   $this->cryptoRepository->getCryptoInformation($crypto_id);
        if ($payments->isEmpty()) {
            return redirect()->back();
        }
        $benefic_amount = $this->parseBeneficSale($payments);
        return view('payment.detail', ['payments' => $payments, 'crypto' => $crypto, 'benefic_amount' => $benefic_amount]);
    }

    public function salePayment($crypto_id) {
        if ($crypto_id) {
            $payments   =   $this->paymentRepository->getPaymentDetail(Auth::id(), $crypto_id);
            if ($payments->isEmpty()) {
                return redirect()->back();
            }
            $current_sale_amount = $this->parseAmountCryptoForSave($payments);
            if ($current_sale_amount > 0) {
                $this->paymentRepository->updateSalePayment(Auth::id(),$payments, $current_sale_amount);
                session()->flash('success', 'Votre vente a été effectué');
            } else {
                session()->flash('error', 'tous les paiements de cette crypto-monnaie ont déjà été vendu');
            }
        }
        return redirect()->back();
    }

    /**
     * Liste des crypto-monnaies du client
     * @param $payments
     * @return array
     */
    private function parseMyCryptoPayment($payments) {
        $arrCrypto = [];
        if ($payments) {
            foreach ($payments as $payment) {
                if (!array_key_exists($payment->cour->crypto_id,$arrCrypto)) {
                    $arrCrypto[$payment->cour->crypto_id] = $payment->cour->crypto;
                }
            }
        }
        return $arrCrypto;
    }

    /**
     *
     * calcule du montant des gains
     * @param $payments
     * @return false|float|int
     */
    private function parseBeneficSale($payments) {
        $benefic_amount = 0;
        foreach ($payments as $payment) {
            if (!$payment->is_sale) {
                $buy_amount             =   $payment->amount;
                $crypto                 =   $this->cryptoRepository->getCryptoInformation($payment->cour->crypto_id);
                $current_sale_amount    =   round(($payment->quantity * $crypto->current_cour), 2);
                $benefic_amount += ($buy_amount - $current_sale_amount);
            }
        }
        return $benefic_amount;
    }

    /**
     *
     * calcule du montant des gains
     * @param $payments
     * @return false|float|int
     */
    private function parseAmountCryptoForSave($payments) {
        $current_sale_amount = 0;
        foreach ($payments as $payment) {
            if (!$payment->is_sale) {
                $crypto                 =   $this->cryptoRepository->getCryptoInformation($payment->cour->crypto_id);
                $current_sale_amount    +=   round(($payment->quantity * $crypto->current_cour), 2);
            }
        }
        return $current_sale_amount;
    }
}
