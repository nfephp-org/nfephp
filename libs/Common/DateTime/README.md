Class DateTime
=============
Esta classe auxiliar provê três funções básicas para o tratamento de datas e horas para os sistemas da SEFAZ.
É requerido PHP >= 5.3

Namespace
=============
Common/DateTime;
 
FUNÇÕES
----------
 
<b>string DateTime::tzdBR(string $siglaUF)</b>
Esta função estática retorna o "timeZone" de qualquer unidade da federação como uma string no formato "-03:00",
a $siglaUF pode ser a sigla de qualquer estado brasileiro (ex. SP, MG, PR, etc..), caso não seja passado nenhum
valor válido o retorno será uma string vazia.
O Time Zone Default do ambiente PHP será modificado também, caso seja passado um valor válido.
   

<b>int DateTime::convertSefazTimeToTimestamp(string $dataHora)</b>
Esta função estática retorna um "timestamp" para uma data no formato usado pela SEFAZ "YYYY-MM-DDThh:mm:ssTZ".


<b>string DateTime::convertTimestampToSefazTime(int $timestamp)</b>
Esta função estática retorna um string no formato de data usado pela SEFAZ "YYYY-MM-DDThh:mm:ssTZ", a partir de um "timestamp".
Caso nada seja passado no parâmetro será retornado a data e hora atual no formato SEFAZ, incluindo o Time Zone default, portanto antes de usar essa função é recomendável que a primeira função "tzdBR" seja usada ou que o Time Zone Default esteja configurado corretamente.

