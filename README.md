# ChimboteFood - Grupo B

## Servicios SOA

El Grupo B implementa servicios para la plataforma ChimboteFood
utilizando PHP sin framework. Los servicios se exponen en un entorno local y cumplen con los principios de Arquitectura Orientada a Servicios (SOA).

---

# 1. Servicio: ProcesarPago

## Descripción
Servicio encargado de procesar el pago de un pedido. Verifica la existencia del pedido y simula la aprobación de la transacción, devolviendo un ID único.

## Endpoint
`POST /procesar_pago.php`

## Tipo de servicio
Básico (Síncrono)

## Principios SOA aplicados
- **Abstracción:** El consumidor desconoce la pasarela de pagos interna o la lógica de validación de base de datos.
- **Sin Estado (Stateless):** Cada solicitud contiene toda la información necesaria (id, monto, método) para procesarse, sin depender de sesiones activas.

## Content-Type
`application/json`

## Entrada
El servicio recibe un objeto JSON con los siguientes parámetros:

| Parámetro | Tipo | Obligatorio | Descripción |
|---|---|---|---|
| `id_pedido` | integer | Sí | Identificador del pedido |
| `monto` | number | Sí | Monto total a pagar |
| `metodo_pago` | string | Sí | Método utilizado para realizar el pago |

### Ejemplo de solicitud (Entrada)
```json
{
  "id_pedido": 1024,
  "monto": 45.50,
  "metodo_pago": "TARJETA_CREDITO"
}
```

### Formato de salida - Éxito (HTTP 200)
```json
{
  "estado_pago": "APROBADO",
  "id_transaccion": "TXN-987654321"
}
```

### Formato de salida - Error (HTTP 400 / 404)
```json
{
  "error": "Parámetros incompletos o inválidos",
  "codigo": 400
}
```

---

# 2. Servicio: NotificarRepartidor

## Descripción
Servicio encargado de enviar notificaciones (push/mensajes) al repartidor asignado a un pedido específico.

## Endpoint
`POST /notificar_repartidor.php`

## Tipo de servicio
Básico (Asíncrono)

## Principios SOA aplicados
- **Reusabilidad:** Este servicio de alertas puede ser invocado por distintos módulos (Soporte, Cancelaciones, Asignación inicial).
- **Bajo Acoplamiento:** El servicio de pagos o de orquestación no necesita saber cómo se envía el mensaje (WebSocket, SMS), solo delega la tarea enviando el payload.

## Content-Type
`application/json`

## Entrada
El servicio recibe un objeto JSON con los siguientes parámetros:

| Parámetro | Tipo | Obligatorio | Descripción |
|---|---|---|---|
| `id_repartidor` | integer | Sí | ID del repartidor en el sistema |
| `id_pedido` | integer | Sí | Identificador del pedido asociado |
| `mensaje` | string | Sí | Contenido de la alerta a enviar |

### Ejemplo de solicitud (Entrada)
```json
{
  "id_repartidor": 501,
  "id_pedido": 1024,
  "mensaje": "Nuevo pedido asignado en Restaurante X"
}
```

### Formato de salida - Éxito (HTTP 200)
```json
{
  "estado_notificacion": "Enviada",
  "timestamp": "2026-08-28T15:30:00Z"
}
```

### Formato de salida - Error (HTTP 400 / 404)
```json
{
  "error": "Repartidor no encontrado o inactivo",
  "codigo": 404
}
```