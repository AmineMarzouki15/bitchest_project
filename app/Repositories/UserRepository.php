<?php

namespace App\Repositories;

use App\Model\Balance;
use App\Model\User;
use Illuminate\Database\Query\Builder;

class UserRepository  {

    /**
     * Undocumented function
     *
     * @param User $user
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * liste des utilisateurs
     * @return mixed
     */
    public function getAllUserList() {
        return $this->user::paginate(20);
    }

    /**
     * liste des clients
     * @return mixed
     */
    public function getAllCustomerList() {
        return $this->user::role('Client')->paginate(20);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createUser($data) {
        $user =  $this->user::create($data);
        if ($data['roles'][0] == 'Client') {
            Balance::create(
                [
                    'user_id'   => $user->id,
                    'amount'    => 0
                ]
            );
        }
        return $user;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id) {
        return $this->user::find($id);
    }


    /**
     * @param $id
     * @param $amount
     * @return bool
     */
    public function updateBalance($id, $amount)
    {
        $balance    =   Balance::where(['user_id' => $id])->first();
        $balance->amount    += $amount;
        if ($balance->save()) {
            session()->put('balance', $balance->amount);
            return true;
        }
        return false;
    }

}

