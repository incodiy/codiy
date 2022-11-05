<?php
/**
 * Created on Mar 6, 2017
 * Time Created	: 2:10:55 PM
 * Filename		: login.blade.php
 *
 * @filesource	login.blade.php
 *
 * @author		wisnuwidi @gmail.com - 2017
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
$config               = [];
$config['title']      = __('ADMIN LOGIN');
$config['logo']       = asset('assets/templates/default') . '/images/logo-almasjid.png';
$config['background'] = null;
if (!empty($content_page['login_page'])) {
	if (!empty($content_page['login_page']['title']))      $config['title']      = $content_page['login_page']['title'];
	if (!empty($content_page['login_page']['logo']))       $config['logo']       = $content_page['login_page']['logo'];
	if (!empty($content_page['login_page']['background'])) $config['background'] = $content_page['login_page']['background'];
}

$background = ' class="login-area login-bg"';
$formStyle  = null;
if (!empty($config['background'])) {
	$background = ' class="login-area login-bg" style="background: url(' . $config['background'] . ') center/cover no-repeat !important;z-index:none !important"';
	$formStyle  = 'left:-35% !important;position:relative;margin:auto 15% !important;';
}
$formStyle  = null;
?>

@extends('default.template.admin.index')

@section('content')

		<div{!! $background !!}>
			<div class="container">
				<div class="login-box ptb--40">
					{!! Form::open(['route' => 'login_processor', 'class' => 'sign-in form-horizontal shadow rounded no-overflow', 'style' => $formStyle]) !!}
						<div class="login-form-head">
							<div class="logo"><img src="{{ $config['logo'] }}" /></div>
							<h4>{{ $config['title'] }}</h4>
						</div>
						<div class="login-form-body">
							<div class="form-gp">
								<label for="expresscode-input-login-email">{{ __('E-Mail Address') }}</label>
								{!! Form::email('email', old('email'), ['id' => 'expresscode-input-login-email', 'class' => $errors->has('email') ? ' is-invalid' : '', 'required', 'autofocus']) !!}
								<i class="ti-email"></i>
								
								@if ($errors->has('email'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
								@endif
							</div>
							<div class="form-gp">
								<label for="expresscode-input-login-password">{{ __('Password') }}</label>
								{!! Form::password('password', ['id' => 'expresscode-input-login-password']) !!}
								<i class="ti-lock"></i>
								
								@if ($errors->has('password'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
								@endif
							</div>
							<div class="row mb-4 rmber-area">
								<div class="col-6">
									<div class="custom-control custom-checkbox mr-sm-2">
										<input type="checkbox" class="custom-control-input" id="customControlAutosizing" {{ old('remember') ? 'checked' : '' }} />
										<label class="custom-control-label" for="customControlAutosizing">{{ __('Remember Me') }}</label>
									</div>
								</div>
								<div class="col-6 text-right">
									<a href="{{ route('password.request') }}" id="forgot-password-button" data-toggle="modalxxx" data-target="#forgot-password-boxxx">{{ __('Forgot Your Password?') }}</a>
								</div>
							</div>
							<div class="submit-btn-area">
								{!! Form::submit('Submit', ['class' => 'btn btn-primary btn-lg btn-block no-margin rounded', 'id' => 'login-btn']) !!}
							</div>
							<div class="form-footer text-center mt-5">
								<p class="text-muted">Don't have an account? <a href="{{ route('register') }}">{{ __('Register') }}</a></p>
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
		
@endsection