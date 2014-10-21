<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions name="CadConsultaCadastro2" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:ns1="http://schemas.xmlsoap.org/soap/http" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <wsdl:types>
<xs:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" version="1.0" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:element name="consultaCadastro2Result" type="tns:consultaCadastro2Result"/>
<xs:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
<xs:element name="nfeDadosMsg">
<xs:complexType mixed="true">
<xs:sequence>
<xs:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:complexType name="consultaCadastro2Result">
<xs:sequence>
<xs:element maxOccurs="unbounded" minOccurs="0" name="retConsCad" type="xs:anyType"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="nfeCabecMsg">
<xs:sequence>
<xs:element minOccurs="0" name="cUF" type="xs:string"/>
<xs:element minOccurs="0" name="versaoDados" type="xs:string"/>
</xs:sequence>
<xs:anyAttribute namespace="##other" processContents="skip"/>
</xs:complexType>
</xs:schema>
  </wsdl:types>
  <wsdl:message name="consultaCadastro2">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="consultaCadastro2Response">
    <wsdl:part element="tns:consultaCadastro2Result" name="consultaCadastro2Result">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="CadConsultaCadastro2Soap12">
    <wsdl:operation name="consultaCadastro2" parameterOrder="nfeDadosMsg nfeCabecMsg">
      <wsdl:input message="tns:consultaCadastro2" name="consultaCadastro2">
    </wsdl:input>
      <wsdl:output message="tns:consultaCadastro2Response" name="consultaCadastro2Response">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="CadConsultaCadastro2SoapBinding" type="tns:CadConsultaCadastro2Soap12">
    <soap12:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="consultaCadastro2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2/consultaCadastro2" style="document"/>
      <wsdl:input name="consultaCadastro2">
        <soap12:header message="tns:consultaCadastro2" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeDadosMsg" use="literal"/>
      </wsdl:input>
      <wsdl:output name="consultaCadastro2Response">
        <soap12:header message="tns:consultaCadastro2Response" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="consultaCadastro2Result" use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="CadConsultaCadastro2">
    <wsdl:port binding="tns:CadConsultaCadastro2SoapBinding" name="CadConsultaCadastro2Soap12">
      <soap12:address location="https://nfe.fazenda.mg.gov.br/nfe2/services/cadconsultacadastro2"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>