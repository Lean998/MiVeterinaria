<?php

namespace App\Validation;

class ValidationTelefono{
    public function telefono_valido($telefono){
        if ($telefono === null) {
            return false;
        }
        if (!is_string($telefono)) {
            $telefono = (string) $telefono;
        }
        if(preg_match('/^(\\+54)?[0-9]{10,12}$/', $telefono) !== 1){
            return false;
        }
        return true;
    }
}
