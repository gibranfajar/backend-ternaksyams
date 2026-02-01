@extends('layouts.auth')

@section('content')
    <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center bg-light">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="text-center mb-4">
                                <h3 class="mb-0"><b>Login</b></h3>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" placeholder="Email Address"
                                    required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn"
                                    style="background-color: #256830; color: #fff">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
