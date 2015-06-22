NFePHP
=================

[![Build Status](https://travis-ci.org/nfephp-org/nfephp.svg?branch=develop)](https://travis-ci.org/nfephp-org/nfephp)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nfephp-org/nfephp/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/nfephp-org/nfephp/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/nfephp-org/nfephp/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/nfephp-org/nfephp/?branch=develop)
[![License](https://poser.pugx.org/nfephp-org/nfephp/license.svg)](https://packagist.org/packages/nfephp-org/nfephp)
[![Latest Unstable Version](https://poser.pugx.org/nfephp-org/nfephp/v/unstable.svg)](https://packagist.org/packages/nfephp-org/nfephp)

NFePHP é uma API para gerenciamento das comunicações entre o emitente de NFe e os serviços dos SEFAZ estaduais. Inteiramente construído em PHP para rodar sob qualquer sistema operacional.
Para começar veja [nossas páginas Wiki](https://github.com/nfephp-org/nfephp/wiki).

Não deixe de se cadastrar no [grupo de discussão do NFePHP](http://groups.google.com/group/nfephp)!

Versão de Desenvolvimento
-----
Versão 4.0.0-dev (observa a versão de layout 3.10 da SEFAZ)

Condicionantes
-----
Para usar essa API é necessário conhecimento em programação PHP, bem como conhecer os padrões atuais da linguagem e ter bases de legislação fiscal. É extremanente recomendável que seja estudado o conteúdo dos seguintes sites.
* Documentação do Funcionamento do sistema de NFe [SEFAZ NFe](http://www.nfe.fazenda.gov.br/portal/principal.aspx)
* Documentação do Funcionamento do sistema de CTe [SEFAZ CTe](http://www.cte.fazenda.gov.br/listaSubMenu.aspx?Id=tW+YMyk/50s=)
* Documentação do Funcionamento do sistema de MDFe [SEFAZ MDfe](https://mdfe-portal.sefaz.rs.gov.br/)
* Composer [Documentação](https://getcomposer.org/doc/)  Construção do [composer.json](http://composer.json.jolicode.com/)
* IMPORTANTE [PHP do Jeito Certo](http://br.phptherightway.com/)
* Coding Style Guide [PSR-2](http://www.php-fig.org/psr/psr-2/)
* Autoload [PSR-4](http://www.php-fig.org/psr/psr-4/)

NOTA: A NFSe Nota Fiscal de Serviços Eletrônica, não tem padrão único, e a API tem somente alguns exemplos de montagem de um sistema para esse fim, mas nenhuma API realmente funcional para esse tipo de documentos fiscais.

Objetivo
-----
A API permite que um programa emissor de NFe se comunique com a SEFAZ. A API não foi criada para ela própria emitir a NFe tendo em vista a enorme quantidade de informações necessárias e as características e especificidades de cada emitente.

Dependências
-------
* composer <https://getcomposer.org/>
* Apache: <http://httpd.apache.org/>
* PHP 5.3+: <http://php.net>
* Bibliotecas de terceiros
 * FPDF: Provisóriamente usada para gerar os documentos em PDF. Veja <http://www.fpdf.org/>.Deverá ser substituida pela classe ZendPdf (devido ao tendimento dos padrões PSR e ser mais ativamente mantida e distribuida via composer.
 * zendframework/zendpdf (v. 2.x) Usada para gerar os documentos em PDF.
 * zendframework/zend-mail (v.2.x) Usada para envio dos emails aos destinátarios dos docuemntos fiscais eletrônicos.
 * zendframework/zend-barcode (v.2.x) Usada para gerar os codigos de barras 128 presente nos documentos fiscais em PDF.
 * soundasleep/html2text (v.0.2) Usada para converter as mensagens Htlm dos emails em seu equivalente em texto puro. Usada na classe de envio dos emails.
 * endroid/qrcode (v.1.x) Usada para gerar o QRCode impresso nas NFCe
* Extensões PHP
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
Clone o repositório com `git clone --branch=develop https://github.com/nfephp-org/nfephp.git` ou [baixe a última versão estável](https://github.com/nfephp-org/nfephp/downloads).

```sh
$ composer install
$ ./vendor/bin/phpunit
```

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
