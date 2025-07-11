<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['reply' => 'Debes iniciar sesión para usar el chatbot.']);
    exit;
}

$cliente_id = $_SESSION['id'];
$userMessage = json_decode(file_get_contents('php://input'), true)['message'] ?? '';

if (!$userMessage) {
    echo json_encode(['reply' => 'Mensaje vacío recibido.']);
    exit;
}

$enlace = mysqli_connect("localhost", "root", "", "galaxy_travel");
if (!$enlace) {
    echo json_encode(['reply' => 'Error de conexión con la base de datos.']);
    exit;
}

// ------------------------
// Aqui primero se verifica si esta esperando algun id de un mensaje anterior
// ------------------------
if (isset($_SESSION['reservacion_estado']) && $_SESSION['reservacion_estado'] === 'esperando_paquete') {
    if (!is_numeric($userMessage)) {
        unset($_SESSION['reservacion_estado']);
        echo json_encode(['reply' => "❌ No entendí el ID del paquete. Escribe 'reservar' para intentar de nuevo."]);
        exit;
    }

    $paquete_id = (int) trim($userMessage);
    $consulta = "SELECT * FROM paquetes WHERE id = $paquete_id AND cupos_disponibles > 0";
    $resultado = mysqli_query($enlace, $consulta);

    if (mysqli_num_rows($resultado) === 1) {
        $paquete = mysqli_fetch_assoc($resultado);
        $insert = "INSERT INTO reservaciones (cliente_id, paquete_id, estado, fecha_reserva)
                   VALUES ($cliente_id, $paquete_id, 'pendiente', NOW())";
        mysqli_query($enlace, $insert);
        mysqli_query($enlace, "UPDATE paquetes SET cupos_disponibles = cupos_disponibles - 1 WHERE id = $paquete_id");
        unset($_SESSION['reservacion_estado']);

        echo json_encode(['reply' => "🎉 ¡Reservación realizada exitosamente para el paquete \"{$paquete['nombre']}\"!"]);
        exit;
    } else {
        unset($_SESSION['reservacion_estado']);
        echo json_encode(['reply' => "❌ El paquete con ID $paquete_id no está disponible. Escribe 'reservar' para ver otros."]);
        exit;
    }
}

// ------------------------
// aca estoy haciendo la conexion completa a los servidores de gemini
// ------------------------

$apiKey = 'AIzaSyDImtnxsmIrGdgpy0XbmBq0u96fS7_fzYo'; 
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey";

$promptSistema = <<<PROMPT
Eres Astroneer, asistente oficial de Galaxy Travel. Tu trabajo es detectar intenciones de los clientes.

Si el usuario quiere:
- Hacer una reservación → responde exactamente: INTENT: RESERVAR
- Ver sus reservaciones → responde exactamente: INTENT: VER_RESERVAS
- Cancelar una reservación (por ID) → responde exactamente: INTENT: CANCELAR [ID]

Ejemplo: INTENT: CANCELAR 3

En todos los demás casos, responde normalmente con información sobre turismo espacial, paquetes y destinos(si intenta hacer una pregunta no relacionada con el tema de viajes espaciales,indica amablemente que solamente hablas sobre viajes espaciales). 
PROMPT;

$data = [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [[ 'text' => $promptSistema ]]
        ],
        [
            'role' => 'user',
            'parts' => [[ 'text' => $userMessage ]]
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if (!$response) {
    echo json_encode(['reply' => "Error de red o cURL: $error"]);
    exit;
}

$result = json_decode($response, true);

// Si hay error explícito en la respuesta
if (isset($result['error'])) {
    $msg = $result['error']['message'] ?? 'Error desconocido de la API';
    
    if (stripos($msg, 'overloaded') !== false) {
        echo json_encode(['reply' => "🤖Estoy sobrecargado ya que existen muchas solicitudes a la vez. Por favor, intenta de nuevo en unos minutos."]);
    } else {
        echo json_encode(['reply' => "❌ Error API: $msg"]);
    }
    exit;
}


// Si no se obtuvo el texto esperado
if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    echo json_encode(['reply' => "⚠️ Estructura inesperada. Verifica modelo/API key."]);
    exit;
}

$reply = $result['candidates'][0]['content']['parts'][0]['text'];

// ------------------------
// las intenciones identificadas del usuario
// ------------------------

if (stripos($reply, 'INTENT: RESERVAR') !== false) {
    $_SESSION['reservacion_estado'] = 'esperando_paquete';

    $query = "SELECT id, nombre, fecha_salida, precio_total, cupos_disponibles
              FROM paquetes
              WHERE cupos_disponibles > 0";
    $result = mysqli_query($enlace, $query);

    if (mysqli_num_rows($result) == 0) {
        unset($_SESSION['reservacion_estado']);
        echo json_encode(['reply' => 'No hay paquetes disponibles actualmente.']);
        exit;
    }

    $paquetes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $paquetes[] = "ID: {$row['id']} | {$row['nombre']} - Fecha: {$row['fecha_salida']} - Cupos: {$row['cupos_disponibles']} - Precio: {$row['precio_total']}$";
    }

    $respuesta = "¡Aquí tienes nuestros paquetes disponibles!\n\n" . implode("\n", $paquetes);
    $respuesta .= "\n\nEscribe el ID del paquete que deseas reservar.";
    echo json_encode(['reply' => nl2br($respuesta)]);
    exit;
}

if (stripos($reply, 'INTENT: VER_RESERVAS') !== false) {
    $consulta = "SELECT r.id, p.nombre, p.fecha_salida, r.estado
                 FROM reservaciones r
                 JOIN paquetes p ON r.paquete_id = p.id
                 WHERE r.cliente_id = $cliente_id";

    $resultado = mysqli_query($enlace, $consulta);

    if (mysqli_num_rows($resultado) > 0) {
        $mensajes = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $mensajes[] = "🔹 Reservación #{$fila['id']} - {$fila['nombre']} | Fecha: {$fila['fecha_salida']} | Estado: {$fila['estado']}";
        }
        echo json_encode(['reply' => implode("\n", $mensajes)]);
    } else {
        echo json_encode(['reply' => 'No tienes reservaciones registradas.']);
    }
    exit;
}

if (preg_match('/INTENT:\s*CANCELAR\s+(\d+)/i', $reply, $match)) {
    $reserva_id = (int)$match[1];
    $consulta = "SELECT * FROM reservaciones WHERE id = $reserva_id AND cliente_id = $cliente_id";
    $resultado = mysqli_query($enlace, $consulta);

    if (mysqli_num_rows($resultado) == 1) {
        $row = mysqli_fetch_assoc($resultado);
        $paquete_id = $row['paquete_id'];

        mysqli_query($enlace, "DELETE FROM reservaciones WHERE id = $reserva_id");
        mysqli_query($enlace, "UPDATE paquetes SET cupos_disponibles = cupos_disponibles + 1 WHERE id = $paquete_id");

        echo json_encode(['reply' => "✅ Reservación #$reserva_id cancelada exitosamente."]);
    } else {
        echo json_encode(['reply' => "❌ No se encontró ninguna reservación con el ID $reserva_id asociada a tu cuenta."]);
    }
    exit;
}

// ------------------------
// y respuesta normal de un chatbot si no existe ninguna intencion
// ------------------------
echo json_encode(['reply' => $reply]);
?>
