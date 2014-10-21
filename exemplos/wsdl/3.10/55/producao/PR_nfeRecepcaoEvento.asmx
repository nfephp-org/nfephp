<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento' xmlns:http='http://schemas.xmlsoap.org/wsdl/http/' xmlns:mime='http://schemas.xmlsoap.org/wsdl/mime/' xmlns:s='http://www.w3.org/2001/XMLSchema' xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/' xmlns:soap12='http://schemas.xmlsoap.org/wsdl/soap12/' xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/' xmlns:tm='http://microsoft.com/wsdl/mime/textMatching/' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento' xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>
 <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Servico destinado a recepcao de mensagens de Evento de NF-e.</wsdl:documentation>
 <wsdl:types>
  <s:schema elementFormDefault='qualified' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento'>
   <s:element name='nfeDadosMsg'>
    <s:complexType mixed='true'>
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name='nfeRecepcaoEventoResult'>
    <s:complexType mixed='true'>
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name='nfeCabecMsg' type='tns:nfeCabecMsg'/>
   <s:complexType name='nfeCabecMsg'>
    <s:sequence>
     <s:element maxOccurs='1' minOccurs='0' name='versaoDados' type='s:string'/>
     <s:element maxOccurs='1' minOccurs='0' name='cUF' type='s:string'/>
    </s:sequence>
    <s:anyAttribute/>
   </s:complexType>
  </s:schema>
 </wsdl:types>
 <wsdl:message name='nfeRecepcaoEventoSoapOut'>
  <wsdl:part element='tns:nfeRecepcaoEventoResult' name='nfeRecepcaoEventoResult'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeRecepcaoEventonfeCabecMsg'>
  <wsdl:part element='tns:nfeCabecMsg' name='nfeCabecMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeRecepcaoEventoSoapIn'>
  <wsdl:part element='tns:nfeDadosMsg' name='nfeDadosMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:portType name='RecepcaoEventoSoap'>
  <wsdl:operation name='nfeRecepcaoEvento'>
   <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Recepção de mensagem de Evento da NF-e.</wsdl:documentation>
   <wsdl:input message='tns:nfeRecepcaoEventoSoapIn'></wsdl:input>
   <wsdl:output message='tns:nfeRecepcaoEventoSoapOut'></wsdl:output>
  </wsdl:operation>
 </wsdl:portType>
 <wsdl:binding name='RecepcaoEventoSoap' type='tns:RecepcaoEventoSoap'>
  <soap:binding transport='http://schemas.xmlsoap.org/soap/http'/>
  <wsdl:operation name='nfeRecepcaoEvento'>
   <soap:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento/nfeRecepcaoEvento' style='document'/>
   <wsdl:input>
    <soap:body use='literal'/>
    <soap:header message='tns:nfeRecepcaoEventonfeCabecMsg' part='nfeCabecMsg' use='literal'></soap:header>
   </wsdl:input>
   <wsdl:output>
    <soap:body use='literal'/>
   </wsdl:output>
  </wsdl:operation>
 </wsdl:binding>
 <wsdl:binding name='RecepcaoEventoSoap12' type='tns:RecepcaoEventoSoap'>
  <soap12:binding transport='http://schemas.xmlsoap.org/soap/http'/>
  <wsdl:operation name='nfeRecepcaoEvento'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento/nfeRecepcaoEvento' style='document'/>
   <wsdl:input>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeRecepcaoEventonfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:input>
   <wsdl:output>
    <soap12:body use='literal'/>
   </wsdl:output>
  </wsdl:operation>
 </wsdl:binding>
 <wsdl:service name='RecepcaoEvento'>
  <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Servico destinado a recepcao de mensagens de evento de NF-e.</wsdl:documentation>
  <wsdl:port binding='tns:RecepcaoEventoSoap' name='RecepcaoEventoPort'>
   <soap:address location='https://nfe.fazenda.pr.gov.br/nfe/NFeRecepcaoEvento'/>
  </wsdl:port>
 </wsdl:service>
</wsdl:definitions>