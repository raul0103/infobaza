@extends('layouts.auth')
@section('title', 'Регистрация')
@section('subtitle', 'Создайте аккаунт для доступа к системе')

@section('content')
    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf
        <x-form.input name="name" label="Имя" :value="old('name')" required />
        <x-form.input name="username" label="Логин" :value="old('username')" required />
        <x-form.input name="email" type="email" label="Email" :value="old('email')" required />
        <x-form.input name="password" type="password" label="Пароль" required />
        <x-form.input name="password_confirmation" type="password" label="Подтверждение пароля" required />

        <button type="submit" class="w-full inline-flex justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
            Создать аккаунт
        </button>
    </form>

    <p class="mt-5 text-sm text-gray-600">
        Уже есть аккаунт?
        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-800">Войти</a>
    </p>
@endsection
