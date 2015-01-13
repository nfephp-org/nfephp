<?xml version="1.0" encoding="UTF-8"?>
HTTP/1.1 200 OK
Server: Apache-Coyote/1.1
Pragma: No-cache
Cache-Control: no-cache
Expires: Wed, 31 Dec 1969 21:00:00 BRT
X-Powered-By: Servlet 2.4; JBoss-4.2.3.GA (build: SVNTag=JBoss_4_2_3_GA date=200807181417)/JBossWeb-2.0
Content-Type: text/xml
Date: Tue, 21 Oct 2014 18:45:49 GMT
Connection: close

<definitions name='NfeAutorizacao' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao' xmlns='http://schemas.xmlsoap.org/wsdl/' xmlns:soap12='http://schemas.xmlsoap.org/wsdl/soap12/' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao' xmlns:xsd='http://www.w3.org/2001/XMLSchema'>
 <types>
  <xs:schema elementFormDefault='qualified' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao' version='1.0' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao' xmlns:xs='http://www.w3.org/2001/XMLSchema'>
   <xs:element name='nfeCabecMsg' type='tns:nfeCabecMsg'/>
   <xs:element name='nfeDadosMsg'>
    <xs:complexType mixed='true'>
     <xs:sequence>
      <xs:any/>
     </xs:sequence>
    </xs:complexType>
   </xs:element>
   <xs:element name='nfeAutorizacaoLoteResult'>
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
   <xs:element name='nfeDadosMsgZip'>
    <xs:complexType mixed='true'>
     <xs:sequence>
      <xs:any/>
     </xs:sequence>
    </xs:complexType>
   </xs:element>
   <xs:element name='nfeAutorizacaoLoteZipResult'>
    <xs:complexType mixed='true'>
     <xs:sequence>
      <xs:any/>
     </xs:sequence>
    </xs:complexType>
   </xs:element>
  </xs:schema>
 </types>
 <message name='NfeAutorizacaoService_nfeAutorizacaoLote'>
  <part element='tns:nfeDadosMsg' name='nfeDadosMsg'></part>
  <part element='tns:nfeCabecMsg' name='nfeCabecMsg'></part>
 </message>
 <message name='NfeAutorizacaoService_nfeAutorizacaoLoteResponse'>
  <part element='tns:nfeAutorizacaoLoteResult' name='nfeAutorizacaoLoteResult'></part>
 </message>
 <message name='NfeAutorizacaoService_nfeAutorizacaoLoteZip'>
  <part element='tns:nfeDadosMsgZip' name='nfeDadosMsgZip'></part>
  <part element='tns:nfeCabecMsg' name='nfeCabecMsg'></part>
 </message>
 <message name='NfeAutorizacaoService_nfeAutorizacaoLoteZipResponse'>
  <part element='tns:nfeAutorizacaoLoteZipResult' name='nfeAutorizacaoLoteZipResult'></part>
 </message>
 <portType name='NfeAutorizacaoService'>
  <operation name='nfeAutorizacaoLote' parameterOrder='nfeCabecMsg nfeDadosMsg'>
   <input message='tns:NfeAutorizacaoService_nfeAutorizacaoLote'></input>
   <output message='tns:NfeAutorizacaoService_nfeAutorizacaoLoteResponse'></output>
  </operation>
  <operation name='nfeAutorizacaoLoteZip' parameterOrder='nfeCabecMsg nfeDadosMsgZip'>
   <input message='tns:NfeAutorizacaoService_nfeAutorizacaoLoteZip'></input>
   <output message='tns:NfeAutorizacaoService_nfeAutorizacaoLoteZipResponse'></output>
  </operation>
 </portType>
 <binding name='NfeAutorizacaoServiceBinding' type='tns:NfeAutorizacaoService'>
  <soap12:binding style='document' transport='http://schemas.xmlsoap.org/soap/http'/>
  <operation name='nfeAutorizacaoLote'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/nfeAutorizacaoLote'/>
   <input>
    <soap12:body parts='nfeDadosMsg' use='literal'/>
    <soap12:header message='tns:NfeAutorizacaoService_nfeAutorizacaoLote' part='nfeCabecMsg' use='literal'></soap12:header>
   </input>
   <output>
    <soap12:body parts='nfeAutorizacaoLoteResult' use='literal'/>
   </output>
  </operation>
  <operation name='nfeAutorizacaoLoteZip'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/nfeAutorizacaoLoteZip'/>
   <input>
    <soap12:body parts='nfeDadosMsgZip' use='literal'/>
    <soap12:header message='tns:NfeAutorizacaoService_nfeAutorizacaoLoteZip' part='nfeCabecMsg' use='literal'></soap12:header>
   </input>
   <output>
    <soap12:body parts='nfeAutorizacaoLoteZipResult' use='literal'/>
   </output>
  </operation>
 </binding>
 <service name='NfeAutorizacao'>
  <port binding='tns:NfeAutorizacaoServiceBinding' name='NfeAutorizacaoPort'>
   <soap12:address location='https://nfe.sefaz.go.gov.br/nfe/services/v2/NfeAutorizacao'/>
  </port>
 </service>
</definitions>