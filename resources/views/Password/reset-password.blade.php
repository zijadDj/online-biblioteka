<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body style="display: flex; flex-direction: column; align-items: center; margin: 100px;">
<h1>Reset Password</h1>

<form method="POST" action="{{ url('librarian/reset-password') }}"
      style=" display:flex; row-gap: 10px; flex-direction: column; align-items: center;">
    @csrf

    <input type="hidden" name="token" value="{{ request()->query('token') }}">

    <div style="display: flex; align-items: center;">
        <label style="width: 150px; margin-right:10px; text-align: center;">Email</label>
        <input style="flex: 1; padding:5px;" type="email" name="email" value="{{ request()->query('email') }}" required
               autofocus>
    </div>

    <div style="display: flex; align-items: center;">
        <label style="width: 150px; margin-right:10px; text-align: center;">New Password</label>
        <input style="flex: 1; padding:5px;" type="password" name="password" required>
    </div>

    <div style="display: flex; align-items: center;">
        <label style="width: 150px; margin-right:10px; text-align: center;">Confirm Password</label>
        <input style="flex: 1; padding:5px;" type="password" name="password_confirmation" required>
    </div>

    <button type="submit" style="margin-top: 10px; width:150px;">Reset Password</button>
</form>

</body>
</html>
