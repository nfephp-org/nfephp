<?xml version="1.0" encoding="UTF-8"?>
HTTP/1.1 200 OK
Server: Apache-Coyote/1.1
Pragma: No-cache
Cache-Control: no-cache
Expires: Wed, 31 Dec 1969 21:00:00 BRT
X-Powered-By: Servlet 2.4; JBoss-4.2.3.GA (build: SVNTag=JBoss_4_2_3_GA date=200807181417)/JBossWeb-2.0
Content-Type: text/xml
Date: Tue, 21 Oct 2014 18:45:50 GMT
Connection: close

<definitions name='NfeInutilizacao2' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2' xmlns='http://schemas.xmlsoap.org/wsdl/' xmlns:soap12='http://schemas.xmlsoap.org/wsdl/soap12/' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2' xmlns:xsd='http://www.w3.org/2001/XMLSchema'>
 <types>
  <xs:schema elementFormDefault='qualified' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2' version='1.0' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2' xmlns:xs='http://www.w3.org/2001/XMLSchema'>
   <xs:element name='nfeCabecMsg' type='tns:nfeCabecMsg'/>
   <xs:element name='nfeDadosMsg'>
    <xs:complexType mixed='true'>
     <xs:sequence>
      <xs:any/>
     </xs:sequence>
    </xs:complexType>
   </xs:element>
   <xs:element name='nfeInutilizacaoNF2Result'>
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
 <message name='NfeInutilizacaoService_nfeInutilizacaoNF2Response'>
  <part element='tns:nfeInutilizacaoNF2Result' name='nfeInutilizacaoNF2Result'></part>
 </message>
 <message name='NfeInutilizacaoService_nfeInutilizacaoNF2'>
  <part element='tns:nfeDadosMsg' name='nfeDadosMsg'></part>
  <part element='tns:nfeCabecMsg' name='nfeCabecMsg'></part>
 </message>
 <portType name='NfeInutilizacaoService'>
  <operation name='nfeInutilizacaoNF2' parameterOrder='nfeCabecMsg nfeDadosMsg'>
   <input message='tns:NfeInutilizacaoService_nfeInutilizacaoNF2'></input>
   <output message='tns:NfeInutilizacaoService_nfeInutilizacaoNF2Response'></output>
  </operation>
 </portType>
 <binding name='NfeInutilizacaoServiceBinding' type='tns:NfeInutilizacaoService'>
  <soap12:binding style='document' transport='http://schemas.xmlsoap.org/soap/http'/>
  <operation name='nfeInutilizacaoNF2'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2/nfeInutilizacaoNF2'/>
   <input>
    <soap12:body parts='nfeDadosMsg' use='literal'/>
    <soap12:header message='tns:NfeInutilizacaoService_nfeInutilizacaoNF2' part='nfeCabecMsg' use='literal'></soap12:header>
   </input>
   <output>
    <soap12:body parts='nfeInutilizacaoNF2Result' use='literal'/>
   </output>
  </operation>
 </binding>
 <service name='NfeInutilizacao2'>
  <port binding='tns:NfeInutilizacaoServiceBinding' name='NfeInutilizacaoServicePort'>
   <soap12:address location='https://nfe.sefaz.go.gov.br/nfe/services/v2/NfeInutilizacao2'/>
  </port>
 </service>
</definitions>