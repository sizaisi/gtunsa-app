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
            'cui' => ['required', 'string', 'max:8', 'unique:GT_GRADUANDO', 'exists:acdiden,cui'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:GT_GRADUANDO'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telefono_fijo' => ['nullable', 'string', 'max:10'],
            'telefono_movil' => ['required', 'string', 'max:15'],
            'direccion' => ['required', 'string', 'max:150'],
        ]);
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
            'cui' => $data['cui'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'telefono_fijo' => $data['telefono_fijo'],
            'telefono_movil' => $data['telefono_movil'],
            'direccion' => $data['direccion'],
        ]);
    }
}
