<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions name="NfeConsulta2" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2" xmlns:ns1="http://schemas.xmlsoap.org/soap/http" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <wsdl:types>
<xs:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2" version="1.0" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
<xs:element name="nfeConsultaNF2Result" type="tns:nfeConsultaNF2Result"/>
<xs:element name="nfeDadosMsg">
<xs:complexType mixed="true">
<xs:sequence>
<xs:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:complexType name="nfeConsultaNF2Result">
<xs:sequence>
<xs:element maxOccurs="unbounded" minOccurs="0" name="retConsSitNFe" type="xs:anyType"/>
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
  <wsdl:message name="nfeConsultaNF2Response">
    <wsdl:part element="tns:nfeConsultaNF2Result" name="nfeConsultaNF2Result">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeConsultaNF2">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeConsulta2Soap">
    <wsdl:operation name="nfeConsultaNF2" parameterOrder="nfeDadosMsg nfeCabecMsg">
      <wsdl:input message="tns:nfeConsultaNF2" name="nfeConsultaNF2">
    </wsdl:input>
      <wsdl:output message="tns:nfeConsultaNF2Response" name="nfeConsultaNF2Response">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeConsulta2SoapBinding" type="tns:NfeConsulta2Soap">
    <soap12:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeConsultaNF2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2/nfeConsultaNF2" style="document"/>
      <wsdl:input name="nfeConsultaNF2">
        <soap12:header message="tns:nfeConsultaNF2" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeDadosMsg" use="literal"/>
      </wsdl:input>
      <wsdl:output name="nfeConsultaNF2Response">
        <soap12:header message="tns:nfeConsultaNF2Response" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeConsultaNF2Result" use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeConsulta2">
    <wsdl:port binding="tns:NfeConsulta2SoapBinding" name="NfeConsulta2Soap12">
      <soap12:address location="https://nfe.fazenda.mg.gov.br/nfe2/services/NfeConsulta2"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>