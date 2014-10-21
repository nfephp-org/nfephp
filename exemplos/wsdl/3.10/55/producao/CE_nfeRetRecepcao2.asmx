<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2">
  <wsdl:types>
<s:schema xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2">
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
          <s:element maxOccurs="1" minOccurs="0" name="versaoDados" type="s:string"/>
          <s:element maxOccurs="1" minOccurs="0" name="cUF" type="s:string"/>
        </s:sequence>
        <s:anyAttribute/>
      </s:complexType>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeRetRecepcao2SoapOut">
    <wsdl:part element="tns:nfeRetRecepcao2Result" name="nfeRetRecepcao2Result">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeRetRecepcao2nfeCabecMsg">
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeRetRecepcao2SoapIn">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeRetRecepcao2Soap">
    <wsdl:operation name="nfeRetRecepcao2">
<wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado a retornar o resultado do processamento do lote de NF-e</wsdl:documentation>
      <wsdl:input message="tns:nfeRetRecepcao2SoapIn">
    </wsdl:input>
      <wsdl:output message="tns:nfeRetRecepcao2SoapOut">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeRetRecepcao2Soap12" type="tns:NfeRetRecepcao2Soap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeRetRecepcao2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2/nfeRetRecepcao2" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
        <soap12:header message="tns:nfeRetRecepcao2nfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
        <soap12:header message="tns:nfeRetRecepcao2nfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeRetRecepcao2">
    <wsdl:port binding="tns:NfeRetRecepcao2Soap12" name="NfeRetRecepcao2Soap12">
      <soap12:address location="https://nfe.sefaz.ce.gov.br/nfe2/services/NfeRetRecepcao2"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>