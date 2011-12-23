<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico">
      <s:element name="nfeStatusServicoNF">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeCabecMsg" type="s:string"/>
            <s:element minOccurs="0" maxOccurs="1" name="nfeDadosMsg" type="s:string"/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeStatusServicoNFResponse">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeStatusServicoNFResult" type="s:string"/>
          </s:sequence>
        </s:complexType>
      </s:element>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeStatusServicoNFSoapIn">
    <wsdl:part name="parameters" element="tns:nfeStatusServicoNF"/>
  </wsdl:message>
  <wsdl:message name="nfeStatusServicoNFSoapOut">
    <wsdl:part name="parameters" element="tns:nfeStatusServicoNFResponse"/>
  </wsdl:message>
  <wsdl:portType name="NfeStatusServicoSoap">
    <wsdl:operation name="nfeStatusServicoNF">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado à consulta do status do serviço prestado pela Sefaz Virtual do Ambiente Nacional</wsdl:documentation>
      <wsdl:input message="tns:nfeStatusServicoNFSoapIn"/>
      <wsdl:output message="tns:nfeStatusServicoNFSoapOut"/>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeStatusServicoSoap" type="tns:NfeStatusServicoSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeStatusServicoNF">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico/nfeStatusServicoNF" style="document"/>
      <wsdl:input>
        <soap:body use="literal"/>
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeStatusServicoSoap12" type="tns:NfeStatusServicoSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeStatusServicoNF">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico/nfeStatusServicoNF" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeStatusServico">
    <wsdl:port name="NfeStatusServicoSoap" binding="tns:NfeStatusServicoSoap">
      <soap:address location="https://hom.nfe.fazenda.gov.br/NFeStatusServico/NFeStatusServico.asmx"/>
    </wsdl:port>
    <wsdl:port name="NfeStatusServicoSoap12" binding="tns:NfeStatusServicoSoap12">
      <soap12:address location="https://hom.nfe.fazenda.gov.br/NFeStatusServico/NFeStatusServico.asmx"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>