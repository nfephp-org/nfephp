<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico3' xmlns:http='http://schemas.xmlsoap.org/wsdl/http/' xmlns:mime='http://schemas.xmlsoap.org/wsdl/mime/' xmlns:s='http://www.w3.org/2001/XMLSchema' xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/' xmlns:soap12='http://schemas.xmlsoap.org/wsdl/soap12/' xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/' xmlns:tm='http://microsoft.com/wsdl/mime/textMatching/' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico3' xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>
 <wsdl:types>
  <s:schema elementFormDefault='qualified' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico3'>
   <s:element name='nfeDadosMsg'>
    <s:complexType mixed='true'>
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name='nfeStatusServicoNFResult'>
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
 <wsdl:message name='nfeStatusServicoNFSoap12Out'>
  <wsdl:part element='tns:nfeStatusServicoNFResult' name='nfeStatusServicoNFResult'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeStatusServicoNFnfeCabecMsg'>
  <wsdl:part element='tns:nfeCabecMsg' name='nfeCabecMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='nfeStatusServicoNFSoap12In'>
  <wsdl:part element='tns:nfeDadosMsg' name='nfeDadosMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:portType name='NfeStatusServicoSoap12'>
  <wsdl:operation name='nfeStatusServicoNF'>
   <wsdl:input message='tns:nfeStatusServicoNFSoap12In'></wsdl:input>
   <wsdl:output message='tns:nfeStatusServicoNFSoap12Out'></wsdl:output>
  </wsdl:operation>
 </wsdl:portType>
 <wsdl:binding name='NfeStatusServicoSoap12' type='tns:NfeStatusServicoSoap12'>
  <soap12:binding transport='http://schemas.xmlsoap.org/soap/http'/>
  <wsdl:operation name='nfeStatusServicoNF'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico3/nfeStatusServicoNF' style='document'/>
   <wsdl:input>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeStatusServicoNFnfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:input>
   <wsdl:output>
    <soap12:body use='literal'/>
    <soap12:header message='tns:nfeStatusServicoNFnfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:output>
  </wsdl:operation>
 </wsdl:binding>
 <wsdl:service name='NfeStatusServico3'>
  <wsdl:port binding='tns:NfeStatusServicoSoap12' name='NfeStatusServicoServicePort'>
   <soap12:address location='https://nfe.fazenda.pr.gov.br/nfe/NFeStatusServico3'/>
  </wsdl:port>
 </wsdl:service>
</wsdl:definitions>