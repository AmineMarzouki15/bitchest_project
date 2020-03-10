<?php


namespace App\Http\Controllers;


use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index() {
        $user   =   $this->userRepository->getUserById(Auth::id());
        return view('profile.index', ['user' => $user]);
    }

    /**
     *
     * mise à jour des informations personneles
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'same:password_confirmation'
        ],
            [
                'name.required' => 'Veuillez renseigner le nom',
                'email.required' => 'Veuillez renseigner l\'adresse email',
                'email.email' => 'Cet adresse email n\'est pas valide',
                'email.unique' => 'Cet adresse email est déjà utilisée'
            ]
        );

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input, array('password'));
        }

        $user = $this->userRepository->getUserById(Auth::id());
        $user->update($input);

        session()->flash('success', 'Mise à jour effectué');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBalance(Request $request) {
        if ($request->has('amount')) {
            $amount =   $request->get('amount');
            $is_save = $this->userRepository->updateBalance(Auth::id(),$amount);
            if ($is_save) {
                session()->flash('success', 'Votre solde a été mis à jour');
            } else {
                session()->flash('error', 'Une erreur est survenue lors de la mise à jour');
            }
        } else {
            session()->flash('error', 'veuillez renseigner le montant');
        }
        return redirect()->back();
    }
}
