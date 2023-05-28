<?php

# Baseado em: https://www.php.net/manual/pt_BR/language.oop5.autoload.php#120258
class Autoloader {

    public const APP_NAMESPACE = 'PHPApp';


    public static function register(){
        spl_autoload_register(function($nomeClasse){

            # normaliza path. Substitui \ por /.
            $file = str_replace('\\', DIRECTORY_SEPARATOR , $nomeClasse);

            # substitui namespace PHPApp pelo path do diretório src
            $file = str_replace(self::APP_NAMESPACE, __DIR__ , $file).'.php';

            if (file_exists($file)) {
                require_once $file;
                return true;
            }
            return false;

        });
    }
}

Autoloader::register();
