<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'cui' => ['required', 'string', 'max:8', 'unique:gt_graduando', 'exists:acdiden,cui'],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:gt_graduando', 'unique:actmail,mail'],
            'apn' => ['required', 'string', 'max:70'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:gt_graduando'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telefono_fijo' => ['nullable', 'string', 'max:10'],
            'telefono_movil' => ['required', 'string', 'max:15'],
            'direccion' => ['required', 'string', 'max:150'],
        ],                
        /*[            
            'email.unique' => 'Ya existe un usuario con esa dirección de correo en el sistema. Solicita una contraseña olvidada o selecciona otra dirección de correo electrónico.',
        ]*/
    );


    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            //'cui' => $data['cui'],
            'apn' => $data['apn'],
            'email' => $data['email'],            
            'password' => Hash::make($data['password']),
            'telefono_fijo' => $data['telefono_fijo'],
            'telefono_movil' => $data['telefono_movil'],
            'direccion' => $data['direccion'],
        ]);
    }
}
