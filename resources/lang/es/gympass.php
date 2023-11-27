<?php

return [
    'unknownError'           => 'Error desconocido',
    'gympassError'           => 'Error Gympass: :err',
    'internalError'          => 'Error interno Gympass [500]',
    'notValidReasonCategory' => 'La categoría de la razón de rechazo/cancelación no es válida.',
    'bookingGeneralError'    => 'No se ha podido agendar esta cita.',

    'checkinTitle'           => 'Gympass Check-in',
    'checkinStatus'          => 'Estatus',
    'checkinStatus.pending'  => 'Pendiente',
    'checkinStatus.rejected' => 'Rechazado',
    'checkinStatus.approved' => 'Aprobado',
    'checkinUser'            => 'Usuario',
    'checkinUserEmail'       => 'Email',
    'checkinApprove'         => 'Aprobar',
    'checkinReject'          => 'Rechazar',
    'Actions'                => 'Acciones',
    'checkinApproved'        => 'Se ha aprobado el check-in con éxito',
    'checkinRejected'        => 'Se ha rechazado el check-in con éxito',
    'checkinApprovalError'   => 'Ha ocurrido un error al procesar el check-in',
    'checkinRetryInGympass'  => 'Por favor vuelva a hacer el check-in desde la aplicación de Gympass.',

    'gympassSettings'  => 'Ajustes de Gympass',
    'activeLabel'      => 'Sincronizar con Gympass',
    'productionLabel'  => 'Gympass Producción',
    'production'       => 'Producción',
    'development'      => 'Desarrollo',
    'gymId'            => 'ID de Gympass',
    'slotId'           => 'Slot ID',
    'regenerateSlotID' => 'Regenerar el evento en Gympass. Esta acción eliminará cualquier clase en Gympass que tenga esta misma fecha y horario y también cancelará todas las reservas que se hayan hecho desde la aplicación de Gympass. Las reservas hechas desde Buq no se verán afectadas.',

    'active'   => 'Activo',
    'inactive' => 'Inactivo',

    'serviceActive'   => 'Sincronizar con Gympass',
    'serviceBookable' => 'Reservable en Gympass',
    'serviceVisible'  => 'Visible en Gympass',
    'serviceProduct'  => 'Producto en Gympass',
    'serviceClassId'  => 'ID de la clase en Gympass',

    'checkinApproveQuestion'    => '¿Desea aceptar este check-in?',
    'checkinRejectQuestion'     => '¿Desea rechazar este check-in?',
    'checkinAccept'             => 'Aceptar',
    'checkinRejectButton'       => 'Rechazar',
    'checkinMenuAdmin'          => 'Gympass Check-in',
    'checkinMenuPendingCheckin' => 'Check-ins Pendientes',

    'userGympassId' => 'ID de Gympass',

    'bookingGympassIdHeader'     => 'ID de Gympass',
    'bookingGympassNumberHeader' => 'Número de Reserva en Gympass',
    'bookingServiceId'           => 'Id en Gympass ',

    'locationGympassTitle'     => 'Ajustes de Gympass',
    'locationDaysBeforeWindow' => 'Días antes de la clase en que se permite hacer booking en Gympass',

    'checkinType_frontdesk'  => 'Validación en Frontdesk',
    'checkinType_automatic'  => 'Validación Automática',
    'checkinType_turnstile'  => 'Validación por Torniquete',
    'checkinType'            => 'Tipo de Validación de Check-in',
    'Errors'                 => 'Errores',
    'checkinCreationDate'    => 'Fecha de Creación',
    'checkinValidationDate'  => 'Fecha de Validación',
    'checkinManualRejection' => 'Check-in rechazado manualmente',

    'approved'                 => 'Aprobado',
    'unapproved'               => 'No Aprobado',
    'resendApprovalEmail'      => 'Reenviar E-mail de aprobación',
    'errorServiceNotInGympass' => 'El servicio seleccionado no se encuentra dado de alta en Gympass. La clase se ha guardado, pero no se ha sincronizado en Gympass.',

    'locationTitle' => 'Ubicación: :location',

    'gympassCredit' => 'Gympass',
];
