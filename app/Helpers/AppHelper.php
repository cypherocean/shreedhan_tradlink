<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * FOR NODE, REDIS AND THIRD PARTY CONNECTION
 */
class AppHelper {
	public static function themeClass() {

		$themeArray = array( /* White Theme */
			'body_class' => '',
			'body_attributes' => '',
			'nav_class' => 'navbar-light',
			'main_menu_class' => 'menu-light',
			'theme_icon' => 'sun',
			'theme' => 0,
		);

		$isProposalRequest = true;
		if (\Request::is(config('Menu_URL.proposal') . '/document/*') || \Request::is(config('Menu_URL.proposal') . '/view/*')) {
			$isProposalRequest = false;
		}
		if (Auth::check() && $isProposalRequest) {
			$user = Auth::user();
			if ($user['theme'] == 1) { /* Dark Theme */
				$themeArray = array(
					'body_class' => 'dark-layout chat-application',
					'body_attributes' => 'data-layout="dark-layout"',
					'nav_class' => 'navbar-dark',
					'main_menu_class' => 'menu-dark',
					'theme_icon' => 'moon',
					'theme' => 1,
				);
			}

			if ($user['theme'] == 2) { /* Semi Theme */
				$themeArray = array(
					'body_class' => 'semi-dark-layout',
					'body_attributes' => 'data-layout="semi-dark-layout"',
					'nav_class' => 'navbar-light',
					'main_menu_class' => 'menu-dark',
					'theme_icon' => 'sunset',
					'theme' => 2,
				);
			}
		}
		return $themeArray;
	}
}