<?xml version="1.0" encoding="utf-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeConsultaNF2Result">
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
  <wsdl:message name="nfeConsultaNF2SoapIn">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg" />
  </wsdl:message>
  <wsdl:message name="nfeConsultaNF2SoapOut">
    <wsdl:part name="nfeConsultaNF2Result" element="tns:nfeConsultaNF2Result" />
  </wsdl:message>
  <wsdl:message name="nfeConsultaNF2nfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:portType name="NfeConsulta2Soap">
    <wsdl:operation name="nfeConsultaNF2">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado ao atendimento de solicitações de consulta da situação atual da NF-e na Base de Dados do Portal da Sefaz Virtual do Ambiente Nacional</wsdl:documentation>
      <wsdl:input message="tns:nfeConsultaNF2SoapIn" />
      <wsdl:output message="tns:nfeConsultaNF2SoapOut" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeConsulta2Soap" type="tns:NfeConsulta2Soap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeConsultaNF2">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2/nfeConsultaNF2" style="document" />
      <wsdl:input>
        <soap:body use="literal" />
        <soap:header message="tns:nfeConsultaNF2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeConsulta2Soap12" type="tns:NfeConsulta2Soap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeConsultaNF2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2/nfeConsultaNF2" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeConsultaNF2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeConsulta2">
    <wsdl:port name="NfeConsulta2Soap" binding="tns:NfeConsulta2Soap">
      <soap:address location="https://hom.sefazvirtual.fazenda.gov.br/NfeConsulta2/NfeConsulta2.asmx" />
    </wsdl:port>
    <wsdl:port name="NfeConsulta2Soap12" binding="tns:NfeConsulta2Soap12">
      <soap12:address location="https://hom.sefazvirtual.fazenda.gov.br/NfeConsulta2/NfeConsulta2.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>