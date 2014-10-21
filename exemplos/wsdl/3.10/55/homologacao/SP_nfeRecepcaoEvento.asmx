<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado ao envio de mensagens de eventos da NF-e.</wsdl:documentation>
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeRecepcaoEventoResult">
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
    </s:schema>
  </wsdl:types>
  <wsdl:message name="nfeRecepcaoEventoSoap12In">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg" />
  </wsdl:message>
  <wsdl:message name="nfeRecepcaoEventoSoap12Out">
    <wsdl:part name="nfeRecepcaoEventoResult" element="tns:nfeRecepcaoEventoResult" />
  </wsdl:message>
  <wsdl:message name="nfeRecepcaoEventonfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:portType name="RecepcaoEventoSoap12">
    <wsdl:operation name="nfeRecepcaoEvento">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Registro de Eventos da NF-e</wsdl:documentation>
      <wsdl:input message="tns:nfeRecepcaoEventoSoap12In" />
      <wsdl:output message="tns:nfeRecepcaoEventoSoap12Out" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="RecepcaoEventoSoap12" type="tns:RecepcaoEventoSoap12">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeRecepcaoEvento">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento/nfeRecepcaoEvento" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeRecepcaoEventonfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeRecepcaoEventonfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="RecepcaoEvento">
    <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado ao envio de mensagens de eventos da NF-e.</wsdl:documentation>
    <wsdl:port name="RecepcaoEventoSoap12" binding="tns:RecepcaoEventoSoap12">
      <soap12:address location="https://homologacao.nfe.fazenda.sp.gov.br/ws/recepcaoevento.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>