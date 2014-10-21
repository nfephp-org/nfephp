<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
  <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado ao atendimento de solicitações de consulta ao Cadastro de Contribuintes do ICMS da Secretaria de Fazenda Estatual.</wsdl:documentation>
  <wsdl:types>
    <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2">
      <s:element name="nfeDadosMsg">
        <s:complexType mixed="true">
          <s:sequence>
            <s:any />
          </s:sequence>
        </s:complexType>
      </s:element>
      <s:element name="consultaCadastro2Result">
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
  <wsdl:message name="consultaCadastro2Soap12In">
    <wsdl:part name="nfeDadosMsg" element="tns:nfeDadosMsg" />
  </wsdl:message>
  <wsdl:message name="consultaCadastro2Soap12Out">
    <wsdl:part name="consultaCadastro2Result" element="tns:consultaCadastro2Result" />
  </wsdl:message>
  <wsdl:message name="consultaCadastro2nfeCabecMsg">
    <wsdl:part name="nfeCabecMsg" element="tns:nfeCabecMsg" />
  </wsdl:message>
  <wsdl:portType name="CadConsultaCadastro2Soap12">
    <wsdl:operation name="consultaCadastro2">
      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Consulta Cadastro de Contribuintes do ICMS</wsdl:documentation>
      <wsdl:input message="tns:consultaCadastro2Soap12In" />
      <wsdl:output message="tns:consultaCadastro2Soap12Out" />
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="CadConsultaCadastro2Soap12" type="tns:CadConsultaCadastro2Soap12">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" />
    <wsdl:operation name="consultaCadastro2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2/consultaCadastro2" style="document" />
      <wsdl:input>
        <soap12:body use="literal" />
        <soap12:header message="tns:consultaCadastro2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal" />
        <soap12:header message="tns:consultaCadastro2nfeCabecMsg" part="nfeCabecMsg" use="literal" />
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="CadConsultaCadastro2">
    <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">Serviço destinado ao atendimento de solicitações de consulta ao Cadastro de Contribuintes do ICMS da Secretaria de Fazenda Estatual.</wsdl:documentation>
    <wsdl:port name="CadConsultaCadastro2Soap12" binding="tns:CadConsultaCadastro2Soap12">
      <soap12:address location="https://nfe.fazenda.sp.gov.br/ws/cadconsultacadastro2.asmx" />
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>