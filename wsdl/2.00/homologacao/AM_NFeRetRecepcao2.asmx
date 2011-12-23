<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:s="http://www.w3.org/2001/XMLSchema">
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any/>
          </s:sequence>
        </s:complexType>

      </s:element>
      <s:element name="nfeRetRecepcao2Result">
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
  <wsdl:message name="nfeRetRecepcao2nfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeRetRecepcao2Soap12Out">
    <wsdl:part name="nfeRetRecepcao2Result" element="tns:nfeRetRecepcao2Result">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeRetRecepcao2Soap12In">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeRetRecepcao2Soap12">
    <wsdl:operation name="nfeRetRecepcao2">
      <wsdl:input message="tns:nfeRetRecepcao2Soap12In">
    </wsdl:input>
      <wsdl:output message="tns:nfeRetRecepcao2Soap12Out">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeRetRecepcao2Soap12" type="tns:NfeRetRecepcao2Soap12">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeRetRecepcao2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2/nfeRetRecepcao2" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
        <soap12:header message="tns:nfeRetRecepcao2nfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
        <soap12:header message="tns:nfeRetRecepcao2nfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeRetRecepcao2">
    <wsdl:port name="NfeRetRecepcao2Soap12" binding="tns:NfeRetRecepcao2Soap12">
      <soap12:address location="https://homnfe.sefaz.am.gov.br/services2/services/NfeRetRecepcao2"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>