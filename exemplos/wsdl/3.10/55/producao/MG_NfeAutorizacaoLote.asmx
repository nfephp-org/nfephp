<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions name="NfeAutorizacao" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao" xmlns:ns1="http://schemas.xmlsoap.org/soap/http" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <wsdl:types>
<xs:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao" version="1.0" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:element name="nfeAutorizacaoLoteResult" type="tns:nfeAutorizacaoLoteResult"/>
<xs:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
<xs:element name="nfeDadosMsg">
<xs:complexType mixed="true">
<xs:sequence>
<xs:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="nfeDadosMsgZip">
<xs:complexType mixed="true">
<xs:sequence>
<xs:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:complexType name="nfeCabecMsg">
<xs:sequence>
<xs:element minOccurs="0" name="cUF" type="xs:string"/>
<xs:element minOccurs="0" name="versaoDados" type="xs:string"/>
</xs:sequence>
<xs:anyAttribute namespace="##other" processContents="skip"/>
</xs:complexType>
<xs:complexType name="nfeAutorizacaoLoteResult">
<xs:sequence>
<xs:element maxOccurs="unbounded" minOccurs="0" name="retEnviNFe" type="xs:anyType"/>
</xs:sequence>
</xs:complexType>
</xs:schema>
  </wsdl:types>
  <wsdl:message name="NfeAutorizacaoLoteZipResponse">
    <wsdl:part element="tns:nfeAutorizacaoLoteResult" name="nfeAutorizacaoLoteResult">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsgZip">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="NfeAutorizacaoLote">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="NfeAutorizacaoLoteZip">
    <wsdl:part element="tns:nfeDadosMsgZip" name="nfeDadosMsgZip">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsgZip">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="NfeAutorizacaoLoteResponse">
    <wsdl:part element="tns:nfeAutorizacaoLoteResult" name="nfeAutorizacaoLoteResult">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeAutorizacaoSoap">
    <wsdl:operation name="NfeAutorizacaoLote" parameterOrder="nfeDadosMsg nfeCabecMsg">
      <wsdl:input message="tns:NfeAutorizacaoLote" name="NfeAutorizacaoLote">
    </wsdl:input>
      <wsdl:output message="tns:NfeAutorizacaoLoteResponse" name="NfeAutorizacaoLoteResponse">
    </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="NfeAutorizacaoLoteZip" parameterOrder="nfeDadosMsgZip nfeCabecMsgZip">
      <wsdl:input message="tns:NfeAutorizacaoLoteZip" name="NfeAutorizacaoLoteZip">
    </wsdl:input>
      <wsdl:output message="tns:NfeAutorizacaoLoteZipResponse" name="NfeAutorizacaoLoteZipResponse">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeAutorizacaoSoapBinding" type="tns:NfeAutorizacaoSoap">
    <soap12:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="NfeAutorizacaoLote">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/NfeAutorizacaoLote" style="document"/>
      <wsdl:input name="NfeAutorizacaoLote">
        <soap12:header message="tns:NfeAutorizacaoLote" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeDadosMsg" use="literal"/>
      </wsdl:input>
      <wsdl:output name="NfeAutorizacaoLoteResponse">
        <soap12:header message="tns:NfeAutorizacaoLoteResponse" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeAutorizacaoLoteResult" use="literal"/>
      </wsdl:output>
    </wsdl:operation>
    <wsdl:operation name="NfeAutorizacaoLoteZip">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/NfeAutorizacaoLoteZip" style="document"/>
      <wsdl:input name="NfeAutorizacaoLoteZip">
        <soap12:header message="tns:NfeAutorizacaoLoteZip" part="nfeCabecMsgZip" use="literal">
        </soap12:header>
        <soap12:body parts="nfeDadosMsgZip" use="literal"/>
      </wsdl:input>
      <wsdl:output name="NfeAutorizacaoLoteZipResponse">
        <soap12:header message="tns:NfeAutorizacaoLoteZipResponse" part="nfeCabecMsgZip" use="literal">
        </soap12:header>
        <soap12:body parts="nfeAutorizacaoLoteResult" use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeAutorizacao">
    <wsdl:port binding="tns:NfeAutorizacaoSoapBinding" name="NfeAutorizacaoSoap12">
      <soap12:address location="https://nfe.fazenda.mg.gov.br/nfe2/services/NfeAutorizacao"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>