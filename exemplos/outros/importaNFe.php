<?php

//contribuição de Giuliano Nascimento

$arq = './35110258716523000119550000000097950900999073-nfe.xml';

if ( is_file($arq) ){
    $docxml = file_get_contents($arq);
    $dados = importaNFe($docxml);
    print_r($dados);
}


function importaNFe($xml){
    $doc = new DOMDocument();
    $doc->preservWhiteSpace = FALSE; //elimina espaços em branco
    $doc->formatOutput = FALSE;
    $doc->loadXML($xml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
    $node = $doc->getElementsByTagName('infNFe')->item(0);
    //obtem a versão do layout da NFe
    $dados['versao']=trim($node->getAttribute("versao"));
    $dados['chave']= substr(trim($node->getAttribute("Id")),3);

    // Reconhecimento dos campos do XML

    $dados['dataEmissao']=tagValue(&$doc,"dEmi");
    $dados['dataMovimento']=tagValue(&$doc,"dSaiEnt")." ".tagValue(&$doc,"hSaiEnt");
    $dados['numero']=tagValue(&$doc,"nNF");
    $dados['modelo']=tagValue(&$doc,"mod");
    $dados['serie']=tagValue(&$doc,"serie");

    $emi=$doc->getElementsByTagName('emit')->item(0);
    $c1=tagValue(&$emi,"CNPJ");
    $c2=substr($c1,0,2).".".substr($c1,2,3).".".substr($c1,5,3)."/".substr($c1,8,4)."-".substr($c1,12,2);
    $dados['emitenteCnpj']=$c1;
    $dados['emitenteCnpjFormatado']=$c2;
    $dados['emitenteRazaoSocial']=tagValue(&$emi,"xNome");
    $dados['emitenteNome']=tagValue(&$emi,"xFant");
    $dados['emitenteInscricaoEstadual']=tagValue(&$emi,"IE");
    $dados['emitenteInscricaoMunicipal']=tagValue(&$emi,"IM");
    $dados['emitenteCnae']=tagValue(&$emi,"CNAE");
    $dados['emitenteEndereco']=tagValue(&$emi,"xLgr");
    $dados['emitenteNumero']=tagValue(&$emi,"nro");
    $dados['emitenteBairro']=tagValue(&$emi,"xBairro");
    $dados['emitenteMunicipio']=tagValue(&$emi,"xMun");
    $dados['emitenteMunicipioIbge']=tagValue(&$emi,"cMun");
    $dados['emitenteCep']=tagValue(&$emi,"CEP");
    $dados['emitenteUF']=tagValue(&$emi,"UF");
    $dados['emitentePaisIbge']=tagValue(&$emi,"cPais");
    $dados['emitentePais']=tagValue(&$emi,"xPais");
    $dados['emitenteTelefone']=tagValue(&$emi,"fone");

    $dst=$doc->getElementsByTagName('dest')->item(0);
    $c1=tagValue(&$dst,"CNPJ");
    $c2=substr($c1,0,2).".".substr($c1,2,3).".".substr($c1,5,3)."/".substr($c1,8,4)."-".substr($c1,12,2);
    $dados['destinatarioCnpj']=$c1;
    $dados['destinatarioCnpjFormatado']=$c2;
    $dados['destinatarioRazaoSocial']=tagValue(&$dst,"xNome");
    $dados['destinatarioNome']=tagValue(&$dst,"xFant");
    $dados['destinatarioInscricaoEstadual']=tagValue(&$dst,"IE");
    $dados['destinatarioInscricaoMunicipal']=tagValue(&$dst,"IM");
    $dados['destinatarioEndereco']=tagValue(&$dst,"xLgr");
    $dados['destinatarioNumero']=tagValue(&$dst,"nro");
    $dados['destinatarioBairro']=tagValue(&$dst,"xBairro");
    $dados['destinatarioMunicipio']=tagValue(&$dst,"xMun");
    $dados['destinatarioMunicipioIbge']=tagValue(&$dst,"cMun");
    $dados['destinatarioCep']=tagValue(&$dst,"CEP");
    $dados['destinatarioUF']=tagValue(&$dst,"UF");
    $dados['destinatarioPaisIbge']=tagValue(&$dst,"cPais");
    $dados['destinatarioPais']=tagValue(&$dst,"xPais");
    $dados['destinatarioTelefone']=tagValue(&$dst,"fone");

    $dados['pesoLiquido']=floatval(tagValue(&$doc,"pesoL"));
    $dados['pesoBruto']=floatval(tagValue(&$doc,"pesoB"));

    $dados['dataRecibo']=tagValue(&$doc,"dhRecbto");
    $dados['protocolo']=tagValue(&$doc,"nProt");

    $det=$doc->getElementsByTagName('det');
    $itens="";
    for ($i = 0; $i < $det->length; $i++) {
        $item=$det->item($i);
        $s="";
        $s['codigo']=tagValue(&$item,"cProd");
        $s['ean']=tagValue(&$item,"cEAN");
        $s['nome']=tagValue(&$item,"xProd");
        $s['ncm']=tagValue(&$item,"NCM");
        $s['cfop']=tagValue(&$item,"CFOP");
        $s['unidade']=tagValue(&$item,"uCom");
        $s['quantidade']=tagValue(&$item,"qCom");
        $s['valor']=tagValue(&$item,"vUnCom");
        $s['valorTotal']=tagValue(&$item,"vProd");
        $s['icms']=0;
        $s['ipi']=0;
        $itens[]=$s;
    }
    $dados['itens']=$itens;
    return($dados);
}


function tagValue($node,$tag){
    return $node->getElementsByTagName("$tag")->item(0)->nodeValue;
}

?>
