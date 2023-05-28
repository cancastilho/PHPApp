<?php

declare(strict_types=1);

use PHPApp\Util;
use PHPUnit\Framework\TestCase;

final class UtilTest extends TestCase
{
    public function testDeveRetornarOla(): void
    {
        $this->assertEquals(Util::ola(), "Olá");
    }
}
