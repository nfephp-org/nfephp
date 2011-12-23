<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação 
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; sem mesmo a garantia explícita do VALOR COMERCIAL ou ADEQUAÇÃO PARA
 * UM PROPÓSITO EM PARTICULAR, veja a Licença Pública Geral GNU para mais
 * detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU junto com este
 * programa. Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3>.
 *
 * @package   NFePHP
 * @name      certificado
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * certificado
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFeTools_certificado {
    public $certificateFile;    // path/file do certificado p12 (pfx) tipo A1
    public $privateKeyFile;     // path/file da chave privada (nao precisa existir)
    public $publicKeyFile;      // path/file da chave publica (nao precisa existir)
    public $sPrivateKey;        // string da chave privada
    public $sPublicKey;         // string do certificado (chave publica)
    public $passKey;            // senha
    public $passPhrase;         // 

    function __construct($certificateFile=_NFE_CERTIFICATE_FILE) {

        $this->certificateFile  = $certificateFile;
        $this->privateKeyFile   = _NFE_PRIVATEKEY_FILE;
        $this->publicKeyFile    = _NFE_PUBLICKEY_FILE;

        $this->passKey          = _NFE_PASSKEY;
        $this->passPhrase       = _NFE_PASSPHRASE;

        openssl_pkcs12_read(file_get_contents($this->certificateFile), $x509cert, _NFE_PASSKEY);

        // chave publica (certificado)
        $aCert = explode("\n", $x509cert['cert']);
        foreach ($aCert as $curData) {
            if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
                $this->sPublicKey.= trim($curData);
            }
        }

        // chave privada
        $this->sPrivateKey = $x509cert['pkey'];


        if (!file_exists($this->privateKeyFile)) {
            file_put_contents($this->privateKeyFile, $x509cert['pkey']);
        }

        if (!file_exists($this->publicKeyFile)) {
            file_put_contents($this->publicKeyFile, $x509cert['cert']);
        }
    }

    function isValid() {
    }

}
