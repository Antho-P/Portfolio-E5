<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);
    
    $to = "petricca.anthony@gmail.com"; //  Email 
    $subject = "Nouveau message de $name";
    $body = "Nom: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: $email\r\nReply-To: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Votre message a été envoyé avec succès !');</script>";
    } else {
        echo "<script>alert('Erreur lors de l'envoi du message.');</script>";
    }
}
?>
