<?php namespace App\Modules\Server\Controllers;

/**
 * Load module-related classes
 */
use App\Modules\Server\Models\Account;

use Auth, Config, Hash, Input, Log, Redirect, Validator, View;

class AccountsController extends \BaseController {

	protected $layout = 'public.master';

	/**
	 * Show the form for creating a new account.
	 * GET /account/create
	 *
	 * @return Response
	 */
	public function create() {
		return View::make('accounts.create', array(	
			'title' => 'Create account',
		));
	}

	/**
	 * Remove the current account from storage.
	 * DELETE /account
	 *
	 * @return Response
	 */
	public function destroy() {
		$account = Account::find(Auth::user()->id);
		if (Hash::check(Input::get('password'), $account->getAuthPassword())) {
			$account->delete();
		}
	}

	/**
	 * Show the form for editing the current account.
	 * GET /account/edit
	 *
	 * @return Response
	 */
	/*public function edit() {
		
	}*/

	/**
	 * Display the current account.
	 * GET /account
	 *
	 * @return Response
	 */
	public function show() {
		$account = Account::with('bans', 'characters')->find(Auth::user()->id);
		return View::make('accounts.show', array(
			'account' => $account,
			'title' => 'Account management',
		));
	}

	/**
	 * Store a newly created account in storage.
	 * POST /account
	 *
	 * @return Response
	 */
	public function store() {
		$input = Input::only('name', 'password');
		$validator = Validator::make($input, Account::$rules);

		if ($validator->passes()) {
			if ($new_account = Account::create($input)) {
				Auth::loginUsingId($new_account->id);
				return Redirect::route('account.show');
			} else {
				return Redirect::back()->with('flash_error', 'Your account could not be created. Contact the server administrator and ask him/her to fill a bug report for Zenith.');
			}
		} else {
			return Redirect::back()->withInput()->withErrors($validator);
		}
	}

	/**
	 * Update the specified account in storage.
	 * PUT /account
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update() {
		$current_id = Auth::user()->id;
	
		/**
		 * If the user has requested name change
		 */
		if (Input::has('name')) {
			if (Config::get('zenith.allow_account_name_change')) {
				$input = array('name' => Input::get('name'));
				$rules = array_intersect_key(Account::$rules, array_flip(array('name')));
				$validator = Validator::make($input, $rules);
				if ($validator->passes()) {
					$account = Account::find($current_id);
					$account->name = $input['name'];
					if ($account->save()) {
						Auth::loginUsingId($account->id);
						return Redirect::route('account.show');
					} else {
						return Redirect::back()->with('flash_error', 'Your account could not be updated. Contact the server administrator and ask him/her to fill a bug report for Zenith.');
					}
				} else {
					return Redirect::back()->withInput()->withErrors($validator);
				}
			} else {
				Log::warning("Account #{$current_id} has tried to change its email without permission.");
				return Redirect::back()->with('flash_error', 'You are not allowed to change your account name.');
			}
		}
		
		/**
		 * If the user has requested email change
		 */
		elseif (Input::has('email')) {
			$input = array('email' => Input::get('email'));
			$rules = array_intersect_key(Account::$rules, array_flip(array('email')));
			$validator = Validator::make($input, $rules);
			if ($validator->passes()) {
				$account = Account::find($current_id);
				if (empty($account->email) || Config::get('zenith.allow_account_email_change')) {
					$account->email = $input['email'];
					if ($account->save()) {
						Auth::loginUsingId($account->id);
						return Redirect::route('account.show');
					} else {
						return Redirect::back()->with('flash_error', 'Your account could not be updated. Contact the server administrator and ask him/her to fill a bug report for Zenith.');
					}
				} else {
					Log::warning("Account #{$current_id} has tried to change its email without permission.");
					return Redirect::back()->with('flash_error', 'You are not allowed to change your account email.');
				}
			} else {
				return Redirect::back()->withInput()->withErrors($validator);
			}
		}
	}
}
