<?php

namespace App\Services;

use Illuminate\Support\Str;

class CodeGeneratorService
{
    /**
     * Generate a single random code.
     */
    public function randomCode(): string
    {
        return strtoupper(implode('-', [
            Str::random(4),
            rand(101, 999),
            Str::random(4)
        ]));
    }

    /**
     * Generate multiple codes.
     */
    public function generate(int $count = 5): array
    {
        $codes = [];

        for ($i = 1; $i <= $count; $i++) {
            $codes[] = $this->randomCode();
        }

        return $codes;
    }
}
