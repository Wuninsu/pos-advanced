@include('layouts._head')


<body>

    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <div class="mb-4 mx-auto">
                <img src="{{ asset('assets/img/logo2.png') }}" alt="Company Logo" class="img-fluid"
                    style="max-height: 80px;">
            </div>
            <h2 class="text-center mb-4">Login</h2>
            <form method="POST" action="{{ route('auth.login') }}" method="POST">
                @csrf
                @include('layouts._alerts')
                <!-- Login id Input -->
                <div class="mb-3">
                    <label for="uid" class="form-label">Email / Username</label>
                    <input type="text" class="form-control @error('login_id') border-danger is-invalid @enderror"
                        id="user_id" name="login_id" value="{{ old('login_id') }}"
                        placeholder="Enter your email or username">
                    @error('login_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') border-danger is-invalid @enderror"
                        id="password" name="password" placeholder="Enter your password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Remember Me and Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <a href="/forgot-password" class="text-decoration-none">Forgot Password?</a>
                </div>

                <!-- Login Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>

            <!-- Sign Up Link -->
            <div class="text-center mt-3">
                <p>Don't have an account? <a href="/signup" class="text-decoration-none">Sign Up</a></p>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/global.js') }}"></script>
</body>

</html>
