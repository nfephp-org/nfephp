<?xml version="1.0" encoding="utf-8"?>
<wsdl:definitions xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado à consulta do status do serviço prestado pelo Portal da Secretaria de Fazenda Estadual.</wsdl:documentation>
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="nfeStatusServicoNF2Result">
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
  <wsdl:message name="nfeStatusServicoNF2Soap12In">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg" />
  </wsdl:message>
  <wsdl:message name="nfeStatusServicoNF2Soap12Out">
    <wsdl:part name="nfeStatusServicoNF2Result" element="tns:nfeStatusServicoNF2Result" />
  </wsdl:message>
  <wsdl:message name="nfeStatusServicoNF2nfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:portType name="NfeStatusServico2Soap12">
    <wsdl:operation name="nfeStatusServicoNF2">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Consulta Status do Serviço</wsdl:documentation>
      <wsdl:input message="tns:nfeStatusServicoNF2Soap12In" />
      <wsdl:output message="tns:nfeStatusServicoNF2Soap12Out" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeStatusServico2Soap12" type="tns:NfeStatusServico2Soap12">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="nfeStatusServicoNF2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2/nfeStatusServicoNF2" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeStatusServicoNF2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
        <soap12:header message="tns:nfeStatusServicoNF2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeStatusServico2">
    <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado à consulta do status do serviço prestado pelo Portal da Secretaria de Fazenda Estadual.</wsdl:documentation>
    <wsdl:port name="NfeStatusServico2Soap12" binding="tns:NfeStatusServico2Soap12">
      <soap12:address location="https://homologacao.nfe.fazenda.sp.gov.br/ws/nfestatusservico2.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>