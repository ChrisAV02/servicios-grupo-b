<?php
// TODO: ARCHIVO PRINCIPAL PARA PROCESAR PAGOS. Recibe un JSON, valida los datos, busca en la BD simulada y retorna el estado de la transacción.

// ! Configuración estricta de cabeceras para interoperabilidad SOA
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // * Permite pruebas cruzadas en localhost
header('Access-Control-Allow-Methods: POST');

// TODO: BLOQUE DE SIMULACIÓN DE DATOS
// ! Arreglo hardcodeado requerido por el laboratorio (reemplaza a la BD real)
$pedidos_pendientes = [
    ['id_pedido' => 1024, 'estado' => 'PENDIENTE', 'total' => 45.50],
    ['id_pedido' => 1025, 'estado' => 'PENDIENTE', 'total' => 120.00]
];

// TODO: BLOQUE DE LECTURA DE ENTRADA
// ! Captura el payload directamente del stream de entrada
$input = json_decode(file_get_contents('php://input'), true);

// TODO: BLOQUE DE VALIDACIÓN DE CONTRATO (P02, P04)
// ! Se valida que los parámetros obligatorios estén presentes en el payload
if (!isset($input['id_pedido']) || !isset($input['monto']) || !isset($input['metodo_pago'])) {
    http_response_code(400); 
    echo json_encode([
        'error' => 'Parámetros incompletos o inválidos',
        'codigo' => 400
    ]);
    exit; // ! Detiene la ejecución si falla el contrato
}

// TODO: BLOQUE DE LÓGICA DE NEGOCIO (P03)
// * Iteramos para simular un SELECT en base de datos
$pedido_encontrado = false;
foreach ($pedidos_pendientes as $pedido) {
    if ($pedido['id_pedido'] == $input['id_pedido']) {
        $pedido_encontrado = true;
        break;
    }
}

// ! Rechaza la solicitud si el recurso no existe en nuestra "BD"
if (!$pedido_encontrado) {
    http_response_code(404);
    echo json_encode([
        'error' => 'Pedido no encontrado para pago',
        'codigo' => 404
    ]);
    exit;
}


// TODO: BLOQUE DE RESPUESTA EXITOSA (P01)
// * Generación dinámica del ID de transacción para mayor realismo
http_response_code(200);
echo json_encode([
    'estado_pago' => 'APROBADO',
    'id_transaccion' => 'TXN-' . strtoupper(uniqid()) 
]);