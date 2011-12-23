<?xml version="1.0" encoding="utf-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeRetRecepcao2Result">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeCabecMsg" type="tns:nfeCabecMsg" />
      <s:complexType name="nfeCabecMsg">
        <s:sequence>
          <s:element minOccurs="0" maxOccurs="1" name="versaoDados" type="s:string" />
          <s:element minOccurs="0" maxOccurs="1" name="cUF" type="s:string" />
        </s:sequence>
        <s:anyAttribute />
      </s:complexType>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeRetRecepcao2SoapIn">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg" />
  </wsdl:message>
  <wsdl:message name="nfeRetRecepcao2SoapOut">
    <wsdl:part name="nfeRetRecepcao2Result" element="tns:nfeRetRecepcao2Result" />
  </wsdl:message>
  <wsdl:message name="nfeRetRecepcao2nfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:portType name="NfeRetRecepcao2Soap">
    <wsdl:operation name="nfeRetRecepcao2">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado a retornar o resultado do processamento do lote de NF-e</wsdl:documentation>
      <wsdl:input message="tns:nfeRetRecepcao2SoapIn" />
      <wsdl:output message="tns:nfeRetRecepcao2SoapOut" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeRetRecepcao2Soap" type="tns:NfeRetRecepcao2Soap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeRetRecepcao2">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2/nfeRetRecepcao2" style="document" />
      <wsdl:input>
        <soap:body use="literal" />
        <soap:header message="tns:nfeRetRecepcao2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeRetRecepcao2Soap12" type="tns:NfeRetRecepcao2Soap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeRetRecepcao2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2/nfeRetRecepcao2" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeRetRecepcao2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeRetRecepcao2">
    <wsdl:port name="NfeRetRecepcao2Soap" binding="tns:NfeRetRecepcao2Soap">
      <soap:address location="https://hom.sefazvirtual.fazenda.gov.br/NfeRetRecepcao2/NfeRetRecepcao2.asmx" />
    </wsdl:port>
    <wsdl:port name="NfeRetRecepcao2Soap12" binding="tns:NfeRetRecepcao2Soap12">
      <soap12:address location="https://hom.sefazvirtual.fazenda.gov.br/NfeRetRecepcao2/NfeRetRecepcao2.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>