<?xml version="1.0" encoding="utf-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRecepcao" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRecepcao" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRecepcao">
      <s:element name="nfeRecepcaoLote">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeCabecMsg" type="s:string" />
            <s:element minOccurs="0" maxOccurs="1" name="nfeDadosMsg" type="s:string" />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeRecepcaoLoteResponse">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeRecepcaoLoteResult" type="s:string" />
          </s:sequence>
        </s:complexType>
      </s:element>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeRecepcaoLoteSoapIn">
    <wsdl:part name="parameters" element="tns:nfeRecepcaoLote" />
  </wsdl:message>
  <wsdl:message name="nfeRecepcaoLoteSoapOut">
    <wsdl:part name="parameters" element="tns:nfeRecepcaoLoteResponse" />
  </wsdl:message>
  <wsdl:portType name="NfeRecepcaoSoap">
    <wsdl:operation name="nfeRecepcaoLote">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado à recepção de mensagens de lote de NF-e</wsdl:documentation>
      <wsdl:input message="tns:nfeRecepcaoLoteSoapIn" />
      <wsdl:output message="tns:nfeRecepcaoLoteSoapOut" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeRecepcaoSoap" type="tns:NfeRecepcaoSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeRecepcaoLote">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRecepcao/nfeRecepcaoLote" style="document" />
      <wsdl:input>
        <soap:body use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeRecepcaoSoap12" type="tns:NfeRecepcaoSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeRecepcaoLote">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRecepcao/nfeRecepcaoLote" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeRecepcao">
    <wsdl:port name="NfeRecepcaoSoap" binding="tns:NfeRecepcaoSoap">
      <soap:address location="https://hom.nfe.fazenda.gov.br/NfeRecepcao/NfeRecepcao.asmx" />
    </wsdl:port>
    <wsdl:port name="NfeRecepcaoSoap12" binding="tns:NfeRecepcaoSoap12">
      <soap12:address location="https://hom.nfe.fazenda.gov.br/NfeRecepcao/NfeRecepcao.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>