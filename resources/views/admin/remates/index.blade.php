<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Remates | DAMICOR</title>
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
        .thumb-btn{border:0;background:none;padding:0;cursor:zoom-in}
        .thumb-img{width:150px;height:100px;object-fit:cover;border-radius:8px;display:block}
        .actions{display:flex;gap:8px;flex-wrap:wrap}
        form{display:inline}
        .muted{color:#5f708b}
        .img-modal{position:fixed;inset:0;background:rgba(10,19,33,.78);display:none;align-items:center;justify-content:center;padding:18px;z-index:200}
        .img-modal.open{display:flex}
        .img-modal img{max-width:min(1100px,96vw);max-height:90vh;border-radius:10px;box-shadow:0 18px 44px rgba(0,0,0,.42);object-fit:contain;background:#fff}
        .img-modal-close{position:absolute;top:14px;right:14px;border:0;background:#fff;color:#18345f;border-radius:999px;width:36px;height:36px;font-size:1.15rem;font-weight:700;cursor:pointer}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="head">
            <h1>Gestión de remates</h1>
            <div class="actions">
                <a class="btn" href="{{ route('admin.remates.create') }}">Nuevo remate</a>
                <form method="post" action="{{ route('admin.remates.logout') }}">
                    @csrf
                    <button class="btn alt" type="submit">Cerrar sesión</button>
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
                <th>Número expediente</th>
                <th>Ubicación inmueble</th>
                <th>Tasacion</th>
                <th>Tasaciones</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($remates as $remate)
                <tr>
                    <td>
                        <button class="thumb-btn js-open-img" type="button" data-src="{{ $remate->foto_path }}" aria-label="Ver foto ampliada">
                            <img class="thumb-img" src="{{ $remate->foto_path }}" alt="Foto remate">
                        </button>
                    </td>
                    <td>{{ $remate->numero_expediente }}</td>
                    <td>{{ $remate->ubicacion_inmueble }}</td>
                    <td>{{ ($remate->tasacion_moneda ?? 'PEN') === 'USD' ? 'US$' : 'S/' }} {{ number_format((float)$remate->tasacion,2) }}</td>
                    <td>
                        @foreach($remate->tasaciones as $tasacion)
                            <div class="muted">{{ optional($tasacion->fecha)->format('d/m/Y') }} {{ substr((string)$tasacion->hora,0,5) }} - {{ ($tasacion->moneda ?? 'PEN') === 'USD' ? 'US$' : 'S/' }} {{ number_format((float)$tasacion->precio_base,2) }}</div>
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
                <tr><td colspan="6">No hay remates registrados.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top:12px">{{ $remates->links() }}</div>
    </div>
    <div id="img-modal" class="img-modal" aria-hidden="true">
        <button id="img-modal-close" class="img-modal-close" type="button" aria-label="Cerrar">&times;</button>
        <img id="img-modal-photo" src="" alt="Foto ampliada del inmueble">
    </div>
    <script>
        (function () {
            var modal = document.getElementById('img-modal');
            var modalPhoto = document.getElementById('img-modal-photo');
            var closeBtn = document.getElementById('img-modal-close');
            var openers = document.querySelectorAll('.js-open-img');

            function closeModal() {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true');
                modalPhoto.src = '';
            }

            openers.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    modalPhoto.src = btn.getAttribute('data-src') || '';
                    modal.classList.add('open');
                    modal.setAttribute('aria-hidden', 'false');
                });
            });

            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
            });
        })();
    </script>
</body>
</html>
