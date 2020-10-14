<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Response, File;
use Socialite;
use App\User;
use App\CodigoMail;

class SocialController extends Controller
{

	public function redirect($provider)
	{
		return Socialite::driver($provider)->redirect();
	}

	public function callback($provider)
	{

		$getInfo = Socialite::driver($provider)->user();

		$user = $this->createUser($getInfo, $provider);

		auth()->login($user);

		return redirect()->to('/home');
	}

	function createUser($getInfo, $provider)
	{

		$cui = CodigoMail::where('mail', $getInfo->email)->first()->cui;

		//$user = User::where('provider_id', $getInfo->id)->first();
		$user = User::where('email', $getInfo->email)->first();

		if (!$user) {
			$user = User::create([
				'cui'    => $cui,
				'email'    => $getInfo->email,
				'provider' => $provider,
				'provider_id' => $getInfo->id
			]);
		} else {
			//User::where('email', $user['email'])->update(['provider' => 'google', 'provider_id' => $googleData->getId()]);
			User::where('email', $user->email)
				->update(['provider' => $provider, 'provider_id' => $getInfo->id]);
		}

		return $user;
	}
}
