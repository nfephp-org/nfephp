Class DateTime
=============
Esta classe auxiliar provê três funções básicas para o tratamento de datas e horas para os sistemas da SEFAZ.

É requerido PHP >= 5.3

Namespace
=============
```php
Common/DateTime;
```

Métodos
==========

tzdBR
--------
```php
string DateTime::tzdBR(string $siglaUF)
```
Esta função estática retorna o "Time Zone Designator" de qualquer unidade da federação como uma string no formato "-03:00", a $siglaUF pode ser a sigla de qualquer estado brasileiro (ex. SP, MG, PR, etc..), caso não seja passado nenhum valor válido o retorno será uma string vazia.
O Time Zone Default do <b>ambiente PHP será modificado também</b>, caso seja passado um valor válido.
Parâmetros
--------
<b>siglaUF</b>

string com a sigla da unidade da federação em letras maiusculas, caso seja passado uma sigla invalida ou vazia será retornado uma string vazia 

convertSefazTimeToTimestamp
--------
```php
int DateTime::convertSefazTimeToTimestamp(string $dataHora)
```
Esta função estática retorna um "timestamp" para uma data no formato usado pela SEFAZ "YYYY-MM-DDThh:mm:ssTZ".
Parâmetros
--------
<b>dataHora</b>

string com o parâmetro data extraído do xml da SEFAZ.

convertTimestampToSefazTime
--------
```php
string DateTime::convertTimestampToSefazTime(int $timestamp)
```
Esta função estática retorna um string no formato de data usado pela SEFAZ "YYYY-MM-DDThh:mm:ssTZ", a partir de um "timestamp".
Parâmetros
--------
<b>timestamp</b>

integer timestamp UNIX para ser convertido ao padrão usado pela SEFAZ. Caso nenhum parametro seja passado será retornado a data e hora atual no dito padrão, , incluindo o Time Zone default, portanto antes de usar essa função é recomendável que a primeira função "tzdBR" seja usada ou que o Time Zone Default esteja configurado corretamente.
