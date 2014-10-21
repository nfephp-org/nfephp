<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao3' xmlns:http='http://schemas.xmlsoap.org/wsdl/http/' xmlns:mime='http://schemas.xmlsoap.org/wsdl/mime/' xmlns:s='http://www.w3.org/2001/XMLSchema' xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/' xmlns:soap12='http://schemas.xmlsoap.org/wsdl/soap12/' xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/' xmlns:tm='http://microsoft.com/wsdl/mime/textMatching/' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao3' xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>
 <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Servico destinado ao atendimento de solicitacoes de inutilizacao de numeracao.</wsdl:documentation>
 <wsdl:types>
  <s:schema elementFormDefault='qualified' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao3'>
   <s:element name='nfeDadosMsg'>
    <s:complexType mixed='true'>
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name='nfeInutilizacaoNFResult'>
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
 <wsdl:message name='nfeInutilizacaoNFSoap12Out'>
  <wsdl:part element='tns:nfeInutilizacaoNFResult' name='nfeInutilizacaoNFResult'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeInutilizacaoNFSoap12In'>
  <wsdl:part element='tns:nfeDadosMsg' name='nfeDadosMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeInutilizacaoNFnfeCabecMsg'>
  <wsdl:part element='tns:nfeCabecMsg' name='nfeCabecMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:portType name='NfeInutilizacaoSoap12'>
  <wsdl:operation name='nfeInutilizacaoNF'>
   <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Inutilizacao de numeracao de NF-e</wsdl:documentation>
   <wsdl:input message='tns:nfeInutilizacaoNFSoap12In'></wsdl:input>
   <wsdl:output message='tns:nfeInutilizacaoNFSoap12Out'></wsdl:output>
  </wsdl:operation>
 </wsdl:portType>
 <wsdl:binding name='NfeInutilizacaoSoap12' type='tns:NfeInutilizacaoSoap12'>
  <soap12:binding transport='http://schemas.xmlsoap.org/soap/http'/>
  <wsdl:operation name='nfeInutilizacaoNF'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao3/nfeInutilizacaoNF' style='document'/>
   <wsdl:input>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeInutilizacaoNFnfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:input>
   <wsdl:output>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeInutilizacaoNFnfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:output>
  </wsdl:operation>
 </wsdl:binding>
 <wsdl:service name='NfeInutilizacao3'>
  <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Servico destinado ao atendimento de solicitacoes de inutilizacao de numeracao.</wsdl:documentation>
  <wsdl:port binding='tns:NfeInutilizacaoSoap12' name='NfeInutilizacaoServicePort'>
   <soap12:address location='https://homologacao.nfe.fazenda.pr.gov.br/nfe/NFeInutilizacao3'/>
  </wsdl:port>
 </wsdl:service>
</wsdl:definitions>