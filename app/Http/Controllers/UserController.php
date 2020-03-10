<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Undocumented function
     *
     * @param UserRepository $userRepository
     */
    private $userRepository;

    /**
     * Undocumented variable
     *
     * @param RoleRepository
     */
    private $roleRepository;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);

        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users  =  session()->get('user_role') == 'SuperAdmin' ? $this->userRepository->getAllUserList() : $this->userRepository->getAllCustomerList();
    
        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles  =   $this->roleRepository->getAllRole();
        // si ce n'est pas le super admin qui est connecté on supprime son role
        $userRole = Auth::user()->roles->pluck('name','name')->all();
        if (!isset($userRole['SuperAdmin'])) {
            unset($roles['SuperAdmin']);
            unset($roles['Admin']);
        }
        return view('users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:password_confirmation',
            'roles' => 'required'
        ],
            [
                'name.required' => 'Veuillez renseigner le nom',
                'email.required' => 'Veuillez renseigner l\'adresse email',
                'email.email' => 'Cet adresse email n\'est pas valide',
                'email.unique' => 'Cet adresse email est déjà utilisée',
                'roles.required' => 'Veuillez attribué le role'
            ]
        );

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = $this->userRepository->createUser($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success','L\'enregistrement a été effectué');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepository->getUserById($id);
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->getUserById($id);
        $roles = $this->roleRepository->getAllRole();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:password_confirmation',
            'roles' => 'required'
        ],
            [
                'name.required' => 'Veuillez renseigner le nom',
                'email.required' => 'Veuillez renseigner l\'adresse email',
                'email.email' => 'Cet adresse email n\'est pas valide',
                'email.unique' => 'Cet adresse email est déjà utilisée',
                'roles.required' => 'Veuillez attribué le role'
            ]

        );


        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }

        $user = $this->userRepository->getUserById($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success','Mise à jour effectué');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $this->userRepository->getUserById($id)->delete();
        return redirect()->route('users.index')
            ->with('success','Suppression effectué');
    }
}
