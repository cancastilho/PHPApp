<?php

namespace PHPApp\php81;

class ClasseReadonly {
    
    public readonly int $campo;

    public function __construct(int $campo) {
        $this->campo = $campo;
    }
}

