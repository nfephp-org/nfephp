<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      certificado
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * certificado
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
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
