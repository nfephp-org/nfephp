<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions name="NfeRetAutorizacao" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao" xmlns:ns1="http://schemas.xmlsoap.org/soap/http" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <wsdl:types>
<xs:schema attributeFormDefault="unqualified" elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
<xs:element name="nfeDadosMsg">
<xs:complexType mixed="true">
<xs:sequence>
<xs:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="nfeRetAutorizacaoLote" type="tns:nfeRetAutorizacaoLoteResult"/>
<xs:complexType name="nfeRetAutorizacaoLoteResult">
<xs:sequence>
<xs:element maxOccurs="unbounded" minOccurs="0" name="retConsReciNFe" type="xs:anyType"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="nfeCabecMsg">
<xs:sequence>
<xs:element minOccurs="0" name="cUF" type="xs:string"/>
<xs:element minOccurs="0" name="versaoDados" type="xs:string"/>
</xs:sequence>
<xs:anyAttribute namespace="##other" processContents="skip"/>
</xs:complexType>
<xs:element name="nfeRetAutorizacaoLoteResult" nillable="true" type="tns:nfeRetAutorizacaoLoteResult"/>
</xs:schema>
  </wsdl:types>
  <wsdl:message name="nfeRetAutorizacaoLote">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeRetAutorizacaoLoteResponse">
    <wsdl:part element="tns:nfeRetAutorizacaoLoteResult" name="nfeRetAutorizacaoLoteResult">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeRetAutorizacaoSoap">
    <wsdl:operation name="nfeRetAutorizacaoLote" parameterOrder="nfeDadosMsg nfeCabecMsg">
      <wsdl:input message="tns:nfeRetAutorizacaoLote" name="nfeRetAutorizacaoLote">
    </wsdl:input>
      <wsdl:output message="tns:nfeRetAutorizacaoLoteResponse" name="nfeRetAutorizacaoLoteResponse">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeRetAutorizacaoSoapBinding" type="tns:NfeRetAutorizacaoSoap">
    <soap12:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeRetAutorizacaoLote">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao/nfeRetAutorizacaoLote" style="document"/>
      <wsdl:input name="nfeRetAutorizacaoLote">
        <soap12:header message="tns:nfeRetAutorizacaoLote" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeDadosMsg" use="literal"/>
      </wsdl:input>
      <wsdl:output name="nfeRetAutorizacaoLoteResponse">
        <soap12:header message="tns:nfeRetAutorizacaoLoteResponse" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeRetAutorizacaoLoteResult" use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeRetAutorizacao">
    <wsdl:port binding="tns:NfeRetAutorizacaoSoapBinding" name="NfeRetAutorizacaoSoap12">
      <soap12:address location="https://nfe.fazenda.mg.gov.br/nfe2/services/NfeRetAutorizacao"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>