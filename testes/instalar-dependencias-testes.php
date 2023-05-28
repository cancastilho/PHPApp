<?php

$extensionsNecessarias = [
    'openssl',
    'mbstring'
];

$extensoesNaoCarregadas =[];

$VENDOR_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor';

foreach ($extensionsNecessarias as $ext){
    if(!extension_loaded($ext)){
        $extensoesNaoCarregadas[]=$ext;
    }
}
if(count($extensoesNaoCarregadas) > 0){
    echo "As seguintes extensões do php são necessárias: " . join(', ',$extensoesNaoCarregadas) . "\n";
    echo "Habilite-as no php.ini";
    die();
}


if(!file_exists($VENDOR_PATH)){
    mkdir($VENDOR_PATH);
}
if(!file_exists("$VENDOR_PATH/phpunit-10.phar")){
    echo "Baixando arquivo https://phar.phpunit.de/phpunit-10.phar para $VENDOR_PATH/phpunit-10.phar\n";
    copy('https://phar.phpunit.de/phpunit-10.phar', "$VENDOR_PATH/phpunit-10.phar");
}
if(file_exists("$VENDOR_PATH/php-unit")){
    echo "Diretório já existe: $VENDOR_PATH/php-unit";
    echo "phpunit-10.phar não será extraido novamente. Saindo.";
    exit();
}

try {
    echo "Extraindo arquivo $VENDOR_PATH/phpunit-10.phar para vscode reconhecer reconhecer dependências.";
    $phar = new Phar("$VENDOR_PATH/phpunit-10.phar");
    $phar->extractTo("$VENDOR_PATH/php-unit"); // extrai todos os arquivos
} catch (Exception $e) {
    echo "Erro ao extrair arquivo .phar. " . $e->getMessage();
}
