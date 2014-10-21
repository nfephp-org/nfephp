<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="consultaCadastro2Result">
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
  <wsdl:message name="consultaCadastro2SoapIn">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg" />
  </wsdl:message>
  <wsdl:message name="consultaCadastro2SoapOut">
    <wsdl:part name="consultaCadastro2Result" element="tns:consultaCadastro2Result" />
  </wsdl:message>
  <wsdl:message name="consultaCadastro2nfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:portType name="CadConsultaCadastro2Soap">
    <wsdl:operation name="consultaCadastro2">
      <wsdl:input message="tns:consultaCadastro2SoapIn" />
      <wsdl:output message="tns:consultaCadastro2SoapOut" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="CadConsultaCadastro2Soap" type="tns:CadConsultaCadastro2Soap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="consultaCadastro2">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2/consultaCadastro2" style="document" />
      <wsdl:input>
        <soap:body use="literal" />
        <soap:header message="tns:consultaCadastro2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
        <soap:header message="tns:consultaCadastro2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="CadConsultaCadastro2Soap12" type="tns:CadConsultaCadastro2Soap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="consultaCadastro2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2/consultaCadastro2" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:consultaCadastro2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
        <soap12:header message="tns:consultaCadastro2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="CadConsultaCadastro2">
    <wsdl:port name="CadConsultaCadastro2Soap" binding="tns:CadConsultaCadastro2Soap">
      <soap:address location="https://hnfe.sefaz.ba.gov.br/webservices/nfenw/CadConsultaCadastro2.asmx" />
    </wsdl:port>
    <wsdl:port name="CadConsultaCadastro2Soap12" binding="tns:CadConsultaCadastro2Soap12">
      <soap12:address location="https://hnfe.sefaz.ba.gov.br/webservices/nfenw/CadConsultaCadastro2.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>