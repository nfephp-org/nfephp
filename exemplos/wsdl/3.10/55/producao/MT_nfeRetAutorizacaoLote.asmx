<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeRetAutorizacaoLoteResult">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
      <s:complexType name="nfeCabecMsg">
        <s:sequence>
          <s:element maxOccurs="1" minOccurs="0" name="cUF" type="s:string"/>
          <s:element maxOccurs="1" minOccurs="0" name="versaoDados" type="s:string"/>
        </s:sequence>
        <s:anyAttribute/>
      </s:complexType>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeRetAutorizacaoLoteSoap12Out">
    <wsdl:part name="nfeRetAutorizacaoLoteResult" element="tns:nfeRetAutorizacaoLoteResult">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeRetAutorizacaoLotenfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeRetAutorizacaoLoteSoap12In">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeRetAutorizacaoSoap12">
    <wsdl:operation name="nfeRetAutorizacaoLote">
      <wsdl:input message="tns:nfeRetAutorizacaoLoteSoap12In">
    </wsdl:input>
      <wsdl:output message="tns:nfeRetAutorizacaoLoteSoap12Out">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeRetAutorizacaoSoap12" type="tns:NfeRetAutorizacaoSoap12">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeRetAutorizacaoLote">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao/nfeRetAutorizacaoLote" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
        <soap12:header message="tns:nfeRetAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
        <soap12:header message="tns:nfeRetAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeRetAutorizacao">
    <wsdl:port name="NfeRetAutorizacaoSoap12" binding="tns:NfeRetAutorizacaoSoap12">
      <soap12:address location="https://nfe.sefaz.mt.gov.br/nfews/v2/services/NfeRetAutorizacao"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>