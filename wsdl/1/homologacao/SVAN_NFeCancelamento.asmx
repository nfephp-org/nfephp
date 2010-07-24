<?xml version="1.0" encoding="utf-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeCancelamento" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeCancelamento" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeCancelamento">
      <s:element name="nfeCancelamentoNF">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeCabecMsg" type="s:string" />
            <s:element minOccurs="0" maxOccurs="1" name="nfeDadosMsg" type="s:string" />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeCancelamentoNFResponse">
        <s:complexType>
          <s:sequence>
            <s:element minOccurs="0" maxOccurs="1" name="nfeCancelamentoNFResult" type="s:string" />
          </s:sequence>
        </s:complexType>
      </s:element>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeCancelamentoNFSoapIn">
    <wsdl:part name="parameters" element="tns:nfeCancelamentoNF" />
  </wsdl:message>
  <wsdl:message name="nfeCancelamentoNFSoapOut">
    <wsdl:part name="parameters" element="tns:nfeCancelamentoNFResponse" />
  </wsdl:message>
  <wsdl:portType name="NfeCancelamentoSoap">
    <wsdl:operation name="nfeCancelamentoNF">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado ao atendimento de solicitações de cancelamento de Notas Fiscais Eletrônicas</wsdl:documentation>
      <wsdl:input message="tns:nfeCancelamentoNFSoapIn" />
      <wsdl:output message="tns:nfeCancelamentoNFSoapOut" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeCancelamentoSoap" type="tns:NfeCancelamentoSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeCancelamentoNF">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeCancelamento/nfeCancelamentoNF" style="document" />
      <wsdl:input>
        <soap:body use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeCancelamentoSoap12" type="tns:NfeCancelamentoSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeCancelamentoNF">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeCancelamento/nfeCancelamentoNF" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeCancelamento">
    <wsdl:port name="NfeCancelamentoSoap" binding="tns:NfeCancelamentoSoap">
      <soap:address location="https://hom.nfe.fazenda.gov.br/NFeCancelamento/NFeCancelamento.asmx" />
    </wsdl:port>
    <wsdl:port name="NfeCancelamentoSoap12" binding="tns:NfeCancelamentoSoap12">
      <soap12:address location="https://hom.nfe.fazenda.gov.br/NFeCancelamento/NFeCancelamento.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>