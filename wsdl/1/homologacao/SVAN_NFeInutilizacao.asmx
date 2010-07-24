<?xml version="1.0" encoding="utf-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao">
      <s:element name="nfeInutilizacaoNF">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeCabecMsg" type="s:string" />
            <s:element minOccurs="0" maxOccurs="1" name="nfeDadosMsg" type="s:string" />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeInutilizacaoNFResponse">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeInutilizacaoNFResult" type="s:string" />
          </s:sequence>
        </s:complexType>
      </s:element>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeInutilizacaoNFSoapIn">
    <wsdl:part name="parameters" element="tns:nfeInutilizacaoNF" />
  </wsdl:message>
  <wsdl:message name="nfeInutilizacaoNFSoapOut">
    <wsdl:part name="parameters" element="tns:nfeInutilizacaoNFResponse" />
  </wsdl:message>
  <wsdl:portType name="NfeInutilizacaoSoap">
    <wsdl:operation name="nfeInutilizacaoNF">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado ao atendimento de solicitações de inutilização de numeração</wsdl:documentation>
      <wsdl:input message="tns:nfeInutilizacaoNFSoapIn" />
      <wsdl:output message="tns:nfeInutilizacaoNFSoapOut" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeInutilizacaoSoap" type="tns:NfeInutilizacaoSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeInutilizacaoNF">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao/nfeInutilizacaoNF" style="document" />
      <wsdl:input>
        <soap:body use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeInutilizacaoSoap12" type="tns:NfeInutilizacaoSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeInutilizacaoNF">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao/nfeInutilizacaoNF" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeInutilizacao">
    <wsdl:port name="NfeInutilizacaoSoap" binding="tns:NfeInutilizacaoSoap">
      <soap:address location="https://hom.nfe.fazenda.gov.br/NFeInutilizacao/NFeInutilizacao.asmx" />
    </wsdl:port>
    <wsdl:port name="NfeInutilizacaoSoap12" binding="tns:NfeInutilizacaoSoap12">
      <soap12:address location="https://hom.nfe.fazenda.gov.br/NFeInutilizacao/NFeInutilizacao.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>