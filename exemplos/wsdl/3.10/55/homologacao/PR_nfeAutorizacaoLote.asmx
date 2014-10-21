<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao3' xmlns:http='http://schemas.xmlsoap.org/wsdl/http/' xmlns:mime='http://schemas.xmlsoap.org/wsdl/mime/' xmlns:s='http://www.w3.org/2001/XMLSchema' xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/' xmlns:soap12='http://schemas.xmlsoap.org/wsdl/soap12/' xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/' xmlns:tm='http://microsoft.com/wsdl/mime/textMatching/' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao3' xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>
 <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Servico destinado a recepcao de mensagens de lote de NF-e.</wsdl:documentation>
 <wsdl:types>
  <s:schema elementFormDefault='qualified' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao3'>
   <s:element name='nfeDadosMsg'>
    <s:complexType mixed='true'>
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name='nfeAutorizacaoLoteResult'>
    <s:complexType mixed='true'>
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name='nfeDadosMsgZip'>
    <s:complexType mixed='true'>
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name='nfeAutorizacaoLoteZipResult'>
    <s:complexType mixed='true'>
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name='nfeCabecMsg' type='tns:nfeCabecMsg'/>
   <s:complexType name='nfeCabecMsg'>
    <s:sequence>
     <s:element maxOccurs='1' minOccurs='0' name='cUF' type='s:string'/>
     <s:element maxOccurs='1' minOccurs='0' name='versaoDados' type='s:string'/>
    </s:sequence>
    <s:anyAttribute/>
   </s:complexType>
  </s:schema>
 </wsdl:types>
 <wsdl:message name='nfeAutorizacaoLoteNfeCabecMsg'>
  <wsdl:part element='tns:nfeCabecMsg' name='nfeCabecMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeAutorizacaoLoteSoap12Out'>
  <wsdl:part element='tns:nfeAutorizacaoLoteResult' name='nfeAutorizacaoLoteResult'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeAutorizacaoLoteZipSoap12In'>
  <wsdl:part element='tns:nfeDadosMsgZip' name='nfeDadosMsgZip'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeAutorizacaoLoteZipSoap12Out'>
  <wsdl:part element='tns:nfeAutorizacaoLoteZipResult' name='nfeAutorizacaoLoteZipResult'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeAutorizacaoLoteSoap12In'>
  <wsdl:part element='tns:nfeDadosMsg' name='nfeDadosMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:portType name='NfeAutorizacaoSoap12'>
  <wsdl:operation name='nfeAutorizacaoLote'>
   <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Transmissao de Lote de NF-e</wsdl:documentation>
   <wsdl:input message='tns:nfeAutorizacaoLoteSoap12In'></wsdl:input>
   <wsdl:output message='tns:nfeAutorizacaoLoteSoap12Out'></wsdl:output>
  </wsdl:operation>
  <wsdl:operation name='nfeAutorizacaoLoteZip'>
   <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Transmissao de Lote Compactado de NF-e</wsdl:documentation>
   <wsdl:input message='tns:nfeAutorizacaoLoteZipSoap12In'></wsdl:input>
   <wsdl:output message='tns:nfeAutorizacaoLoteZipSoap12Out'></wsdl:output>
  </wsdl:operation>
 </wsdl:portType>
 <wsdl:binding name='NfeAutorizacaoSoap12' type='tns:NfeAutorizacaoSoap12'>
  <soap12:binding transport='http://schemas.xmlsoap.org/soap/http'/>
  <wsdl:operation name='nfeAutorizacaoLote'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao3/nfeAutorizacaoLote' style='document'/>
   <wsdl:input>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeAutorizacaoLoteNfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:input>
   <wsdl:output>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeAutorizacaoLoteNfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:output>
  </wsdl:operation>
  <wsdl:operation name='nfeAutorizacaoLoteZip'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao3/nfeAutorizacaoLoteZip' style='document'/>
   <wsdl:input>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeAutorizacaoLoteNfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:input>
   <wsdl:output>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeAutorizacaoLoteNfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:output>
  </wsdl:operation>
 </wsdl:binding>
 <wsdl:service name='NfeAutorizacao3'>
  <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Servico destinado a recepcao de mensagens de lote de NF-e.</wsdl:documentation>
  <wsdl:port binding='tns:NfeAutorizacaoSoap12' name='NfeAutorizacaoServicePort'>
   <soap12:address location='https://homologacao.nfe.fazenda.pr.gov.br/nfe/NFeAutorizacao3'/>
  </wsdl:port>
 </wsdl:service>
</wsdl:definitions>