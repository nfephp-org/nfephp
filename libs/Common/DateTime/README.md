Class DateTime
=============
Esta classe auxiliar provê três funções básicas para o tratamento de datas e horas para os sistemas da SEFAZ.

Namespace
=============
Common/DateTime;
 
FUNÇÕES
==============
 
<span style="color:green;">string</span><span style="color:blue;"> DateTime::tzdBR(</span><span style="color:green;">string</span><span style="color:blue;"> $siglaUF)</span>
---------
Esta função estática retorna o "timeZone" de qualquer unidade da federação como uma string no formato "-03:00",
a $siglaUF pode ser a sigla de qualquer estado brasileiro (ex. SP, MG, PR, etc..), caso não seja passado nenhum
valor válido o retorno será uma string vazia.
O Time Zone Default do ambiente PHP será modificado também, caso seja passado um valor válido.
   

<span style="color:green;">int</span><span style="color:blue;"> DateTime::convertSefazTimeToTimestamp(</span><span style="color:green;">string</span><span style="color:blue;"> $dataHora)</span>
---------
Esta função estática retorna um "timestamp" para uma data no formato usado pela SEFAZ "YYYY-MM-DDThh:mm:ssTZ".


<span style="color:green;">string</span><span style="color:blue;"> DateTime::convertTimestampToSefazTime(</span><span style="color:green;">int</span><span style="color:blue;"> $timestamp)</span>
---------
Esta função estática retorna um string no formato de data usado pela SEFAZ "YYYY-MM-DDThh:mm:ssTZ", a partir de um "timestamp".
Caso nada seja passado no parâmetro será retornado a data e hora atual no formato SEFAZ, incluindo o Time Zone default, portanto
antes de usar essa função é recomendável que a primeira função "tzdBR" seja usada ou que o Time Zone Default esteja configurado corretamente.

