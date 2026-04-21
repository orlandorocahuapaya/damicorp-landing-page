<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso Remates | DAMICOR</title>
    <style>
        body{font-family:Arial,sans-serif;background:#0f1b2d;color:#0f1b2d;display:grid;place-items:center;min-height:100vh;margin:0}
        .card{background:#fff;border-radius:14px;padding:24px;width:min(420px,92%);box-shadow:0 18px 40px rgba(0,0,0,.28)}
        h1{font-size:1.2rem;margin:0 0 14px}
        label{display:block;font-weight:700;font-size:.9rem;margin-bottom:6px}
        input{width:100%;padding:10px 12px;border:1px solid #c9d6eb;border-radius:10px}
        button{margin-top:14px;width:100%;padding:11px;border:0;border-radius:10px;background:#1f4d93;color:#fff;font-weight:700;cursor:pointer}
        .err{font-size:.85rem;color:#9a1e2b;margin-top:7px}
    </style>
</head>
<body>
    <main class="card">
        <h1>Panel privado de remates</h1>
        <form method="post" action="{{ route('admin.remates.login.submit') }}">
            @csrf
            <label for="password">Contrasena</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
            @error('password') <p class="err">{{ $message }}</p> @enderror
            <button type="submit">Ingresar</button>
        </form>
    </main>
</body>
</html>
