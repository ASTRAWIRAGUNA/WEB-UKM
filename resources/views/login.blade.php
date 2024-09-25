@extends('base')

@section('head')
<style>
    body, html {
        height: 100%;
    }

    .login-container {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
    }

    .login-box {
        background-color: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    .login-box h2 {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .form-control {
        margin-bottom: 15px;
    }

    .btn-primary {
        width: 100%;
    }
</style>
@endsection

@section('body')
<div class="login-container">
    <div class="login-box">
        <h2>Login</h2>
        <form action="#" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control shadow-none" id="nim" name="nim" placeholder="NIM" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control shadow-none" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control shadow-none" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>
@endsection