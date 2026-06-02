@extends('layouts.auth')
@section('title', 'Вход')
@section('subtitle', 'Авторизуйтесь, чтобы открыть личный кабинет')

@section('content')
    <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
        @csrf
        <x-form.input name="email" type="email" label="Email" :value="old('email')" required />
        <x-form.input name="password" type="password" label="Пароль" required />
        <x-form.checkbox name="remember" label="Запомнить меня" :checked="old('remember')" />

        <button type="submit" class="w-full inline-flex justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
            Войти
        </button>
    </form>

    <p class="mt-5 text-sm text-gray-600">
        Нет аккаунта?
        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-800">Зарегистрироваться</a>
    </p>
@endsection
