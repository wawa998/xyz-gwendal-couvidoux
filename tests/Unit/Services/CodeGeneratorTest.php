<?php

namespace Tests\Unit\Services;

use App\Services\CodeGeneratorService;
use PHPUnit\Framework\TestCase;

class CodeGeneratorTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_single_code_generator(): void
    {
        $generator = new CodeGeneratorService;

        $this->assertIsString($generator->randomCode());
    }

    /**
     * A basic test example.
     */
    public function test_multiple_code_generator(): void
    {
        $generator = new CodeGeneratorService;

        $count  = 10;
        $result = $generator->generate($count);

        $this->assertIsArray($result);
        $this->assertCount($count, $result);
    }
}
