<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Response, File;
use Socialite;
use App\User;
use App\Graduando;
use App\AlumnoEmail;

class SocialController extends Controller
{
	public function redirect($provider)
	{
		return Socialite::driver($provider)->with(['hd' => 'unsa.edu.pe'])->redirect();
	}

	public function callback($provider)
	{
		$getInfo = Socialite::driver($provider)->user();

		$parts_email = explode('@', $getInfo->email);
        $email_name = current($parts_email);           
		$alumno_unsa = AlumnoEmail::where('mail', $email_name)->first();

		if (!$alumno_unsa) {
			\Session::flash('login_message', "Lo sentimos, su correo aún no esta habilitado o registrado en el Sistema Académico");			

			return redirect()->to('/');
		}
		else {
			$cui = $alumno_unsa->cui;

			//dd($cui);
			//$user = $this->createUser($getInfo, $provider, $cui);
			$user = $this->crearGraduando($getInfo, $cui);			
			auth()->login($user);	
		}		

		return redirect()->to('/home');
	}

	function createUser($getInfo, $provider, $cui)
	{		
		//$user = User::where('email', $getInfo->email)->first();
		$user = User::where('cui', $cui)->first();

		if (!$user) {
			$user = User::create([
				'cui'    => $cui,
				'name' => $getInfo->name,
				'email'    => $getInfo->email,
				'provider' => $provider,
				'provider_id' => $getInfo->id,				
			]);
		} else {			
			User::where('email', $user->email)
				->update(
					[
						'name' => $getInfo->name,
						'provider' => $provider, 
						'provider_id' => $getInfo->id						
					]
				);
		}		

		return $user;
	}

	function crearGraduando($getInfo, $cui)
	{		
		$user = User::where('email', $getInfo->email)->first();		

		if (!$user) {
			$graduando = Graduando::create([				
				'cui' => $cui,				
			]);			

			$user = User::create([				
				'name' => $getInfo->name,
				'tipo_administrado' => 'Graduando',
				'administrado_id' => $graduando->id,
				'email'    => $getInfo->email,				
				'google_id' => $getInfo->id,				
			]);
		}	

		return $user;
	}
}
