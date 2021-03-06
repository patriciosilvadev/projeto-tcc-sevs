@extends('layouts.auth')

@section('content')

    <form id="formulario" class="card-shadow-2dp" role="form" method="POST" action="{{ route('password.email') }}"
          aria-label="{{ __('Resetar Senha') }}">
        @csrf
        <div class="text-center mb-3">
            <img class="" src="{{ asset('img/vidraceiro-solid.svg') }}" alt="" width="72" height="72">
        </div>
        <h1 class="title">{{ __('Resetar Senha') }}</h1>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="form-label-group mb-4">
            <label for="email">{{ __('E-Mail') }}</label>
            <input type="email" id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                   name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
            @if ($errors->has('email'))
                <span class="badge badge-danger mt-3">{{ $errors->first('email') }}</span>
            @endif
        </div>
        <button class="btn btn-md btn-primary btn-block btn-custom"
                type="submit"> {{ __('Resetar Senha') }}</button>
    </form>
@endsection
