<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsultaDest" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsultaDest" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsultaDest">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeConsultaNFDestResult">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeCabecMsg" type="tns:nfeCabecMsg" />
      <s:complexType name="nfeCabecMsg">
        <s:sequence>
          <s:element minOccurs="0" maxOccurs="1" name="cUF" type="s:string" />
          <s:element minOccurs="0" maxOccurs="1" name="versaoDados" type="s:string" />
        </s:sequence>
        <s:anyAttribute />
      </s:complexType>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeConsultaNFDestSoapIn">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg" />
  </wsdl:message>
  <wsdl:message name="nfeConsultaNFDestSoapOut">
    <wsdl:part name="nfeConsultaNFDestResult" element="tns:nfeConsultaNFDestResult" />
  </wsdl:message>
  <wsdl:message name="nfeConsultaNFDestnfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:portType name="NFeConsultaDestSoap">
    <wsdl:operation name="nfeConsultaNFDest">
      <wsdl:input message="tns:nfeConsultaNFDestSoapIn" />
      <wsdl:output message="tns:nfeConsultaNFDestSoapOut" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NFeConsultaDestSoap" type="tns:NFeConsultaDestSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeConsultaNFDest">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsultaDest/nfeConsultaNFDest" style="document" />
      <wsdl:input>
        <soap:body use="literal" />
        <soap:header message="tns:nfeConsultaNFDestnfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NFeConsultaDestSoap12" type="tns:NFeConsultaDestSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeConsultaNFDest">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsultaDest/nfeConsultaNFDest" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeConsultaNFDestnfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NFeConsultaDest">
    <wsdl:port name="NFeConsultaDestSoap" binding="tns:NFeConsultaDestSoap">
      <soap:address location="https://www.nfe.fazenda.gov.br/NFeConsultaDest/NFeConsultaDest.asmx" />
    </wsdl:port>
    <wsdl:port name="NFeConsultaDestSoap12" binding="tns:NFeConsultaDestSoap12">
      <soap12:address location="https://www.nfe.fazenda.gov.br/NFeConsultaDest/NFeConsultaDest.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>