# NFePHP

[![Build Status](https://travis-ci.org/nfephp-org/nfephp.svg)](https://travis-ci.org/nfephp-org/nfephp)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nfephp-org/nfephp/badges/quality-score.png)](https://scrutinizer-ci.com/g/nfephp-org/nfephp/)
[![Code Coverage](https://scrutinizer-ci.com/g/nfephp-org/nfephp/badges/coverage.png)](https://scrutinizer-ci.com/g/nfephp-org/nfephp/)
[![License](https://poser.pugx.org/nfephp-org/nfephp/license.svg)](https://packagist.org/packages/nfephp-org/nfephp)
[![Latest Stable Version](https://poser.pugx.org/nfephp-org/nfephp/v/stable.svg)](https://packagist.org/packages/nfephp-org/nfephp)
[![Latest Unstable Version](https://poser.pugx.org/nfephp-org/nfephp/v/unstable.svg)](https://packagist.org/packages/nfephp-org/nfephp)
[![Total Downloads](https://poser.pugx.org/nfephp-org/nfephp/downloads)](https://packagist.org/packages/nfephp-org/nfephp)

NFePHP é uma API para gerenciamento das comunicações entre o emitente de NFe e os serviços dos SEFAZ estaduais. Inteiramente construído em PHP para rodar sob qualquer sistema operacional.
Para começar veja [nossas páginas Wiki](https://github.com/nfephp-org/nfephp/wiki).

Não deixe de se cadastrar no [grupo de discussão do NFePHP](http://groups.google.com/group/nfephp)!

## PULL REQUESTS
**Srs. neste repositório somente serão aceitos "PULL REQUESTS" relativos a BUGS e correções derivadas de mudanças promovidas pelas SEFAZ.**

Não mais serão aceitas alterações, melhorias no código ou inclusões de novos recursos ou de novos serviços, todas essas melhorias deverão ser encaminhadas para o novo repositório SPED-XXX.

>Em breve (até meados de 2017), este repositório deixará de receber qualquer contribuição e será descontinuado, em favor dos novos repositórios !!!
>Para manter a integridade da API nessa nova versão (4.1.x-dev), estão sendo mantidos os "namespaces", as chamadas de métodos e seus parametros, que não deverão ser alterados a não ser por motivo de "força maior", como mudanças da SEFAZ que forcem essa situação.
>No uso da nova versão, atenção deve ser dedicada a nomenclatura de classes, que foi simplificada, e nos recursos como impressão que foram deslocados para outro repositório.  


## REESTRUTURAÇÃO DE REPOSITÓRIOS

**As estruturas de CTe, MDFe e outras foram removidas deste repositório e levadas a seus novos repositórios veja:**

[SPED NFe](https://github.com/nfephp-org/sped-nfe) Novo repositório da classes de NFe (em fase de testes)

[SPED CTe](https://github.com/nfephp-org/sped-cte) Novo repositório das classes de CTe (em desenvolvimento)

[SPED MDFe](https://github.com/nfephp-org/sped-mdfe) Novo repositório das classes de MDFe (em desenvolvimento)

[SPED NFSe](https://github.com/nfephp-org/sped-nfse) Novo repositório das classes de NFSe (em desenvolvimento)

Os demais componentes também terão repositórios novos, mas por ora ainda permanecem neste.

[SPED COMMON](https://github.com/nfephp-org/sped-common) Novo repositório das classes comuns usadas por todos ou vários projetos. 

[SPED DA](https://github.com/nfephp-org/sped-da) Novo repositório das classes que geram a impressão dos documentos. 

[POSPRINT](https://github.com/nfephp-org/posprint) Framework para impressão com impressoras térmicas POS (em desenvolvimento)

[SPED GNRE](https://github.com/nfephp-org/sped-gnre) Repositório da classes de GNRE (versão estável)

Além desses outros repositórios estão em construção ou já dispõem de bibliotecas

[SPED EFD](https://github.com/nfephp-org/sped-efinanceira) Repositório da classes de Sped EFD fiscal  (em desenvolvimento)

[SPED RESTFUL](https://github.com/nfephp-org/sped-restful) Aplicativo RestFul para geração de documentos Sped (em desenvolvimento)

[SPED CONSOLE](https://github.com/nfephp-org/sped-console) Conjunto de recursos em linha de comando (em desenvolvimento)

[SPED DOCS](https://github.com/nfephp-org/sped-docs) Conjunto da documentação dos pacotes NFePHP (ultrapassado, incompleto e parcial)

[SPED EMISSOR](https://github.com/nfephp-org/sped-emissor) Aplicativo "front-end" para emissão de documentos (não iniciado)

[SPED eSOCIAL](https://github.com/nfephp-org/sped-esocial) Repositório das classes para eSocial (apenas documentação)

[SPED SERIALIZER](https://github.com/nfephp-org/sped-esocial) Repositorio de classes para serialização de XML (conceito)

Outros projetos relacionados, mas com finalidade especifica:

[SPED eFINANCEIRA](https://github.com/nfephp-org/sped-efinanceira) Repositório da classes de eFinanceira (versão estável)

[SPED eSFINGE](https://github.com/nfephp-org/sped-esfinge) Framework para integração com o sistema eSfinge do TCE/SC (versão estável)

# CONTRIBUINDO

Este é um projeto totalmente *OpenSource*, para usa-lo e modifica-lo você não paga absolutamente nada. Porém para continuarmos a mante-lo é necessário qua alguma contribuição seja feita, seja auxiliando na codificação, na documentação ou na realização de testes e identificação de falhas e BUGs.

Mas também, caso você ache que qualquer informação obtida aqui, lhe foi útil e que isso vale de algum dinheiro e está disposto a doar algo, sinta-se livre para enviar qualquer quantia através de :

<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=linux%2erlm%40gmail%2ecom&lc=BR&item_name=NFePHP%20OpenSource%20API&item_number=nfephp&currency_code=BRL&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest">
<img alt="Doar com Paypal" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif"/></a>
[![](https://stc.pagseguro.uol.com.br/public/img/botoes/doacoes/209x48-doar-assina.gif)](https://pag.ae/bkXPq4) 


## IMPORTANTE:

A partir desta versão o numero das versões seguirá uma sequencia própria da API e não mais irá se referir as versões de layout da NFe, CTe, etc.

Nesta versão (4.0.x) apenas a NFe é funcional, para CTe, e MDFe deve ser usado a TAG 3.10-Obsoleta, pelo menos até que outros colaboradores se disponham a auxiliar na refatoração  

## Versão de Desenvolvimento

Versão 4.0.x-dev (observa a versão de layout 3.10 da SEFAZ)

## Versão Estável

Devido as constantes alterações, dos schemas, webservices e legislações, promovidas pelo Congresso Nacional, pelas SEFAZ estaduais e pelos provedores dos webservices, a versão em MASTER e a última TAG são as mais estáveis e funcionais.

## Instalação com Composer

Pelo terminal vá até a raiz de seu projeto e lá execute :

```
composer require nfephp-org/nfephp
``` 
Isso fará com que o SEU arquivo composer.json seja acrescido da dependência da API.
A API será baixada e colocada na pasta "vendor" e o arquivo autoload.php sejá atualizado.


## Condicionantes

Para usar essa API é necessário conhecimento em programação PHP, bem como conhecer os padrões atuais da linguagem e ter bases de legislação fiscal. É extremanente recomendável que seja estudado o conteúdo dos seguintes sites.
* Documentação do Funcionamento do sistema de NFe [SEFAZ NFe](http://www.nfe.fazenda.gov.br/portal/principal.aspx)
* Documentação do Funcionamento do sistema de CTe [SEFAZ CTe](http://www.cte.fazenda.gov.br/listaSubMenu.aspx?Id=tW+YMyk/50s=)
* Documentação do Funcionamento do sistema de MDFe [SEFAZ MDfe](https://mdfe-portal.sefaz.rs.gov.br/)
* Composer [Documentação](https://getcomposer.org/doc/)  Construção do [composer.json](http://composer.json.jolicode.com/)
* IMPORTANTE [PHP do Jeito Certo](http://br.phptherightway.com/)
* Coding Style Guide [PSR-2](http://www.php-fig.org/psr/psr-2/)
* Autoload [PSR-4](http://www.php-fig.org/psr/psr-4/)

>NOTA: A NFSe Nota Fiscal de Serviços Eletrônica, não tem padrão único, e a API tem somente alguns exemplos de montagem de um sistema para esse fim, mas nenhuma API realmente funcional para esse tipo de documentos fiscais.

## Objetivo

A API permite que um programa emissor de NFe se comunique com a SEFAZ. A API não foi criada para ela própria emitir a NFe tendo em vista a enorme quantidade de informações necessárias e as características e especificidades de cada emitente.

## Dependências

* composer <https://getcomposer.org/>
* Apache: <http://httpd.apache.org/>
* PHP 5.4+: <http://php.net>
* Bibliotecas de terceiros
 * FPDF: Provisóriamente usada para gerar os documentos em PDF. Veja <http://www.fpdf.org/>.Deverá ser substituida pela classe ZendPdf (devido ao tendimento dos padrões PSR e ser mais ativamente mantida e distribuida via composer.
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

## Instalação

Para mais detalhes sobre a instalação, veja <https://github.com/nfephp-org/nfephp/wiki/Instalação>.

## Quick start

Clone o repositório com `git clone --branch=develop https://github.com/nfephp-org/nfephp.git` ou [baixe a última versão estável](https://github.com/nfephp-org/nfephp/downloads).

```sh
$ composer install
$ ./vendor/bin/phpunit
```

## Versionamento

Para fins de transparência e discernimento sobre nosso ciclo de lançamento, e procurando manter compatibilidade com versões anteriores, o número de versão da NFePHP 
será mantida, tanto quanto possível, respeitando o padrão abaixo.

As liberações serão numeradas com o seguinte formato:

`<major>.<minor>.<patch>`

E serão construídas com as seguintes orientações:

* Quebra de compatibilidade com versões anteriores, avança o `<major>`.
* Adição de novas funcionalidades sem quebrar compatibilidade com versões anteriores, avança o `<minor>`.
* Correção de bugs e outras alterações, avança `<patch>`.

Para mais informações, por favor visite <http://semver.org/>.

## Desenvolvimento

Para todo o desenvolvimento, correções de bugs, inclusões e testes deverá ser usada branch `develop`. 
Na branch `master`estarão os códigos considerados como estáveis.
Novas branches poderão surgir em função das necessidades que se apresentarem, seja para manter versionamentos anteriores seja para estabelecer correções de bugs. Mas apenas essas duas branches estabelecidas é que serão permanentente mantidas. 

## Bug tracker

Encontrou um bug? Informe-nos aqui no GitHub!

<https://github.com/nfephp-org/nfephp/issues>

## Mantenedores (em revisão)

* NFe  - `Roberto L. Machado`
* NFCe - `Roberto L. Machado`
* NFSe - `não definido`
* CTe  - `não definido`
* MDFe - `não definido`
* CLe  - `não definido`

## Pull Request

Para que seu Pull Request seja aceito ele deve estar seguindo os padrões descritos neste documento <http://www.walkeralencar.com/PHPCodeStandards.pdf>
