<?xml version="1.0" encoding="utf-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao">
      <s:element name="nfeRetRecepcao">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeCabecMsg" type="s:string" />
            <s:element minOccurs="0" maxOccurs="1" name="nfeDadosMsg" type="s:string" />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeRetRecepcaoResponse">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeRetRecepcaoResult" type="s:string" />
          </s:sequence>
        </s:complexType>
      </s:element>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeRetRecepcaoSoapIn">
    <wsdl:part name="parameters" element="tns:nfeRetRecepcao" />
  </wsdl:message>
  <wsdl:message name="nfeRetRecepcaoSoapOut">
    <wsdl:part name="parameters" element="tns:nfeRetRecepcaoResponse" />
  </wsdl:message>
  <wsdl:portType name="NfeRetRecepcaoSoap">
    <wsdl:operation name="nfeRetRecepcao">
      <wsdl:input message="tns:nfeRetRecepcaoSoapIn" />
      <wsdl:output message="tns:nfeRetRecepcaoSoapOut" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeRetRecepcaoSoap" type="tns:NfeRetRecepcaoSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeRetRecepcao">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao/nfeRetRecepcao" style="document" />
      <wsdl:input>
        <soap:body use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeRetRecepcaoSoap12" type="tns:NfeRetRecepcaoSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeRetRecepcao">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao/nfeRetRecepcao" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeRetRecepcao">
    <wsdl:port name="NfeRetRecepcaoSoap" binding="tns:NfeRetRecepcaoSoap">
      <soap:address location="https://hom.nfe.fazenda.gov.br/SCAN/NfeRetRecepcao/NfeRetRecepcao.asmx" />
    </wsdl:port>
    <wsdl:port name="NfeRetRecepcaoSoap12" binding="tns:NfeRetRecepcaoSoap12">
      <soap12:address location="https://hom.nfe.fazenda.gov.br/SCAN/NfeRetRecepcao/NfeRetRecepcao.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>