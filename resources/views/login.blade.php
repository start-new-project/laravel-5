<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>  

    <!-- Styles -->
    <link href="{{ mix('assets/css/app.css') }}" rel="stylesheet">
</head>
    <body>
    <div id="login" style="background-image:url('assets/images/login-bg-1.jpg')">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-3 offset-lg-4 col-md-4 offset-md-4 col-sm-6 offset-sm-3">
                    <div id="login-form" class="p-3">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <h4 class="text-center pt-2">
                                {{ __('Login') }}
                            </h4>
                            <hr>
                            <div class="p-2">
                                <div class="form-group row">
                                    <label for="email" class="col-12 col-form-label">{{ __('E-Mail Address') }}</label>

                                    <div class="col-12">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-12 col-form-label">{{ __('Password') }}</label>

                                    <div class="col-12">
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>  
                            </div>
                            <hr>
                            <div class="form-group row pr-2 pl-2">
                                <div class="col-9">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-sm btn-primary float-right">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html> 
