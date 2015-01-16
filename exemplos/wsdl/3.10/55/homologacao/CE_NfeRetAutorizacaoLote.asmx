<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao">
  <wsdl:types>
<s:schema xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao">
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
          <s:element maxOccurs="1" minOccurs="0" name="versaoDados" type="s:string"/>
          <s:element maxOccurs="1" minOccurs="0" name="cUF" type="s:string"/>
        </s:sequence>
        <s:anyAttribute/>
      </s:complexType>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeRetAutorizacaoLoteSoapOut">
    <wsdl:part element="tns:nfeRetAutorizacaoLoteResult" name="nfeRetAutorizacaoLoteResult">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeRetAutorizacaoLotenfeCabecMsg">
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeRetAutorizacaoLoteSoapIn">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeRetAutorizacaoSoap">
    <wsdl:operation name="nfeRetAutorizacaoLote">
<wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado a retornar o resultado do processamento do lote de NF-e</wsdl:documentation>
      <wsdl:input message="tns:nfeRetAutorizacaoLoteSoapIn">
    </wsdl:input>
      <wsdl:output message="tns:nfeRetAutorizacaoLoteSoapOut">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeRetAutorizacaoSoap" type="tns:NfeRetAutorizacaoSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeRetAutorizacaoLote">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao/nfeRetAutorizacaoLote" style="document"/>
      <wsdl:input>
        <soap:body use="literal"/>
        <soap:header message="tns:nfeRetAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap:header>
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal"/>
        <soap:header message="tns:nfeRetAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap:header>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeRetAutorizacaoSoap12" type="tns:NfeRetAutorizacaoSoap">
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
    <wsdl:port binding="tns:NfeRetAutorizacaoSoap" name="NfeRetAutorizacaoSoap">
      <soap:address location="https://nfe.sefaz.ce.gov.br/nfe2/services/NfeRetAutorizacao"/>
    </wsdl:port>
    <wsdl:port binding="tns:NfeRetAutorizacaoSoap12" name="NfeRetAutorizacaoSoap12">
      <soap12:address location="https://nfeh.sefaz.ce.gov.br/nfe2/services/NfeRetAutorizacao"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>