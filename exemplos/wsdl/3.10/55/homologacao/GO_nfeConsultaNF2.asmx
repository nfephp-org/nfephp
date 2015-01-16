<?xml version="1.0" encoding="UTF-8"?>
HTTP/1.1 200 OK
Server: Apache-Coyote/1.1
Pragma: No-cache
Cache-Control: no-cache
Expires: Wed, 31 Dec 1969 21:00:00 BRT
X-Powered-By: Servlet 2.4; JBoss-4.2.3.GA (build: SVNTag=JBoss_4_2_3_GA date=200807181417)/JBossWeb-2.0
Content-Type: text/xml
Date: Tue, 21 Oct 2014 18:45:46 GMT
Connection: close

<definitions name='NfeConsulta2' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2' xmlns='http://schemas.xmlsoap.org/wsdl/' xmlns:soap12='http://schemas.xmlsoap.org/wsdl/soap12/' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2' xmlns:xsd='http://www.w3.org/2001/XMLSchema'>
 <types>
  <xs:schema elementFormDefault='qualified' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2' version='1.0' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2' xmlns:xs='http://www.w3.org/2001/XMLSchema'>
   <xs:element name='nfeCabecMsg' type='tns:nfeCabecMsg'/>
   <xs:element name='nfeConsultaNF2Result'>
    <xs:complexType mixed='true'>
     <xs:sequence>
      <xs:any/>
     </xs:sequence>
    </xs:complexType>
   </xs:element>
   <xs:element name='nfeDadosMsg'>
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
 <message name='NfeConsultaService_nfeConsultaNF2'>
  <part element='tns:nfeDadosMsg' name='nfeDadosMsg'></part>
  <part element='tns:nfeCabecMsg' name='nfeCabecMsg'></part>
 </message>
 <message name='NfeConsultaService_nfeConsultaNF2Response'>
  <part element='tns:nfeConsultaNF2Result' name='nfeConsultaNF2Result'></part>
 </message>
 <portType name='NfeConsultaService'>
  <operation name='nfeConsultaNF2' parameterOrder='nfeCabecMsg nfeDadosMsg'>
   <input message='tns:NfeConsultaService_nfeConsultaNF2'></input>
   <output message='tns:NfeConsultaService_nfeConsultaNF2Response'></output>
  </operation>
 </portType>
 <binding name='NfeConsultaServiceBinding' type='tns:NfeConsultaService'>
  <soap12:binding style='document' transport='http://schemas.xmlsoap.org/soap/http'/>
  <operation name='nfeConsultaNF2'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2/nfeConsultaNF2'/>
   <input>
    <soap12:body parts='nfeDadosMsg' use='literal'/>
    <soap12:header message='tns:NfeConsultaService_nfeConsultaNF2' part='nfeCabecMsg' use='literal'></soap12:header>
   </input>
   <output>
    <soap12:body parts='nfeConsultaNF2Result' use='literal'/>
   </output>
  </operation>
 </binding>
 <service name='NfeConsulta2'>
  <port binding='tns:NfeConsultaServiceBinding' name='NfeConsultaServicePort'>
   <soap12:address location='https://homolog.sefaz.go.gov.br/nfe/services/v2/NfeConsulta2'/>
  </port>
 </service>
</definitions>