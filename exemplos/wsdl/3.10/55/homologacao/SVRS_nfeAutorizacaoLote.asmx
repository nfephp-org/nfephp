<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeAutorizacaoLoteResult">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeCabecMsg" type="tns:nfeCabecMsg" />
      <s:complexType name="nfeCabecMsg">
        <s:sequence>
          <s:element minOccurs="0" maxOccurs="1" name="cUF" type="s:string" />
          <s:element minOccurs="0" maxOccurs="1" name="versaoDados" type="s:string" />
        </s:sequence>
        <s:anyAttribute />
      </s:complexType>
      <s:element name="nfeDadosMsgZip" type="s:string" />
      <s:element name="nfeAutorizacaoLoteZipResult">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeAutorizacaoLoteSoap12In">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg" />
  </wsdl:message>
  <wsdl:message name="nfeAutorizacaoLoteSoap12Out">
    <wsdl:part name="nfeAutorizacaoLoteResult" element="tns:nfeAutorizacaoLoteResult" />
  </wsdl:message>
  <wsdl:message name="nfeAutorizacaoLotenfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:message name="nfeAutorizacaoLoteZipSoap12In">
    <wsdl:part name="nfeDadosMsgZip" element="tns:nfeDadosMsgZip" />
  </wsdl:message>
  <wsdl:message name="nfeAutorizacaoLoteZipSoap12Out">
    <wsdl:part name="nfeAutorizacaoLoteZipResult" element="tns:nfeAutorizacaoLoteZipResult" />
  </wsdl:message>
  <wsdl:message name="nfeAutorizacaoLoteZipnfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:portType name="NfeAutorizacaoSoap12">
    <wsdl:operation name="nfeAutorizacaoLote">
      <wsdl:input message="tns:nfeAutorizacaoLoteSoap12In" />
      <wsdl:output message="tns:nfeAutorizacaoLoteSoap12Out" />
    </wsdl:operation>
    <wsdl:operation name="nfeAutorizacaoLoteZip">
      <wsdl:input message="tns:nfeAutorizacaoLoteZipSoap12In" />
      <wsdl:output message="tns:nfeAutorizacaoLoteZipSoap12Out" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeAutorizacaoSoap12" type="tns:NfeAutorizacaoSoap12">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeAutorizacaoLote">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/nfeAutorizacaoLote" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="nfeAutorizacaoLoteZip">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/nfeAutorizacaoLoteZip" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeAutorizacaoLoteZipnfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeAutorizacaoLoteZipnfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeAutorizacao">
    <wsdl:port name="NfeAutorizacaoSoap12" binding="tns:NfeAutorizacaoSoap12">
      <soap12:address location="https://homologacao.nfe.sefazvirtual.rs.gov.br/ws/NfeAutorizacao/NFeAutorizacao.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>