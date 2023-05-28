<?php declare(strict_types=1);

use PHPApp\php81\ClasseReadonly;
use PHPUnit\Framework\TestCase;


final class ClasseReadonlyTest extends TestCase
{

    public function testDeveImpedirAtribuicaoEmCamposReadonly(): void
    {
        $obj = new ClasseReadonly(1);
        $this->assertEquals($obj->campo,1);
        $this->expectException(Throwable::class);
        $obj->campo = 2; //lançará exceção!
    }

}

