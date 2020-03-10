<?php


namespace App\Repositories;


use App\Model\Balance;
use App\Model\Payment;

class PaymentRepository
{

    /**
     * @var Payment
     */
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * enregistrement d'un paiement
     * @param $arrData
     * @return |null
     */
    public function savePayment($arrData) {
        if (!empty($arrData)) {
            $payment = $this->payment::create(
                [
                    'user_id'   =>  $arrData['user_id'],
                    'cour_id'   =>  $arrData['cour_id'],
                    'quantity'   =>  $arrData['quantity'],
                    'amount'   =>  $arrData['amount'],
                    'is_sale'   => false
                ]
            );
            if ($payment) {
                $balance    =   Balance::where(['user_id' => $arrData['user_id']])->first();
                $balance->amount -= $arrData['amount'];
                if ($balance->save()) {
                    session()->put('balance', $balance->amount);
                }
            }
            return $payment;
        }
        return null;
    }

    public function getAllPayment($user_id) {
        return $this->payment::where(['user_id' => $user_id, 'is_sale' => false])->with(['Cour.Crypto'])->get();
    }

    public function getPaymentDetail($user_id, $crypto_id) {
        return $this->payment::where(['user_id' => $user_id])
                                ->whereHas('cour', function ($query) use ($crypto_id) {
                                    return $query->where('crypto_id', '=', $crypto_id);
                                })
                                ->with('cour.crypto')
                                ->get();
    }

    public function updateSalePayment($user_id,$payments, $current_sale_amount) {
        foreach ($payments as $payment) {
            if (!$payment->is_sale) {
                $payment->is_sale = true;
                $payment->save();
            }
        }
        $balance =  Balance::where(['user_id' => $user_id])->first();
        $balance->amount += $current_sale_amount;
        if ($balance->save()) {
            session()->put('balance', $balance->amount);
        }
    }
}
