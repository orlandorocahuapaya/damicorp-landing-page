# Backend Laravel (DAMICOR)

## 1) Instalar dependencias

En este entorno se detecto que falta `ext-fileinfo` en PHP CLI.  
Habilitala en `C:\php\php.ini` (extension=fileinfo) y luego ejecuta:

```powershell
cd d:\DAMICOR\backend
composer install
```

## 2) Configurar entorno

```powershell
copy .env.example .env
php artisan key:generate
```

Ajusta en `.env`:

- `APP_URL`
- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`
- `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`
- `CONTACT_TO_EMAIL`
- `CONTACT_REDIRECT_URL` (por defecto `/contacto.html`)

## 3) Ejecutar servidor

```powershell
php artisan serve
```

Endpoint de formulario:

- `POST /contacto/enviar`
