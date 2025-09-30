<?php

//Create an instance; passing `true` enables exceptions
$destinatario = "procesos@damicorperu.com";    

$username   =$_POST['username'];
$email       =$_POST['email'];
$numero     =$_POST['numero'];
$direccion  =$_POST['direccion'];
$mensaje    =$_POST['mensaje'];


$asunto = "Asunto del mensaje $email";

$contenido = "Este mensaje fue enviado por: $username \n";
$contenido .= "Email: $email \n";
$contenido .= "Número: $numero \n";
$contenido .= "Dirección: $direccion \n";
$contenido .= "Mensaje: $mensaje \n";


mail($destinatario,$asunto,$contenido);

header("Location:index.html");

/*

$username   =$_POST['username'];
$mail       =$_POST['email'];
$numero     =$_POST['numero'];
$direccion  =$_POST['direccion'];
$message    =$_POST['mensaje'];


$header  ='From: ' . $mail . "\r\n";
$header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$header .= "Mime-Version:1.0 \r\n";
$header .= "Content-Type: text/plain";


$message = "Este mensaje fue enviado por: " . $username. "\r\n";
$message .="Su e-mail es: " . $mail. "\r\n";
$message .="Teléfono de contacto: " . $numero. "\r\n";
$message .="Mensaje: " . $_POST['mensaje']. "\r\n";
$message .="Enviado el : ". date('d/m/Y', time());

$para = 'procesos@damicorperu.com';
$asunto = 'Asunto del mensaje';


mail($para, $asunto, $message, $header);


header("Location:index.html");

*/

?>

