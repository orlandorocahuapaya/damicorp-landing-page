<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion de Remates | DAMICOR</title>
    <style>
        body{font-family:Arial,sans-serif;margin:0;background:#f4f7fd;color:#10233f}
        .wrap{width:min(1100px,94%);margin:22px auto}
        .head{display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap}
        .btn{background:#214f95;color:#fff;padding:9px 13px;border-radius:10px;text-decoration:none;font-weight:700;border:0;cursor:pointer}
        .btn.alt{background:#6b7e99}
        .ok{background:#e7f6ec;border:1px solid #bfe5cb;padding:10px;border-radius:10px;margin:12px 0}
        table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 10px 28px rgba(0,0,0,.07);margin-top:14px}
        th,td{padding:10px;border-bottom:1px solid #e3ebf8;text-align:left;font-size:.9rem;vertical-align:top}
        th{background:#eff4ff}
        img{width:92px;height:66px;object-fit:cover;border-radius:8px}
        .actions{display:flex;gap:8px;flex-wrap:wrap}
        form{display:inline}
        .muted{color:#5f708b}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="head">
            <h1>Gestion de remates</h1>
            <div class="actions">
                <a class="btn" href="{{ route('admin.remates.create') }}">Nuevo remate</a>
                <form method="post" action="{{ route('admin.remates.logout') }}">
                    @csrf
                    <button class="btn alt" type="submit">Cerrar sesion</button>
                </form>
            </div>
        </div>

        @if(session('status'))
            <p class="ok">{{ session('status') }}</p>
        @endif

        <table>
            <thead>
            <tr>
                <th>Foto</th>
                <th>Fecha expediente</th>
                <th>Ubicacion inmueble</th>
                <th>Tasaciones</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($remates as $remate)
                <tr>
                    <td><img src="{{ $remate->foto_path }}" alt="Foto remate"></td>
                    <td>{{ optional($remate->fecha_expediente)->format('d/m/Y') }}</td>
                    <td>{{ $remate->ubicacion_inmueble }}</td>
                    <td>
                        @foreach($remate->tasaciones as $tasacion)
                            <div class="muted">S/ {{ number_format((float)$tasacion->precio_base,2) }} - {{ optional($tasacion->fecha)->format('d/m/Y') }}</div>
                        @endforeach
                    </td>
                    <td>
                        <div class="actions">
                            <a class="btn" href="{{ route('admin.remates.edit', $remate) }}">Editar</a>
                            <form method="post" action="{{ route('admin.remates.destroy', $remate) }}" onsubmit="return confirm('¿Eliminar este remate?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn alt" type="submit">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No hay remates registrados.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top:12px">{{ $remates->links() }}</div>
    </div>
</body>
</html>
