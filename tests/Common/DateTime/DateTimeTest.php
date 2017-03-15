<?php
/**
 * Class DateTimeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\Common\DateTime\DateTime;

class DateTimeTest extends PHPUnit\Framework\TestCase
{
    public $tstp = 1424196793;
    public $sefTime = '2015-02-17T15:13:13-03:00';

    public function testTZD()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $tzP = date('P');
        $tzP1 = DateTime::tzdBR('SP');
        $this->assertEquals($tzP, $tzP1);
    }

    public function testConvertSefazTimeToTimestamp()
    {
        DateTime::tzdBR('CE');
        $tsp = DateTime::convertSefazTimeToTimestamp($this->sefTime);
        $this->assertEquals($tsp, $this->tstp);
    }

    public function testConvertTimestampToSefazTime()
    {
        DateTime::tzdBR('CE');
        $seft = DateTime::convertTimestampToSefazTime($this->tstp);
        $this->assertEquals($seft, $this->sefTime);
    }

    public function testConvertSefazTimeToTimestampEmBranco()
    {
        $this->assertEmpty(DateTime::convertSefazTimeToTimestamp());
    }

    public function testTZDSiglaNaoInformada()
    {
        $this->assertEmpty(DateTime::tzdBR());
    }

    /**
     * @dataProvider siglasNaoExistentes
     */
    public function testTZDSiglaNaoExistente($uf)
    {
        $this->assertEmpty(DateTime::tzdBR($uf));
    }

    public function siglasNaoExistentes()
    {
        return array(
            array('TESTE'),
            array('JO')
        );
    }
}
