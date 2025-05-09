<?php

namespace App\Validation;

class ValidationAlphaSpace{
    public function valid_alpha_space($string){
        if ($string === null) {
            return false;
        }
        if (!is_string($string)){
            $string = (string) $string;
        }
        if(preg_match('/^[A-Za-záÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ ]+$/u', $string) !== 1){
            return false;
        }
        return true;
    }
}