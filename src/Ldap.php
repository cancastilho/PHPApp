<?php
// basic sequence with LDAP is connect, bind, search, interpret search
// result, close connection
namespace PHPApp;

use Exception;

class Ldap
{

    public readonly string $ldapHost;
    public readonly string $ldapUser;
    public readonly string $ldapPort;
    public readonly string $ldapPortTls;
    public readonly string $ldapPassword;
    public readonly string $ldapBaseDn;
    public readonly string $dominio;

    public function __construct()
    {
        $this->ldapHost = getenv("LDAP_HOST");
        $this->ldapUser = getenv("LDAP_USER");
        $this->ldapPort = getenv("LDAP_PORT");
        $this->ldapPortTls = getenv("LDAP_PORT_TLS");
        $this->ldapPassword = getenv("LDAP_PASSWORD");
        $this->ldapBaseDn = getenv("LDAP_BASE_DN");
        $this->dominio = getenv("DOMINIO");
    }

    public function conectar()
    {
        $conectionString = "ldap://{$this->ldapHost}:{$this->ldapPort}";
        $conexao = ldap_connect($conectionString);
        ldap_set_option($conexao, LDAP_OPT_PROTOCOL_VERSION, 3);
        return $conexao;
    }



    public function conectarTLS()
    {
        $conectionString = "ldap://{$this->ldapHost}:{$this->ldapPort}";
        $conexao = ldap_connect($conectionString);
        ldap_set_option($conexao, LDAP_OPT_PROTOCOL_VERSION, 3);
        //ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
        ldap_set_option($conexao, LDAP_OPT_REFERRALS, 0);
        ldap_start_tls($conexao) or die('nao conectou com tls');
        return $conexao;
    }

    public function testarConexao()
    {
        echo "<h3>LDAP query test</h3>";
        echo "Connecting ...";;
        $conexao = $this->conectar();
        if ($conexao) {
            echo "Binding ...";
            $bindResult = ldap_bind($conexao, $this->ldapUser, $this->ldapPassword);
            if ($bindResult) {
                echo "Bind result is " . $bindResult . "<br />";
                $filtro = "cn=*admin";
                echo "Searching for ($filtro) ";
                $resultado = ldap_search($conexao,  $this->ldapBaseDn, $filtro);
                echo "Number of entries returned is " . ldap_count_entries($conexao, $resultado) . "<br />";
                echo "Getting entries ...<p>";
                $info = ldap_get_entries($conexao, $resultado);
                echo "Data for " . $info["count"] . " items returned:<p>";
                for ($i = 0; $i < $info["count"]; $i++) {
                    echo "dn is: " . $info[$i]["dn"] . "<br />";
                    echo "first cn entry is: " . $info[$i]["cn"][0] . "<br />";
                }
            } else {
                echo 'Não fez bind.';
                return false;
            }
            echo "Closing connection";
            ldap_close($conexao);
            return true;
        } else {
            echo "<h4>Não foi possível conectar ao LDAP.</h4>";
            return false;
        }
    }

    public function criarUsuario($userRn, $userInfo): bool
    {
        $conexao = $this->conectar();
        if ($conexao) {
            $bindResult = ldap_bind($conexao, $this->ldapUser, $this->ldapPassword);
            $userDn =  "$userRn,$this->ldapBaseDn";
            $resultado = ldap_add($conexao, $userDn, $userInfo);
            ldap_close($conexao);
            return $resultado;
        } else {
            echo "Unable to connect to LDAP server";
            return false;
        }
    }

    public static function adifyPw($newPassword)
    {
        return iconv("UTF-8", "UTF-16LE", '"' . $newPassword . '"');
        // $newPassword = '"' . $newPassword . '"';
        // $len = strlen($newPassword);
        // $newPassw = "";
        // for ($i = 0; $i < $len; $i++) {
        //     $newPassw .= "{$newPassword[$i]}\000";
        // }
        // return $newPassw;
    }

    public function buscar($filtro = '(objectclass=*)', $atributosARetornar = array('dn'))
    {
        $conexao = $this->conectar();
        $bindResult = ldap_bind($conexao, $this->ldapUser, $this->ldapPassword);
        if (!$bindResult) {
            throw new Exception('Não foi possível autenticar no servidor LDAP.');
        };
        $resultado = ldap_read($conexao, $this->ldapBaseDn, $filtro, $atributosARetornar);
        if ($resultado === false) {
            throw new Exception('Não foi possível fazer busca no servidor LDAP.');
        }
        $registros = ldap_get_entries($conexao, $resultado);
        return $registros;
    }

    public function deletar($userDn)
    {
        $conexao = $this->conectar();
        $bindResult = ldap_bind($conexao, $this->ldapUser, $this->ldapPassword);
        $resultado = ldap_delete($conexao, $userDn);
        return $resultado;
    }

    public function resetarSenha($userDn, $novaSenha): bool
    {
        //AD won't allow you to change a password over LDAP 389.
        //https://learn.microsoft.com/en-us/troubleshoot/windows-server/identity/change-windows-active-directory-user-password
        //https://groups.google.com/g/perl.ldap/c/eeYB2ceiZEE
        $conexao = $this->conectarTLS();
        $bindResult = ldap_bind($conexao, $this->ldapUser, $this->ldapPassword);
        if (!$bindResult) {
            throw new Exception('Não foi possível fazer o bind.');
        }
        //  $novaSenhaEncodada = mb_convert_encoding($novaSenha, "UTF-16LE");
        $entry = [
            [
                'attrib' => 'unicodepwd',
                'modtype' => LDAP_MODIFY_BATCH_REPLACE,
                'values' => array(self::adifyPw($novaSenha))
            ],
            // se usuário deve alterar a senha no primeiro logon.
            // [
            //     'attrib'  => 'pwdlastset', 'modtype' => LDAP_MODIFY_BATCH_REPLACE, 'values'  => array('0')
            // ]
        ];
        $resultado = ldap_modify_batch($conexao, $userDn, $entry);
        if (!$resultado) {
            echo "\nldap_error: " . ldap_error($conexao);
            ldap_get_option($conexao, LDAP_OPT_DIAGNOSTIC_MESSAGE, $err);
            echo "\nldap_get_option: $err";
        }
        ldap_close($conexao);
        return $resultado;
    }
}
