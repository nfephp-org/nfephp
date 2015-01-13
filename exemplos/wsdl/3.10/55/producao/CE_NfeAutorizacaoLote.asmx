<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao">
  <wsdl:types>
<s:schema xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any/>
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeAutorizacaoLoteResult">
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
      <s:element name="nfeDadosMsgZip" type="s:string"/>
      <s:element name="nfeAutorizacaoLoteZipResult">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any/>
          </s:sequence>
        </s:complexType>
      </s:element>
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeAutorizacaoLotenfeCabecMsg">
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeAutorizacaoLoteZipnfeCabecMsg">
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeAutorizacaoLoteSoapIn">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeAutorizacaoLoteSoapOut">
    <wsdl:part element="tns:nfeAutorizacaoLoteResult" name="nfeAutorizacaoLoteResult">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeAutorizacaoLoteZipSoapOut">
    <wsdl:part element="tns:nfeAutorizacaoLoteZipResult" name="nfeAutorizacaoLoteZipResult">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeAutorizacaoLoteZipSoapIn">
    <wsdl:part element="tns:nfeDadosMsgZip" name="nfeDadosMsgZip">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeAutorizacaoSoap">
    <wsdl:operation name="nfeAutorizacaoLote">
<wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado à recepção de mensagens de lote de NF-e</wsdl:documentation>
      <wsdl:input message="tns:nfeAutorizacaoLoteSoapIn">
    </wsdl:input>
      <wsdl:output message="tns:nfeAutorizacaoLoteSoapOut">
    </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="nfeAutorizacaoLoteZip">
<wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado à recepção de mensagens de lote de NF-e compactada</wsdl:documentation>
      <wsdl:input message="tns:nfeAutorizacaoLoteZipSoapIn">
    </wsdl:input>
      <wsdl:output message="tns:nfeAutorizacaoLoteZipSoapOut">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeAutorizacaoSoap12" type="tns:NfeAutorizacaoSoap">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeAutorizacaoLote">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/nfeAutorizacaoLote" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
        <soap12:header message="tns:nfeAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
        <soap12:header message="tns:nfeAutorizacaoLoteZipnfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="nfeAutorizacaoLoteZip">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/nfeAutorizacaoLoteZip" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
        <soap12:header message="tns:nfeAutorizacaoLoteZipnfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
        <soap12:header message="tns:nfeAutorizacaoLoteZipnfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:binding name="NfeAutorizacaoSoap" type="tns:NfeAutorizacaoSoap">
    <soap:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeAutorizacaoLote">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/nfeAutorizacaoLote" style="document"/>
      <wsdl:input>
        <soap:body use="literal"/>
        <soap:header message="tns:nfeAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap:header>
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="nfeAutorizacaoLoteZip">
      <soap:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/nfeAutorizacaoLoteZip" style="document"/>
      <wsdl:input>
        <soap:body use="literal"/>
        <soap:header message="tns:nfeAutorizacaoLoteZipnfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap:header>
      </wsdl:input>
      <wsdl:output>
        <soap:body use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeAutorizacao">
    <wsdl:port binding="tns:NfeAutorizacaoSoap12" name="NfeAutorizacaoSoap12">
      <soap12:address location="https://nfe.sefaz.ce.gov.br/nfe2/services/NfeAutorizacao"/>
    </wsdl:port>
    <wsdl:port binding="tns:NfeAutorizacaoSoap" name="NfeAutorizacaoSoap">
      <soap:address location="https://nfe.sefaz.ce.gov.br/nfe2/services/NfeAutorizacao"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>