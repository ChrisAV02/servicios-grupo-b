<?php
// TODO: ARCHIVO PARA NOTIFICAR REPARTIDORES. Simula el encolamiento de un mensaje push/SMS a un repartidor activo.

// ! Configuración de cabeceras requeridas para la API RESTful
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // * Habilita el acceso desde Postman o clientes web
header('Access-Control-Allow-Methods: POST');

// TODO: BLOQUE DE SIMULACIÓN DE DATOS
// ! IDs de repartidores registrados que pueden recibir notificaciones
$repartidores_activos = [
    501, 502, 503
];

// TODO: BLOQUE DE LECTURA DE ENTRADA
// ! Transformación del JSON entrante en un arreglo asociativo PHP
$input = json_decode(file_get_contents('php://input'), true);

// TODO: BLOQUE DE VALIDACIÓN DE CONTRATO (P02)
// ! Los tres campos son obligatorios según el diseño arquitectónico
if (!isset($input['id_repartidor']) || !isset($input['id_pedido']) || !isset($input['mensaje'])) {
    http_response_code(400); 
    echo json_encode([
        'error' => 'Parámetros incompletos o inválidos',
        'codigo' => 400
    ]);
    exit;
}

// TODO: BLOQUE DE LÓGICA DE NEGOCIO (P03)
// * Verificamos si el ID proporcionado está dentro del listado permitido (in_array)
if (!in_array($input['id_repartidor'], $repartidores_activos)) {
    http_response_code(404);
    echo json_encode([
        'error' => 'Repartidor no encontrado o inactivo',
        'codigo' => 404
    ]);
    exit; // ! Detenemos el flujo si el destinatario no es válido
}

// TODO: BLOQUE DE RESPUESTA EXITOSA (P01)
// * Retorna el estado asíncrono y la estampa de tiempo actual del servidor
http_response_code(200); 
echo json_encode([
    'estado_notificacion' => 'Enviada',
    'timestamp' => date('c') 
]);