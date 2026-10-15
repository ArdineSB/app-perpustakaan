{{-- File: resources/views/profil/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <h1>Profil</h1>

    <table>
        <tr>
            <th>Nama</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td>{{ ucfirst($user->role) }}</td>
        </tr>
    </table>

    <h2>Ganti Password</h2>

    <form action="{{ route('profil.password') }}" method="POST">
        @csrf

        <label for="password_lama">Password lama</label>
        <input type="password" name="password_lama" id="password_lama">
        @error('password_lama')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="password_baru">Password baru</label>
        <input type="password" name="password_baru" id="password_baru">
        @error('password_baru')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="password_baru_confirmation">Konfirmasi password baru</label>
        <input type="password" name="password_baru_confirmation" id="password_baru_confirmation">

        <button type="submit" class="btn">Simpan Password</button>
    </form>
@endsection
