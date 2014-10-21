<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions name="NfeStatusServico2" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2" xmlns:ns1="http://schemas.xmlsoap.org/soap/http" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <wsdl:types>
<xs:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2" version="1.0" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
<xs:element name="nfeDadosMsg">
<xs:complexType mixed="true">
<xs:sequence>
<xs:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="nfeStatusServicoNF2Result" type="tns:nfeStatusServicoNF2Result"/>
<xs:complexType name="nfeStatusServicoNF2Result">
<xs:sequence>
<xs:element maxOccurs="unbounded" minOccurs="0" name="retConsStatServ" type="xs:anyType"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="nfeCabecMsg">
<xs:sequence>
<xs:element minOccurs="0" name="cUF" type="xs:string"/>
<xs:element minOccurs="0" name="versaoDados" type="xs:string"/>
</xs:sequence>
<xs:anyAttribute namespace="##other" processContents="skip"/>
</xs:complexType>
</xs:schema>
  </wsdl:types>
  <wsdl:message name="nfeStatusServicoNF2">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeStatusServicoNF2Response">
    <wsdl:part element="tns:nfeStatusServicoNF2Result" name="nfeStatusServicoNF2Result">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeStatusServico2Soap">
    <wsdl:operation name="nfeStatusServicoNF2" parameterOrder="nfeDadosMsg nfeCabecMsg">
      <wsdl:input message="tns:nfeStatusServicoNF2" name="nfeStatusServicoNF2">
    </wsdl:input>
      <wsdl:output message="tns:nfeStatusServicoNF2Response" name="nfeStatusServicoNF2Response">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeStatusServico2SoapBinding" type="tns:NfeStatusServico2Soap">
    <soap12:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeStatusServicoNF2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2/nfeStatusServicoNF2" style="document"/>
      <wsdl:input name="nfeStatusServicoNF2">
        <soap12:header message="tns:nfeStatusServicoNF2" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeDadosMsg" use="literal"/>
      </wsdl:input>
      <wsdl:output name="nfeStatusServicoNF2Response">
        <soap12:header message="tns:nfeStatusServicoNF2Response" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeStatusServicoNF2Result" use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeStatusServico2">
    <wsdl:port binding="tns:NfeStatusServico2SoapBinding" name="NfeStatusServico2Soap12">
      <soap12:address location="https://hnfe.fazenda.mg.gov.br/nfe2/services/NfeStatusServico2"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>