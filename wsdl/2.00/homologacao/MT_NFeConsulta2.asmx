<?xml version="1.0" encoding="UTF-8"?>
<definitions targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2" xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:s="http://www.w3.org/2001/XMLSchema">
    <types>
        <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2">
            <s:element name="nfeDadosMsg">
                <s:complexType mixed="true">
                    <s:sequence>
                        <s:any/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="nfeConsultaNF2Result">
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
    </types>
    <message name="nfeConsultaNF2Soap12In">
        <part name="nfeDadosMsg" element="tns:nfeDadosMsg"/>
    </message>
    <message name="nfeConsultaNF2Soap12Out">
        <part name="nfeConsultaNF2Result" element="tns:nfeConsultaNF2Result"/>
    </message>
    <message name="nfeConsultaNF2nfeCabecMsg">
        <part name="nfeCabecMsg" element="tns:nfeCabecMsg"/>
    </message>
    <portType name="NfeConsulta2Soap12">
        <operation name="nfeConsultaNF2">
            <input message="tns:nfeConsultaNF2Soap12In"/>
            <output message="tns:nfeConsultaNF2Soap12Out"/>
        </operation>
    </portType>
    <binding name="NfeConsulta2Soap12" type="tns:NfeConsulta2Soap12">
        <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
        <operation name="nfeConsultaNF2">
            <soap12:operation style="document" soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeConsulta2/nfeConsultaNF2" soapActionRequired="false"/>
            <input>
                <soap12:body use="literal"/>
                <soap12:header message="tns:nfeConsultaNF2nfeCabecMsg" part="nfeCabecMsg" use="literal"/>
            </input>
            <output>
                <soap12:body use="literal"/>
                <soap12:header message="tns:nfeConsultaNF2nfeCabecMsg" part="nfeCabecMsg" use="literal"/>
            </output>
        </operation>
    </binding>
    <service name="NfeConsulta2">
        <port name="NfeConsulta2Soap12" binding="tns:NfeConsulta2Soap12">
            <soap12:address location="https://homologacao.sefaz.mt.gov.br/nfews/v2/services/NfeConsulta2"/>
        </port>
    </service>
</definitions>