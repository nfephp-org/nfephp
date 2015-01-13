<?xml version="1.0" encoding="UTF-8"?>
HTTP/1.1 200 OK
Server: Apache-Coyote/1.1
Pragma: No-cache
Cache-Control: no-cache
Expires: Wed, 31 Dec 1969 21:00:00 BRT
X-Powered-By: Servlet 2.4; JBoss-4.2.3.GA (build: SVNTag=JBoss_4_2_3_GA date=200807181417)/JBossWeb-2.0
Content-Type: text/xml
Date: Tue, 21 Oct 2014 18:45:47 GMT
Connection: close

<definitions name='NfeRetAutorizacao' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao' xmlns='http://schemas.xmlsoap.org/wsdl/' xmlns:soap12='http://schemas.xmlsoap.org/wsdl/soap12/' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao' xmlns:xsd='http://www.w3.org/2001/XMLSchema'>
 <types>
  <xs:schema elementFormDefault='qualified' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao' version='1.0' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao' xmlns:xs='http://www.w3.org/2001/XMLSchema'>
   <xs:element name='nfeCabecMsg' type='tns:nfeCabecMsg'/>
   <xs:element name='nfeDadosMsg'>
    <xs:complexType mixed='true'>
     <xs:sequence>
      <xs:any/>
     </xs:sequence>
    </xs:complexType>
   </xs:element>
   <xs:element name='nfeRetAutorizacaoLoteResult'>
    <xs:complexType mixed='true'>
     <xs:sequence>
      <xs:any/>
     </xs:sequence>
    </xs:complexType>
   </xs:element>
   <xs:complexType name='nfeCabecMsg'>
    <xs:sequence>
     <xs:element minOccurs='0' name='cUF' type='xs:string'/>
     <xs:element minOccurs='0' name='versaoDados' type='xs:string'/>
    </xs:sequence>
   </xs:complexType>
  </xs:schema>
 </types>
 <message name='NfeRetAutorizacaoService_nfeRetAutorizacaoLoteResponse'>
  <part element='tns:nfeRetAutorizacaoLoteResult' name='nfeRetAutorizacaoLoteResult'></part>
 </message>
 <message name='NfeRetAutorizacaoService_nfeRetAutorizacao'>
  <part element='tns:nfeDadosMsg' name='nfeDadosMsg'></part>
  <part element='tns:nfeCabecMsg' name='nfeCabecMsg'></part>
 </message>
 <portType name='NfeRetAutorizacaoService'>
  <operation name='nfeRetAutorizacaoLote' parameterOrder='nfeCabecMsg nfeDadosMsg'>
   <input message='tns:NfeRetAutorizacaoService_nfeRetAutorizacaoLote'></input>
   <output message='tns:NfeRetAutorizacaoService_nfeRetAutorizacaoLoteResponse'></output>
  </operation>
 </portType>
 <binding name='NfeRetAutorizacaoServiceBinding' type='tns:NfeRetAutorizacaoService'>
  <soap12:binding style='document' transport='http://schemas.xmlsoap.org/soap/http'/>
  <operation name='nfeRetAutorizacaoLote'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao/nfeRetAutorizacaoLote'/>
   <input>
    <soap12:body parts='nfeDadosMsg' use='literal'/>
    <soap12:header message='tns:NfeRetAutorizacaoService_nfeRetAutorizacaoLote' part='nfeCabecMsg' use='literal'></soap12:header>
   </input>
   <output>
    <soap12:body parts='nfeRetAutorizacaoLoteResult' use='literal'/>
   </output>
  </operation>
 </binding>
 <service name='NfeRetAutorizacao'>
  <port binding='tns:NfeRetAutorizacaoServiceBinding' name='NfeRetAutorizacaoServicePort'>
   <soap12:address location='https://homolog.sefaz.go.gov.br/nfe/services/v2/NfeRetAutorizacao'/>
  </port>
 </service>
</definitions>