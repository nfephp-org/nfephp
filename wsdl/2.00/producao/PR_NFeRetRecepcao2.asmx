<wsdl:definitions targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
 <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Servico destinado a retornar o resultado do processamento do lote de NF-e.</wsdl:documentation>
 <wsdl:types>
  <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2">
   <s:element name="nfeDadosMsg">
    <s:complexType mixed="true">
     <s:sequence>
      <s:any/>
     </s:sequence>
    </s:complexType>
   </s:element>
   <s:element name="nfeRetRecepcao2Result">
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
 <wsdl:message name="nfeRetRecepcao2Soap12In">
  <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg"/>
 </wsdl:message>
 <wsdl:message name="nfeRetRecepcao2nfeCabecMsg">
  <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg"/>
 </wsdl:message>
 <wsdl:message name="nfeRetRecepcao2Soap12Out">
  <wsdl:part element="tns:nfeRetRecepcao2Result" name="nfeRetRecepcao2Result"/>
 </wsdl:message>
 <wsdl:portType name="NfeRetRecepcao2Soap12">
  <wsdl:operation name="nfeRetRecepcao2">
   <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Consulta Processamento de Lote de NF-e</wsdl:documentation>
   <wsdl:input message="tns:nfeRetRecepcao2Soap12In"/>
   <wsdl:output message="tns:nfeRetRecepcao2Soap12Out"/>
  </wsdl:operation>
 </wsdl:portType>
 <wsdl:binding name="NfeRetRecepcao2Soap12" type="tns:NfeRetRecepcao2Soap12">
  <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
  <wsdl:operation name="nfeRetRecepcao2">
   <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2/nfeRetRecepcao2" style="document"/>
   <wsdl:input>
    <soap12:body use="literal"/>
    <soap12:header message="tns:nfeRetRecepcao2nfeCabecMsg" part="nfeCabecMsg" use="literal"/>
   </wsdl:input>
   <wsdl:output>
    <soap12:body use="literal"/>
    <soap12:header message="tns:nfeRetRecepcao2nfeCabecMsg" part="nfeCabecMsg" use="literal"/>
   </wsdl:output>
  </wsdl:operation>
 </wsdl:binding>
 <wsdl:service name="NfeRetRecepcao2">
  <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Servico destinado a retornar o resultado do processamento do lote de NF-e.</wsdl:documentation>
  <wsdl:port binding="tns:NfeRetRecepcao2Soap12" name="NfeRetRecepcaoServicePort">
   <soap12:address location="https://nfe2.fazenda.pr.gov.br/nfe/NFeRetRecepcao2"/>
  </wsdl:port>
 </wsdl:service>
</wsdl:definitions>