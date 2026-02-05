<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;
require_once __DIR__ . '/../config/db.php';

// --- FUNCIÓN PRINCIPAL DE NOTIFICACIÓN ---
function notifyCustomers($productId, $productName, $productUrl, $productImage) {
    try {
        // 2. Conexión y carga de variables
        $pdo = Database::conectar();
        
        
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
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

// --- FUNCIÓN PARA NOTIFICAR COMPRA ---
function notifyPurchase($userId, $userEmail, $userName, $orderDetails, $totalAmount, $orderId) {
    try {
        // Cargar variables de entorno
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        // CONFIGURACIÓN PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['SMTP_PORT'];
        $mail->CharSet    = 'UTF-8';
        
        $mail->setFrom($_ENV['SMTP_USER'], 'Tienda Online');
        $mail->addAddress($userEmail, $userName);
        $mail->isHTML(true);
        $mail->Subject = "¡Compra confirmada! Pedido #$orderId";

        // Construir tabla HTML de productos
        $productosHTML = "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
        $productosHTML .= "<tr style='background: #f0f0f0; border: 1px solid #ddd;'>";
        $productosHTML .= "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Producto</th>";
        $productosHTML .= "<th style='padding: 10px; text-align: center; border: 1px solid #ddd;'>Cantidad</th>";
        $productosHTML .= "<th style='padding: 10px; text-align: right; border: 1px solid #ddd;'>Precio</th>";
        $productosHTML .= "<th style='padding: 10px; text-align: right; border: 1px solid #ddd;'>Subtotal</th>";
        $productosHTML .= "</tr>";

        // Iterar sobre los detalles de la orden
        if (is_array($orderDetails)) {
            foreach ($orderDetails as $item) {
                $productName = $item['nombre'] ?? 'Producto';
                $cantidad = $item['cantidad'] ?? 0;
                $precio = $item['precio'] ?? 0;
                $subtotal = $cantidad * $precio;

                $productosHTML .= "<tr style='border: 1px solid #ddd;'>";
                $productosHTML .= "<td style='padding: 10px; border: 1px solid #ddd;'>$productName</td>";
                $productosHTML .= "<td style='padding: 10px; text-align: center; border: 1px solid #ddd;'>$cantidad</td>";
                $productosHTML .= "<td style='padding: 10px; text-align: right; border: 1px solid #ddd;'>\$" . number_format($precio, 2) . "</td>";
                $productosHTML .= "<td style='padding: 10px; text-align: right; border: 1px solid #ddd;'>\$" . number_format($subtotal, 2) . "</td>";
                $productosHTML .= "</tr>";
            }
        }

        $productosHTML .= "<tr style='background: #f9f9f9; font-weight: bold; border: 1px solid #ddd;'>";
        $productosHTML .= "<td colspan='3' style='padding: 10px; text-align: right; border: 1px solid #ddd;'>TOTAL:</td>";
        $productosHTML .= "<td style='padding: 10px; text-align: right; border: 1px solid #ddd;'>\$" . number_format($totalAmount, 2) . "</td>";
        $productosHTML .= "</tr>";
        $productosHTML .= "</table>";

        // Cuerpo del email
        $mail->Body = "
            <div style='font-family: sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 10px; max-width: 600px;'>
                <h2 style='color: #27ae60;'>¡Gracias por tu compra, $userName!</h2>
                <p>Tu pedido ha sido confirmado exitosamente.</p>
                
                <div style='background: #f0f0f0; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <p><strong>Número de Pedido:</strong> #$orderId</p>
                    <p><strong>Estado:</strong> Confirmado</p>
                </div>

                <h3 style='color: #2c3e50; margin-top: 30px;'>Detalles de tu compra:</h3>
                $productosHTML

                <div style='margin-top: 30px; padding: 15px; background: #e8f8f5; border-left: 4px solid #27ae60; border-radius: 3px;'>
                    <p><strong>¿Necesitas ayuda?</strong></p>
                    <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.</p>
                </div>

                <p style='margin-top: 30px; color: #7f8c8d; font-size: 12px;'>Este es un email automático, por favor no respondas a este mensaje.</p>
            </div>
        ";

        // Enviar email
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }

    } catch (Exception $e) {
        echo "Error al enviar notificación de compra: " . $e->getMessage();
        return false;
    }
}
?>