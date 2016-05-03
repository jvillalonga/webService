<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class textsms {

  public function switchMensaje($data){
    switch ($data['codigo']) {
      case 'altaOk':
        return 'Se ha dado de alta. Se le ha cobrado '.$data['amount'].'$ por la suscripción.';
        break;

      case 'noAlta':
        return 'No tiene fondos suficientes para la suscripción.';
        break;

      case 'cobroOk':
        return 'Se le ha cobrado '.$data['amount'].'$ por la suscripción.';
        break;

      case 'noCobro':
        return 'No tiene fondos suficientes para la suscripción. Se le dará de baja.';
        break;

      case 'bajaOk':
        return 'Se ha dado de baja con éxito.';
        break;

      default:
        return 'Error. No se ha podido generar el mensaje';
        break;
    }

  }
}
