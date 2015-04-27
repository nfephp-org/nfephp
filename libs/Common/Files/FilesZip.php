<?php

namespace NFePHP\Common\Files;

/**
 * Classe auxiliar para criar, listar e testar os diretórios utilizados pela API
 * @category   NFePHP
 * @package    NFePHP\Common\Files
 * @copyright  Copyright (c) 2008-2014
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Exception;

class FilesZip
{
    /**
     * unZipTmpFile
     * Descompacta strings GZIP usando arquivo temporário e SO
     * @param string $datazip Dados compactados com gzip
     * @return string arquivo descompactado
     * @throws Exception
     */
    public static function unZipTmpFile($datazip = '')
    {
        if (is_file($datazip)) {
            $data = file_get_contents($datazip);
        } else {
            $data = $datazip;
        }
        $uncompressed = '';
        //cria um nome para o arquivo temporario
        do {
            $tempName = uniqid('temp_');
        } while (file_exists($tempName));
        //grava a string compactada no arquivo temporário
        if (file_put_contents($tempName, $data)) {
            try {
                ob_start();
                //efetua a leitura do arquivo descompactando e jogando o resultado
                //bo cache
                @readgzfile($tempName);
                //descarrega o cache na variável
                $uncompressed = ob_get_clean();
            } catch (\Exception $e) {
                $ex = $e;
            }
            //remove o arquivo temporário
            if (file_exists($tempName)) {
                unlink($tempName);
            }
            if (isset($ex)) {
                throw new Exception\RuntimeException(
                    $ex
                );
            }
            //retorna a string descomprimida
            return $uncompressed;
        }
    }

    /**
     * unGZip
     * Descompacta dados compactados GZIP via PHP
     * @param string $data Dados compactados com gzip em uma string
     * @return mixed
     */
    public static function unGZip($data = '')
    {
        $len = strlen($data);
        if ($len < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b")) {
            throw new Exception\RuntimeException(
                "Não está no formato GZIP."
            );
        }
        $method = ord(substr($data, 2, 1));  // metodo de compressão
        $flags  = ord(substr($data, 3, 1));  // Flags
        if ($flags & 31 != $flags) {
            throw new Exception\RuntimeException(
                "Não são permitidos bits reservados."
            );
        }
        // NOTA: $mtime pode ser negativo (limitações nos inteiros do PHP)
        $mtime = unpack("V", substr($data, 4, 4));
        $mtime = $mtime[1];
        $headerlen = 10;
        $extralen  = 0;
        $extra     = "";
        if ($flags & 4) {
            // dados estras prefixados de 2-byte no cabeçalho
            if ($len - $headerlen - 2 < 8) {
                throw new Exception\RuntimeException(
                    "Dados inválidos."
                );
            }
            $extralen = unpack("v", substr($data, 8, 2));
            $extralen = $extralen[1];
            if ($len - $headerlen - 2 - $extralen < 8) {
                throw new Exception\RuntimeException(
                    "Dados inválidos."
                );
            }
            $extra = substr($data, 10, $extralen);
            $headerlen += 2 + $extralen;
        }
        $filenamelen = 0;
        $filename = "";
        if ($flags & 8) {
            // C-style string
            if ($len - $headerlen - 1 < 8) {
                $msg = "Dados inválidos.";
                $this->pSetError($msg);
                return false;
            }
            $filenamelen = strpos(substr($data, $headerlen), chr(0));
            if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
                throw new Exception\RuntimeException(
                    "Dados inválidos."
                );
            }
            $filename = substr($data, $headerlen, $filenamelen);
            $headerlen += $filenamelen + 1;
        }
        $commentlen = 0;
        $comment = "";
        if ($flags & 16) {
            // C-style string COMMENT data no cabeçalho
            if ($len - $headerlen - 1 < 8) {
                throw new Exception\RuntimeException(
                    "Dados inválidos."
                );
            }
            $commentlen = strpos(substr($data, $headerlen), chr(0));
            if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
                throw new Exception\RuntimeException(
                    "Formato de cabeçalho inválido."
                );
            }
            $comment = substr($data, $headerlen, $commentlen);
            $headerlen += $commentlen + 1;
        }
        $headercrc = "";
        if ($flags & 2) {
            // 2-bytes de menor ordem do CRC32 esta presente no cabeçalho
            if ($len - $headerlen - 2 < 8) {
                throw new Exception\RuntimeException(
                    "Dados inválidos."
                );
            }
            $calccrc = crc32(substr($data, 0, $headerlen)) & 0xffff;
            $headercrc = unpack("v", substr($data, $headerlen, 2));
            $headercrc = $headercrc[1];
            if ($headercrc != $calccrc) {
                throw new Exception\RuntimeException(
                    "Checksum do cabeçalho falhou."
                );
            }
            $headerlen += 2;
        }
        // Rodapé GZIP
        $datacrc = unpack("V", substr($data, -8, 4));
        $datacrc = sprintf('%u', $datacrc[1] & 0xFFFFFFFF);
        $isize = unpack("V", substr($data, -4));
        $isize = $isize[1];
        // decompressão
        $bodylen = $len-$headerlen-8;
        if ($bodylen < 1) {
            throw new Exception\RuntimeException(
                "BUG da implementação."
            );
        }
        $body = substr($data, $headerlen, $bodylen);
        $data = "";
        if ($bodylen > 0) {
            switch ($method) {
                case 8:
                    // Por hora somente é suportado esse metodo de compressão
                    $data = gzinflate($body, null);
                    break;
                default:
                    throw new Exception\RuntimeException(
                        "Método de compressão desconhecido (não suportado)."
                    );
            }
        }
        // conteudo zero-byte é permitido
        // Verificar CRC32
        $crc   = sprintf("%u", crc32($data));
        $crcOK = $crc == $datacrc;
        $lenOK = $isize == strlen($data);
        if (!$lenOK || !$crcOK) {
            $msg = ( $lenOK ? '' : 'Verificação do comprimento FALHOU. ').( $crcOK ? '' : 'Checksum FALHOU.');
            throw new Exception\RuntimeException(
                $msg
            );
        }
        return $data;
    }
    
    /**
     * compacta uma string usando Gzip
     * @param string $data
     * @return string
     */
    public static function gZipString($data = '')
    {
        return gzencode($data, 9, FORCE_GZIP);
    }
    
    /**
     * descompacta uma string usando Gzip
     * @param string $data
     * @return string
     */
    public static function unGZipString($data = '')
    {
        return gzdecode($data);
    }
    
    /**
     * compacta uma string usando ZLIB
     * @param string $data
     * @return string
     */
    public static function zipString($data = '')
    {
        return gzcompress($data, 9);
    }
}
