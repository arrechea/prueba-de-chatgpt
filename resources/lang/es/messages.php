<?php

return [
    'not-found'                  => 'Not found.',
    'internal-error'             => 'Internal server error.',
    'unauthorized-access'        => 'Unathorized access',
    'unauthorized-access-role'   => 'Unathorized role access',
    'account-inactive'           => 'Restricted! You account is inactive.',
    'parameters-fail-validation' => 'Fail to pass validation',
    'SOMETHING_WENT_WRONG'       => 'Something went wrong!',


    'api-add'      => 'Api added successfully',
    'category-add' => 'Category added successfully',

    'admin-profile-update' => ' Your profile has successfully been updated',

    'role-add'     => 'Role added successfully',
    'role-update'  => 'Role updated successfully',
    'role-distroy' => 'Role removed successfully',

    'permission-add'     => 'Permission added successfully',
    'permission-update'  => 'Permission updated successfully',
    'permission-distroy' => 'Permission removed successfully',

    'admin-add'         => 'Administrator added successfully',
    'admin-empty-list'  => 'Please add an administator.',
    'admin-update'      => 'Administrator updated successfully',
    'admin-distroy'     => 'Administrator removed successfully',
    'admin-status'      => "Administrator's account (:status)status updated successfully",
    'admin-update-fail' => 'Fail to update the record!',


    'user-add'         => 'User added successfully',
    'user-update'      => 'User updated successfully',
    'user-distroy'     => 'User removed successfully',
    'user-status'      => "User's account (:status)status updated successfully",
    'user-update-fail' => 'Fail to update the record!',
    'user-not-found'   => 'No user found with this ID!',

    'delete-company'       => '¿Desea eliminar esta compañía?',
    'delete-brand'         => '¿Desea eliminar esta Marca?',
    'delete-studio'        => '¿Desea eliminar esta Ubicación?',
    'delete-user'          => '¿Desea eliminar este usuario?',
    'delete-user-category' => '¿Desea eliminar esta categoría de usuario?',
    'delete-admin'         => '¿Desea eliminar este administrador?',
    'delete-service'       => '¿Desea eliminar este servicio?',
    'delete-offer'         => '¿Desea eliminar esta promoción?',
    'delete-room'          => '¿Desea eliminar este salón?',
    'delete-staff'         => '¿Desea eliminar este instructor?',
    'delete-credits'       => '¿Desea eliminar estos creditos?',
    'delete-membership'    => '¿Desea eliminar esta membresía?',
    'delete-reservation'   => '¿Desea eliminar esta reserva?',
    'delete-mail'            => '¿Desea eliminar la informacion de este correo?',
    'delete-meeting'         => '¿Desea eliminar esta clase?',
    'delete-waitlist'        => '¿Desea eliminar este usuario de la lista de espera?',
    'delete-user-credits'    => '¿Desea eliminar los créditos para este usuario?',
    'delete-user-membership' => '¿Desea eliminar esta membresía para este usuario?',

    'create-marketing' => '¿Qué tipo de producto desea agregar?',

    'undefined' => 'Indefinido',

    'rooms-empty' => 'No existen salones',

    'meeting-passed'                 => 'No se puede editar un horario que ya ha pasado.',

    //Custom error messages.
    'reservation-users_id'           => 'Su perfil de usuario no se encuentra activo en esta compañía. Contacte a un administrador.',
    'reservation-meetings_id'        => 'La fecha de la reunión ya ha pasado.',
    'reservation-meetings_id-exists' => 'La reunión solicitada no existe en esta ubicación.',
    'reservation-combos_id'          => 'El paquete buscado no se encuentra activo en esta compañía.',
    'reservation-memberships_id'     => 'La membresía buscada no se encuentra activa en esta compañía.',
    'reservation-payment_types_id'   => 'El tipo de pago no se encuentra activo en esta marca.',
    'reservation-credits_id'         => 'El tipo de crédito no se encuentra activo en esta compañía.',
    'reservation-room'               => 'El salón asignado a la clase no existe o está inactivo',
    'reservation-gift_card-taken'    => 'Lo sentimos, esta gift card ya existe.',

    'not-verified-email' => 'Su usuario no ha sido verificado. Por favor revise su correo electrónico, incluyendo su carpeta de spam.',

    'yes' => 'Sí',
    'no'  => 'No',

    'username-in-use'             => 'El email utilizado para el registro ya ha sido utilizado, prueba con otra dirección de correo electrónico, si has olvidado tu contraseña por favor accede a "Recuperar contraseña".',
    'username-not-found'          => 'El email utilizado para la actualización no existe en la compañía ligada a esta cuenta.',
    'user-password-not-confirmed' => 'Por favor, revisa que la contraseña y su confirmación coincidan. ',
    'user-password-short'         => 'Por favor introduce una contraseña con más de 5 caracteres, estos puede ser letras y/o números y/o caracteres especiales.',
    'user-not-verified'           => 'El correo electrónico utilizado para el registro está pendiente de validación. Revisa tu bandeja de entrada y/o de "Spam" para validarlo y poder ingresar a su perfil. ',
    'user-name-missing'           => 'Por favor, completa el campo ":attribute" para finalizar el registro. ',
    'user-email-missing'          => 'Por favor, complete el campo "Correo" con un email válido. ',

    'captcha-missing' => 'Debe de completar correctamente el reCAPTCHA para comprobar no es un robot. ',

    'invalid_mobile_token' => 'El token provisto desde la aplicación móvil es incorrecto',

    'invalid-data' => 'Los datos dados son inválidos.',

    'unblock-user' => '¿Desea desbloquear las reservas para este usuario?',

    'company-name-in-use' => 'El nombre de la Empresa ya ha sido utilizado, prueba con otro Nombre.',
    'company-slug-in-use' => 'El slug de la Empresa ya ha sido utilizado, prueba con otro slug.',

    'location-name-in-use' => 'El nombre indicado para la ubicación ya ha sido utilizado, por favor, utilice otro.',
];
