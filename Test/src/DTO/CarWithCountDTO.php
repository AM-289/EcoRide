<?php

namespace App\DTO;

class CarWithCountDTO {

    public function __construct(
        public readonly int $id,
        public readonly string $brand,
        public readonly string $energy,
        public readonly int $count
    ) {
        
    }
}