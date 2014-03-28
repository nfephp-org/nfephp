NFePHP
=================

> **OBS**: Talvez, antes de chegar aqui, você tenha encontrado o site <http://www.assembla.com/spaces/nfephp/> e talvez você esteja se perguntando qual a diferença entre o NFePHP hospedado lá e o NFePHP hospedado aqui. Veja [nossa FAQ](https://github.com/nfephp-org/nfephp/wiki/FAQ) para sanar esta dúvida.

NFePHP é uma API para gerenciamento das comunicações entre o emitente de NFe e os serviços dos SEFAZ estaduais. Inteiramente construído em PHP para rodar sob qualquer sistema operacional.
Para começar veja [nossas páginas Wiki](https://github.com/nfephp-org/nfephp/wiki).

Não deixe de se cadastrar no [grupo de discussão do NFePHP](http://groups.google.com/group/nfephp)!

Objetivo
-----
A API permite que um programa emissor de NFe se comunique com a SEFAZ. A API não foi criada para ela própria emitir a NFe tendo em vista a enorme quantidade de informações necessárias e as características e especificidades de cada emitente.

Dependências
-------
* Apache: <http://httpd.apache.org/>
* PHP 5.3+: <http://php.net>
* Bibliotecas de terceiros
 * FPDF: Usada para gerar o DANFE em PDF. Veja <http://www.fpdf.org/>.
 * PHPMailer: Usada para envio das NFe ao destinatário. Veja <http://code.google.com/a/apache-extras.org/p/phpmailer/>.
 * QRCode: Usada para NFCe - Danfe Simplificado para venda consumidor.
* Extensões PHP
 * SOAP: Nativo do PHP. Veja <http://br2.php.net/manual/book.soap.php>.
 * cURL: Normalmente já vem habilitado com o PHP 5.3+. Veja <http://br2.php.net/manual/book.curl.php> e <http://curl.haxx.se/>.
 * OpenSSL: Normalmente já vem habilitado com o PHP 5.3+. Veja <http://br2.php.net/manual/book.openssl.php> e <http://www.openssl.org/>.
 * mcrypt: Normalmente já vem habilitado com o PHP 5.3+. Veja <http://www.php.net/manual/book.mcrypt.php>.
 * imap: Normalmente já vem habilitado com o PHP 5.3+. Veja <http://www.php.net/manual/book.imap.php>
 * GD: Normalmente já vem habilitado com o PHP 5.3+. Veja <http://www.php.net/manual/book.image.php>
 * ZIP: Necessário para o download de NFe da SEFAZ. Veja <http://www.php.net/manual/en/book.zip.php>
 * Zlib: Necessária para descompactar NFe após download. Veja <http://www.php.net/manual/en/book.zlib.php>

Instalação
------
Para mais detalhes sobre a instalação, veja <https://github.com/nfephp-org/nfephp/wiki/Instalação>.

Quick start
-----
Clone o repositório com `git clone https://github.com/nfephp-org/nfephp.git` ou [baixe a última versão estável](https://github.com/nfephp-org/nfephp/downloads).

Versionamento
----------
Para fins de transparência e discernimento sobre nosso ciclo de lançamento, e procurando manter compatibilidade com versões anteriores, o número de versão da NFePHP 
será mantida, tanto quanto possível, respeitando o padrão abaixo.

As liberações serão numeradas com o seguinte formato:

`<major>.<minor>.<patch>`

E serão construídas com as seguintes orientações:

* Quebra de compatibilidade com versões anteriores, avança o `<major>`.
* Adição de novas funcionalidades sem quebrar compatibilidade com versões anteriores, avança o `<minor>`.
* Correção de bugs e outras alterações, avança `<patch>`.

Para mais informações, por favor visite <http://semver.org/>.

Desenvolvimento
-----------
Para todo o desenvolvimento, correções de bugs, inclusões e testes deverá ser usada branch `develop`. 
Na branch `master`estarão os coódigos considerados como estáveis.
Novas branchs poderão surgir em função das necessidades que se apresentarem, seja para manter versionamentos anteriores seja para estabelecer correções de bugs. Mas apenas essas duas branchs estabelecidas é que serão permanentente mantidas. 

Bug tracker
-----------
Encontrou um bug? Informe-nos aqui no GitHub!

<https://github.com/nfephp-org/nfephp/issues>

Mantenedores
-----------
* NFe  - `Roberto L. Machado`
* NFCe - `Roberto L. Machado`
* NFSe - `Hugo Cegana`
* CTe  - `Luciano Antony` `Roberto Spadim` 
* MDFe - `Leandro C. LOpes`
* CLe  - `não definido`

Pull Request
--------
Para que seu Pull Request seja aceito ele deve estar seguindo os padrões descritos neste documento <http://www.walkeralencar.com/PHPCodeStandards.pdf>
