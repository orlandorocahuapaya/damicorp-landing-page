<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $remate ? 'Editar' : 'Crear' }} Remate | DAMICOR</title>
    <style>
        body{font-family:Arial,sans-serif;margin:0;background:#f4f7fd;color:#10233f}
        .wrap{width:min(900px,94%);margin:22px auto}
        .card{background:#fff;border-radius:12px;padding:16px;box-shadow:0 10px 28px rgba(0,0,0,.07)}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .full{grid-column:1/-1}
        label{display:block;font-weight:700;font-size:.9rem;margin-bottom:6px}
        input{width:100%;padding:10px;border:1px solid #c9d6eb;border-radius:10px}
        .row{display:grid;grid-template-columns:1fr 1fr auto;gap:10px;align-items:end;margin-bottom:10px}
        .btn{background:#214f95;color:#fff;padding:10px 13px;border-radius:10px;text-decoration:none;font-weight:700;border:0;cursor:pointer}
        .btn.alt{background:#6b7e99}
        .actions{display:flex;gap:10px;margin-top:16px}
        .err{font-size:.85rem;color:#9a1e2b;margin-top:4px}
        @media(max-width:760px){.grid{grid-template-columns:1fr}.row{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="wrap">
    <h1>{{ $remate ? 'Editar remate' : 'Nuevo remate' }}</h1>
    <div class="card">
        <form method="post" action="{{ $action }}" enctype="multipart/form-data">
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif
            <div class="grid">
                <div>
                    <label for="fecha_expediente">Fecha de expediente</label>
                    <input id="fecha_expediente" name="fecha_expediente" type="date" value="{{ old('fecha_expediente', optional($remate?->fecha_expediente)->format('Y-m-d')) }}" required>
                    @error('fecha_expediente') <p class="err">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="foto">Foto del remate {{ $remate ? '(opcional al editar)' : '' }}</label>
                    <input id="foto" name="foto" type="file" accept="image/*" {{ $remate ? '' : 'required' }}>
                    @error('foto') <p class="err">{{ $message }}</p> @enderror
                </div>
                <div class="full">
                    <label for="ubicacion_inmueble">Ubicacion del inmueble</label>
                    <input id="ubicacion_inmueble" name="ubicacion_inmueble" type="text" value="{{ old('ubicacion_inmueble', $remate?->ubicacion_inmueble) }}" required>
                    @error('ubicacion_inmueble') <p class="err">{{ $message }}</p> @enderror
                </div>
            </div>

            <h3>Tasaciones</h3>
            <div id="tasaciones-wrap">
                @php
                    $oldTasaciones = old('tasaciones', $tasaciones);
                @endphp
                @foreach($oldTasaciones as $index => $tasacion)
                    <div class="row tasacion-row">
                        <div>
                            <label>Precio base</label>
                            <input type="number" step="0.01" min="0" name="tasaciones[{{ $index }}][precio_base]" value="{{ $tasacion['precio_base'] ?? '' }}" required>
                        </div>
                        <div>
                            <label>Fecha</label>
                            <input type="date" name="tasaciones[{{ $index }}][fecha]" value="{{ $tasacion['fecha'] ?? '' }}" required>
                        </div>
                        <button type="button" class="btn alt remove-row">Quitar</button>
                    </div>
                @endforeach
            </div>
            @error('tasaciones') <p class="err">{{ $message }}</p> @enderror
            @error('tasaciones.*.precio_base') <p class="err">{{ $message }}</p> @enderror
            @error('tasaciones.*.fecha') <p class="err">{{ $message }}</p> @enderror

            <button type="button" id="add-tasacion" class="btn alt">Agregar tasacion</button>

            <div class="actions">
                <button class="btn" type="submit">Guardar</button>
                <a class="btn alt" href="{{ route('admin.remates.index') }}">Volver</a>
            </div>
        </form>
    </div>
</div>
<script>
    (function () {
        const wrap = document.getElementById('tasaciones-wrap');
        const addBtn = document.getElementById('add-tasacion');

        function normalizeIndexes() {
            const rows = wrap.querySelectorAll('.tasacion-row');
            rows.forEach((row, i) => {
                const price = row.querySelector('input[name*="[precio_base]"]');
                const date = row.querySelector('input[name*="[fecha]"]');
                price.name = `tasaciones[${i}][precio_base]`;
                date.name = `tasaciones[${i}][fecha]`;
            });
        }

        function bindRemove(btn) {
            btn.addEventListener('click', function () {
                if (wrap.querySelectorAll('.tasacion-row').length === 1) {
                    return;
                }
                btn.closest('.tasacion-row').remove();
                normalizeIndexes();
            });
        }

        wrap.querySelectorAll('.remove-row').forEach(bindRemove);

        addBtn.addEventListener('click', function () {
            const idx = wrap.querySelectorAll('.tasacion-row').length;
            const div = document.createElement('div');
            div.className = 'row tasacion-row';
            div.innerHTML = `
                <div>
                    <label>Precio base</label>
                    <input type="number" step="0.01" min="0" name="tasaciones[${idx}][precio_base]" required>
                </div>
                <div>
                    <label>Fecha</label>
                    <input type="date" name="tasaciones[${idx}][fecha]" required>
                </div>
                <button type="button" class="btn alt remove-row">Quitar</button>
            `;
            wrap.appendChild(div);
            bindRemove(div.querySelector('.remove-row'));
        });
    })();
</script>
</body>
</html>
