<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;
require_once '../../config/db.php';

// --- FUNCIÓN PRINCIPAL DE NOTIFICACIÓN ---
function notifyCustomers($productId, $productName, $productUrl, $productImage) {
    try {
        // 2. Conexión y carga de variables
        $pdo = Database::conectar();
        
        
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

       
        $stmt = $pdo->prepare("SELECT id, email FROM lista_espera WHERE id_producto = ? AND estado = 'activo'");
        $stmt->execute([$productId]);
        $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($subscribers)) {
            return "No hay suscriptores para este producto.";
        }

        // 4. CONFIGURACIÓN PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Mailtrap usa STARTTLS en puerto 587 o 2525
        $mail->Port       = $_ENV['SMTP_PORT'];
        $mail->CharSet    = 'UTF-8';
            
        $mail->setFrom($_ENV['SMTP_USER'], 'Tienda Online');
        $mail->isHTML(true);
        $mail->Subject = "¡Ya llegó! $productName está disponible";

        // 5. ENVÍO MASIVO
        foreach ($subscribers as $user) {
            $email = $user['email'];
            
            $mail->clearAddresses();
            $mail->addAddress($email);

            $mail->Body = "
                <div style='font-family: sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                    <h2 style='color: #2c3e50;'>¡Hola! Excelentes noticias.</h2>
                    <p>El producto <strong>$productName</strong> que estabas esperando ya tiene unidades disponibles.</p>
                    <img src='$productImage' width='200' style='display:block; margin: 20px 0;' alt='$productName'>
                    <a href='$productUrl' style='background: #27ae60; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>IR A LA TIENDA</a>
                    <p style='margin-top: 20px;'><small>Recibes este correo porque te suscribiste a la lista de espera.</small></p>
                </div>
            ";
            
            if($mail->send()) {
                // 6. ACTUALIZAR ESTADO (Usando 'estado' como en tu SQL)
                $updateStmt = $pdo->prepare("UPDATE lista_espera SET estado = 'enviado' WHERE id = ?");
                $updateStmt->execute([$user['id']]);
                echo "Notificación enviada a: $email <br>";
            }
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>