<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- FUNCIÓN PRINCIPAL DE NOTIFICACIÓN ---
function notifyCustomers($productId, $productName, $productUrl, $productImage) {
    $pdo = getDB();
    $mail = new PHPMailer(true);

    require 'vendor/autoload.php';

    // Cargar variables desde el archivo .env
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['SMTP_PORT'];
            
        $mail->setFrom('ventas@miecommerce.com', 'Mi Ecommerce');
        $mail->isHTML(true);
        $mail->Subject = "¡Ya llegó! $productName está disponible";

        // 3. Recorrer usuarios y enviar correos
        foreach ($subscribers as $user) {
            $email = $user['email'];
            
            // Limpiar destinatarios anteriores
            $mail->clearAddresses();
            $mail->addAddress($email);

            // Contenido del correo
            $bodyContent = "
                <div style='font-family: sans-serif; padding: 20px; border: 1px solid #ddd;'>
                    <h2>¡Hola! Buenas noticias.</h2>
                    <p>El producto <strong>$productName</strong> ya tiene stock.</p>
                    <img src='$productImage' width='150' style='display:block; margin: 10px 0;' alt='$productName'>
                    <a href='$productUrl' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Comprar ahora</a>
                    <p><small>Este es un aviso automático.</small></p>
                </div>
            ";
            
            $mail->Body = $bodyContent;
            $mail->send();

            // 4. Actualizar estado en Base de Datos para no reenviar
            $updateStmt = $pdo->prepare("UPDATE stock_alerts SET status = 'sent' WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            
            echo "Correo enviado a: $email <br>";
        }

    } catch (Exception $e) {
        echo "Error al enviar correo: {$mail->ErrorInfo}";
    }
}
?>