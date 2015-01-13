<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeDownloadNFResult">
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
  <wsdl:message name="nfeDownloadNFSoapIn">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg" />
  </wsdl:message>
  <wsdl:message name="nfeDownloadNFSoapOut">
    <wsdl:part name="nfeDownloadNFResult" element="tns:nfeDownloadNFResult" />
  </wsdl:message>
  <wsdl:message name="nfeDownloadNFnfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:portType name="NfeDownloadNFSoap">
    <wsdl:operation name="nfeDownloadNF">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado ao atendimento de solicitações de download de Notas Fiscais Eletrônicas por seus destinatários.</wsdl:documentation>
      <wsdl:input message="tns:nfeDownloadNFSoapIn" />
      <wsdl:output message="tns:nfeDownloadNFSoapOut" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeDownloadNFSoap" type="tns:NfeDownloadNFSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeDownloadNF">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF/nfeDownloadNF" style="document" />
      <wsdl:input>
        <soap:body use="literal" />
        <soap:header message="tns:nfeDownloadNFnfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeDownloadNFSoap12" type="tns:NfeDownloadNFSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeDownloadNF">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF/nfeDownloadNF" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeDownloadNFnfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeDownloadNF">
    <wsdl:port name="NfeDownloadNFSoap" binding="tns:NfeDownloadNFSoap">
      <soap:address location="https://hom.nfe.fazenda.gov.br/NfeDownloadNF/NfeDownloadNF.asmx" />
    </wsdl:port>
    <wsdl:port name="NfeDownloadNFSoap12" binding="tns:NfeDownloadNFSoap12">
      <soap12:address location="https://hom.nfe.fazenda.gov.br/NfeDownloadNF/NfeDownloadNF.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>