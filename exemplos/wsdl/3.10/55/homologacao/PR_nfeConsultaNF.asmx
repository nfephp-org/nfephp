<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta3' xmlns:http='http://schemas.xmlsoap.org/wsdl/http/' xmlns:mime='http://schemas.xmlsoap.org/wsdl/mime/' xmlns:s='http://www.w3.org/2001/XMLSchema' xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/' xmlns:soap12='http://schemas.xmlsoap.org/wsdl/soap12/' xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/' xmlns:tm='http://microsoft.com/wsdl/mime/textMatching/' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta3' xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>
 <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Servico destinado ao atendimento de solicitacoes de consulta da situacao atual da NF-e na Base de Dados do Portal da Secretaria de Fazenda Estadual.</wsdl:documentation>
 <wsdl:types>
  <s:schema elementFormDefault='qualified' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta3'>
   <s:element name='nfeDadosMsg'>
    <s:complexType mixed='true'>
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name='nfeConsultaNFResult'>
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
 <wsdl:message name='nfeConsultaNFnfeCabecMsg'>
  <wsdl:part element='tns:nfeCabecMsg' name='nfeCabecMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeConsultaNFSoap12Out'>
  <wsdl:part element='tns:nfeConsultaNFResult' name='nfeConsultaNFResult'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeConsultaNFSoap12In'>
  <wsdl:part element='tns:nfeDadosMsg' name='nfeDadosMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:portType name='NfeConsultaSoap12'>
  <wsdl:operation name='nfeConsultaNF'>
   <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Consulta situacao atual da NF-e</wsdl:documentation>
   <wsdl:input message='tns:nfeConsultaNFSoap12In'></wsdl:input>
   <wsdl:output message='tns:nfeConsultaNFSoap12Out'></wsdl:output>
  </wsdl:operation>
 </wsdl:portType>
 <wsdl:binding name='NfeConsultaSoap12' type='tns:NfeConsultaSoap12'>
  <soap12:binding transport='http://schemas.xmlsoap.org/soap/http'/>
  <wsdl:operation name='nfeConsultaNF'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta3/nfeConsultaNF' style='document'/>
   <wsdl:input>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeConsultaNFnfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:input>
   <wsdl:output>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeConsultaNFnfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:output>
  </wsdl:operation>
 </wsdl:binding>
 <wsdl:service name='NfeConsulta3'>
  <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Servico destinado ao atendimento de solicitacoes de consulta da situacao atual da NF-e na Base de Dados do Portal da Secretaria de Fazenda Estadual.</wsdl:documentation>
  <wsdl:port binding='tns:NfeConsultaSoap12' name='NfeConsultaServicePort'>
   <soap12:address location='https://homologacao.nfe.fazenda.pr.gov.br/nfe/NFeConsulta3'/>
  </wsdl:port>
 </wsdl:service>
</wsdl:definitions>