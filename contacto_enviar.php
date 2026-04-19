<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contacto.html');
    exit;
}

$autoloadPaths = [
    __DIR__ . '/vendor/autoload.php',
    dirname(__DIR__) . '/vendor/autoload.php',
];

$autoloadLoaded = false;
foreach ($autoloadPaths as $autoloadPath) {
    if (file_exists($autoloadPath)) {
        require_once $autoloadPath;
        $autoloadLoaded = true;
        break;
    }
}

if (!$autoloadLoaded) {
    header('Location: contacto.html?status=error');
    exit;
}

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function clean_text(string $value): string
{
    return trim(str_replace(["\r", "\n"], ' ', $value));
}

function env_value(string $key, string $default = ''): string
{
    $value = getenv($key);
    return $value === false ? $default : trim((string) $value);
}

$nombre = clean_text($_POST['Nombre'] ?? '');
$correo = filter_var(trim($_POST['Correo'] ?? ''), FILTER_VALIDATE_EMAIL) ?: '';
$telefono = clean_text($_POST['Telefono'] ?? '');
$asuntoInput = clean_text($_POST['Asunto'] ?? '');
$mensaje = trim($_POST['Mensaje'] ?? '');

if ($nombre === '' || $correo === '' || $telefono === '' || $asuntoInput === '' || $mensaje === '') {
    header('Location: contacto.html?status=invalid');
    exit;
}

$smtpHost = env_value('SMTP_HOST');
$smtpPort = (int) env_value('SMTP_PORT', '587');
$smtpUser = env_value('SMTP_USER');
$smtpPass = env_value('SMTP_PASS');
$smtpSecure = strtolower(env_value('SMTP_SECURE', 'tls')); // tls | ssl | none

$toEmail = env_value('CONTACT_TO_EMAIL', 'procesos@damicorperu.com');
$fromEmail = env_value('CONTACT_FROM_EMAIL', $smtpUser !== '' ? $smtpUser : 'no-reply@damicorperu.com');
$fromName = env_value('CONTACT_FROM_NAME', 'DAMICOR Web');

if ($smtpHost === '' || $smtpPort <= 0 || $smtpUser === '' || $smtpPass === '') {
    header('Location: contacto.html?status=error');
    exit;
}

$subject = 'Formulario Web DAMICOR - ' . $asuntoInput;
$body = "Nombre: {$nombre}\n"
    . "Correo: {$correo}\n"
    . "Telefono: {$telefono}\n"
    . "Asunto: {$asuntoInput}\n\n"
    . "Mensaje:\n{$mensaje}\n";

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->Port = $smtpPort;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->CharSet = 'UTF-8';
    $mail->Timeout = 20;

    if ($smtpSecure === 'ssl') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } elseif ($smtpSecure === 'none') {
        $mail->SMTPSecure = '';
        $mail->SMTPAutoTLS = false;
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }

    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($toEmail);
    $mail->addReplyTo($correo, $nombre);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->isHTML(false);

    $mail->send();
    header('Location: contacto.html?status=ok');
    exit;
} catch (Exception $e) {
    header('Location: contacto.html?status=error');
    exit;
}
