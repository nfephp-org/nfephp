<?xml version="1.0" encoding="utf-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta">
      <s:element name="nfeConsultaNF">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeCabecMsg" type="s:string" />
            <s:element minOccurs="0" maxOccurs="1" name="nfeDadosMsg" type="s:string" />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeConsultaNFResponse">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeConsultaNFResult" type="s:string" />
          </s:sequence>
        </s:complexType>
      </s:element>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeConsultaNFSoapIn">
    <wsdl:part name="parameters" element="tns:nfeConsultaNF" />
  </wsdl:message>
  <wsdl:message name="nfeConsultaNFSoapOut">
    <wsdl:part name="parameters" element="tns:nfeConsultaNFResponse" />
  </wsdl:message>
  <wsdl:portType name="NfeConsultaSoap">
    <wsdl:operation name="nfeConsultaNF">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço que consulta a Situação atual da NF-e.</wsdl:documentation>
      <wsdl:input message="tns:nfeConsultaNFSoapIn" />
      <wsdl:output message="tns:nfeConsultaNFSoapOut" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeConsultaSoap" type="tns:NfeConsultaSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeConsultaNF">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta/nfeConsultaNF" style="document" />
      <wsdl:input>
        <soap:body use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeConsultaSoap12" type="tns:NfeConsultaSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeConsultaNF">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta/nfeConsultaNF" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeConsulta">
    <wsdl:port name="NfeConsultaSoap" binding="tns:NfeConsultaSoap">
      <soap:address location="https://hom.nfe.fazenda.gov.br/SCAN/NfeConsulta/NfeConsulta.asmx" />
    </wsdl:port>
    <wsdl:port name="NfeConsultaSoap12" binding="tns:NfeConsultaSoap12">
      <soap12:address location="https://hom.nfe.fazenda.gov.br/SCAN/NfeConsulta/NfeConsulta.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>