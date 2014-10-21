<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeConsultaNFResult">
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
  <wsdl:message name="nfeConsultaNFSoapIn">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg" />
  </wsdl:message>
  <wsdl:message name="nfeConsultaNFSoapOut">
    <wsdl:part name="nfeConsultaNFResult" element="tns:nfeConsultaNFResult" />
  </wsdl:message>
  <wsdl:message name="nfeConsultaNFnfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:portType name="NfeConsultaSoap">
    <wsdl:operation name="nfeConsultaNF">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado à consulta da NF-e.</wsdl:documentation>
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
        <soap:header message="tns:nfeConsultaNFnfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
        <soap:header message="tns:nfeConsultaNFnfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeConsultaSoap12" type="tns:NfeConsultaSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeConsultaNF">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta/nfeConsultaNF" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeConsultaNFnfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeConsultaNFnfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeConsulta">
    <wsdl:port name="NfeConsultaSoap" binding="tns:NfeConsultaSoap">
      <soap:address location="https://hnfe.sefaz.ba.gov.br/webservices/NfeConsulta/NfeConsulta.asmx" />
    </wsdl:port>
    <wsdl:port name="NfeConsultaSoap12" binding="tns:NfeConsultaSoap12">
      <soap12:address location="https://hnfe.sefaz.ba.gov.br/webservices/NfeConsulta/NfeConsulta.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>