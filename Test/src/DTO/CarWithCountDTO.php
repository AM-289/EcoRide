<?php

namespace App\DTO;

class CategoryWithCountDTO {

    public function __construct(
        public readonly int $id,
        public readonly string $brand,
        public readonly int $count
    ) {
        
    }
}