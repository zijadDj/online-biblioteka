<!-- resources/views/librarian/reset-password.blade.php -->
@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if ($errors->any())
    <ul style="color: red;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<!DOCTYPE html>
<html>
<head>
    <title>Resetuj lozinku</title>
</head>
<body>
<h1>Resetuj lozinku</h1>

<form method="POST" action="/librarian/reset-password">
    @csrf

    <input type="hidden" name="email" value="{{ request('email') }}">
    <input type="hidden" name="token" value="{{ request('token') }}">

    <div>
        <label for="password">Nova lozinka:</label>
        <input type="password" name="password" required>
    </div>

    <div>
        <label for="password_confirmation">Potvrdi lozinku:</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <button type="submit">Resetuj</button>
</form>
</body>
</html>

