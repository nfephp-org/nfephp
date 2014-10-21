<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2' xmlns:http='http://schemas.xmlsoap.org/wsdl/http/' xmlns:mime='http://schemas.xmlsoap.org/wsdl/mime/' xmlns:s='http://www.w3.org/2001/XMLSchema' xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/' xmlns:soap12='http://schemas.xmlsoap.org/wsdl/soap12/' xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/' xmlns:tm='http://microsoft.com/wsdl/mime/textMatching/' xmlns:tns='http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2' xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>
 <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Servico destinado ao atendimento de solicitacoes de consulta ao Cadastro de Contribuintes do ICMS da Secretaria de Fazenda Estatual.</wsdl:documentation>
 <wsdl:types>
  <s:schema elementFormDefault='qualified' targetNamespace='http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2'>
   <s:element name='consultaCadastro2'>
    <s:complexType>
     <s:sequence>
      <s:element maxOccurs='1' minOccurs='0' name='nfeDadosMsg'>
       <s:complexType mixed='true'>
        <s:sequence>
         <s:any/>
        </s:sequence>
       </s:complexType>
      </s:element>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name='consultaCadastro2Response'>
    <s:complexType>
     <s:sequence>
      <s:element maxOccurs='1' minOccurs='0' name='consultaCadastro2Result'>
       <s:complexType mixed='true'>
        <s:sequence>
         <s:any/>
        </s:sequence>
       </s:complexType>
      </s:element>
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
 <wsdl:message name='consultaCadastro2Soap12In'>
  <wsdl:part element='tns:consultaCadastro2' name='parameters'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='consultaCadastro2nfeCabecMsg'>
  <wsdl:part element='tns:nfeCabecMsg' name='nfeCabecMsg'></wsdl:part>
 </wsdl:message>
 <wsdl:message name='consultaCadastro2Soap12Out'>
  <wsdl:part element='tns:consultaCadastro2Response' name='parameters'></wsdl:part>
 </wsdl:message>
 <wsdl:portType name='CadConsultaCadastro2Soap12'>
  <wsdl:operation name='consultaCadastro2'>
   <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Consulta Cadastro de Contribuintes do ICMS</wsdl:documentation>
   <wsdl:input message='tns:consultaCadastro2Soap12In'></wsdl:input>
   <wsdl:output message='tns:consultaCadastro2Soap12Out'></wsdl:output>
  </wsdl:operation>
 </wsdl:portType>
 <wsdl:binding name='CadConsultaCadastro2Soap12' type='tns:CadConsultaCadastro2Soap12'>
  <soap12:binding transport='http://schemas.xmlsoap.org/soap/http'/>
  <wsdl:operation name='consultaCadastro2'>
   <soap12:operation soapAction='http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2/consultaCadastro2' style='document'/>
   <wsdl:input>
    <soap12:body use='literal'/>
    <soap12:header message='tns:consultaCadastro2nfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:input>
   <wsdl:output>
    <soap12:body use='literal'/>
    <soap12:header message='tns:consultaCadastro2nfeCabecMsg' part='nfeCabecMsg' use='literal'></soap12:header>
   </wsdl:output>
  </wsdl:operation>
 </wsdl:binding>
 <wsdl:service name='CadConsultaCadastro2'>
  <wsdl:documentation xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'>Servico destinado ao atendimento de solicitacoes de consulta ao Cadastro de Contribuintes do ICMS da Secretaria de Fazenda Estatual.</wsdl:documentation>
  <wsdl:port binding='tns:CadConsultaCadastro2Soap12' name='CadConsultaCadastroServicePort'>
   <soap12:address location='https://homologacao.nfe.fazenda.pr.gov.br/nfe/CadConsultaCadastro2'/>
  </wsdl:port>
 </wsdl:service>
</wsdl:definitions>