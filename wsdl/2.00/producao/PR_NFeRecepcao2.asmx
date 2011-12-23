<wsdl:definitions targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRecepcao2" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRecepcao2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
 <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Servico destinado a recepcao de mensagens de lote de NF-e.</wsdl:documentation>
 <wsdl:types>
  <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRecepcao2">
   <s:element name="nfeDadosMsg">
    <s:complexType mixed="true">
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name="nfeRecepcaoLote2Result">
    <s:complexType mixed="true">
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
   <s:complexType name="nfeCabecMsg">
    <s:sequence>
     <s:element maxOccurs="1" minOccurs="0" name="cUF" type="s:string"/>
     <s:element maxOccurs="1" minOccurs="0" name="versaoDados" type="s:string"/>
    </s:sequence>
    <s:anyAttribute/>
   </s:complexType>
  </s:schema>
 </wsdl:types>
 <wsdl:message name="nfeRecepcaoLote2Soap12Out">
  <wsdl:part element="tns:nfeRecepcaoLote2Result" name="nfeRecepcaoLote2Result"/>
 </wsdl:message>
 <wsdl:message name="nfeRecepcaoLote2nfeCabecMsg">
  <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg"/>
 </wsdl:message>
 <wsdl:message name="nfeRecepcaoLote2Soap12In">
  <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg"/>
 </wsdl:message>
 <wsdl:portType name="NfeRecepcao2Soap12">
  <wsdl:operation name="nfeRecepcaoLote2">
   <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Transmissao de Lote de NF-e</wsdl:documentation>
   <wsdl:input message="tns:nfeRecepcaoLote2Soap12In"/>
   <wsdl:output message="tns:nfeRecepcaoLote2Soap12Out"/>
  </wsdl:operation>
 </wsdl:portType>
 <wsdl:binding name="NfeRecepcao2Soap12" type="tns:NfeRecepcao2Soap12">
  <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
  <wsdl:operation name="nfeRecepcaoLote2">
   <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRecepcao2/nfeRecepcaoLote2" style="document"/>
   <wsdl:input>
    <soap12:body use="literal"/>
    <soap12:header message="tns:nfeRecepcaoLote2nfeCabecMsg" part="nfeCabecMsg" use="literal"/>
   </wsdl:input>
   <wsdl:output>
    <soap12:body use="literal"/>
    <soap12:header message="tns:nfeRecepcaoLote2nfeCabecMsg" part="nfeCabecMsg" use="literal"/>
   </wsdl:output>
  </wsdl:operation>
 </wsdl:binding>
 <wsdl:service name="NfeRecepcao2">
  <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Servico destinado a recepcao de mensagens de lote de NF-e.</wsdl:documentation>
  <wsdl:port binding="tns:NfeRecepcao2Soap12" name="NfeRecepcaoServicePort">
   <soap12:address location="https://nfe2.fazenda.pr.gov.br/nfe/NFeRecepcao2"/>
  </wsdl:port>
 </wsdl:service>
</wsdl:definitions>