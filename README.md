## php-app

Projeto de exemplo em php que inclui extensões para consulta em LDAP, teste unitário e cobertura de código.

Tecnologias envolvidas:

- PHP 8.2
- Apache Web Server
- Módulo ModSecurity (WAF)
- Docker
- PHPUnit

## Executando a aplicação em ambiente de desenvolvimento

O ambiente usado para manutenção dessa aplicação inclui:

- Windows 10
- VS Code
- Extensões vscode:
    - PHP Inteliphense
    - WSL
- WLS 2
- Docker instalado no WSL2
- Windows Terminal

```sh
# Faça uma cópia do arquivo /config/secrets.env.exemplo para /config/secrets.env.
# secrets.env não será versionado.

# Execute os comandos abaixo da raiz do projeto
# Faz build da imagem do app
./scripts/build.sh

# Executa o projeto usando o código contido na imagem docker
./scripts/executar-imagem.sh

# Executar o projeto sobreescrevendo o código da imagem docker pelo conteúdo código corrente.
# Facilita o desenvolvimento, por permitir visualizar as alterações imediatamente ao editar um arquivo.
./scripts/executar.sh
```

## Executando os testes

Certifique-se de que o executável do php esteja no PATH. 

Para desenvolver os testes no vscode sem que o intelisense indique erro nas classes do phpunit execute o comando abaixo:

````sh
cd testes
php instalar-dependecias-testes.php
# Caso ocorra erro, ative as extensões solicitadas (mbstring, openssl) no seu php.ini
# Será criado o diretório vendor com o phpunit-10.phar. O phpunit-10.phar será extraído lá dentro.
````

Para executar os testes do projeto siga as instruções abaixo. Não é necessário executar o procedimento anterior para isso.

```ps1
# Faça uma cópia do arquivo /config/secrets.env.exemplo para /config/secrets.env.
# secrets.env não será versionado.

# Faz build da imagem de teste
./scripts/build-testes.sh

# Executa a imagem de testes.
./scripts/testar.sh
```