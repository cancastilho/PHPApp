<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class PHPUnitTest extends TestCase
{
    public function testDeveFazerAssertEmptyComValores(): void
    {
        $this->assertEmpty(null); 
        $this->assertEmpty([]); 
        $this->assertEmpty(''); 
        $this->assertEmpty(0); 
        $this->assertEmpty(false); 
        $this->assertEmpty($variavelNaoDefinida); 
    }

    public function testDeveEsperarQueTesteLanceExcecao(): void
    {
        $this->expectException(Throwable::class);
        $divisaoPorZero = 1 / 0; // Lança exceção!
    }
    
}

