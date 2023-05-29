<?php

declare(strict_types=1);

use PHPApp\Ldap;
use PHPUnit\Framework\TestCase;

final class LdapTest extends TestCase
{
    public function testDeveConectarComServidorLdap(): void
    {
        $ldap = new Ldap();
        $this->assertTrue($ldap->testarConexao());
    }
    public function testDeveCriarUsuarioNoServidorLdapEDefinirSenha(): void
    {
        $ldap = new Ldap();
        $timestamp = time();
        $cn = 'Teste Ldap ' . $timestamp;
        $info["cn"] =  $cn;
        $info["sn"] = "Teste";
        $info["objectclass"] = "inetOrgPerson";
        $info["sAMAccountName"] = 'teste' . $timestamp;
        $info['userprincipalname'] = $info["sAMAccountName"] . '@' . $ldap->dominio;
        $userRn = 'cn=' . $cn;
        $resultado = $ldap->criarUsuario($userRn, $info);
        $this->assertTrue($resultado);
        $novaSenha = 'UmaSenhaForte30050';
        $userDn = 'cn=' . $cn . ',' . $ldap->ldapBaseDn;
        $resultado = $ldap->resetarSenha($userDn, $novaSenha);
        $this->assertTrue($resultado);
    }
    public function testDeveRemoverUsuariosDeTestDoServidor(): void
    {
        $ldap = new Ldap();
        $userDn='CN=Teste Ldap 1685336363,DC=cancastilho,DC=local';
        $resultado = $ldap->deletar($userDn);
        $this->assertTrue($resultado);
    }
    public function testDeveBuscarUsuariosNoServidorLdap(): void
    {
        $ldap = new Ldap();
        $userDn='CN=Teste Ldap 1685336363,DC=cancastilho,DC=local';
        $filtro='(cn=Teste*)';
        $resultado = $ldap->buscar($filtro);
        $this->assertIsArray($resultado);
        $this->assertGreaterThan(0,  count($resultado));
        die(var_dump($resultado));
    }
}
