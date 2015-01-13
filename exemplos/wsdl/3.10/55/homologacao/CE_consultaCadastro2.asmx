<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2">
  <wsdl:types>
<s:schema xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2">
			<s:element name="nfeDadosMsg">
				<s:complexType mixed="true">
					<s:sequence>
						<s:any/>
					</s:sequence>
				</s:complexType>
			</s:element>
			<s:element name="cadConsultaCadastro2Result">
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
			<s:element name="consultaCadastro2">
				<s:complexType>
					<s:sequence>

						<s:element name="in" type="s:string"/>
					</s:sequence>
				</s:complexType>
			</s:element>
			<s:element name="consultaCadastro2Response">
				<s:complexType>
					<s:sequence>

						<s:element name="out" type="s:string"/>
					</s:sequence>
				</s:complexType>
			</s:element>
		</s:schema>
  </wsdl:types>
  <wsdl:message name="consultaCadastro2Request">
    <wsdl:part element="tns:consultaCadastro2" name="nfeDadosMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="cadConsultaCadastro2nfeCabecMsg">
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="cadConsultaCadastro2Soap12Out">
    <wsdl:part element="tns:cadConsultaCadastro2Result" name="cadConsultaCadastro2Result">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="consultaCadastro2Response">
    <wsdl:part element="tns:consultaCadastro2Response" name="cadConsultaCadastro2Result">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="cadConsultaCadastro2Soap12In">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="CadConsultaCadastro2Soap12">
    <wsdl:operation name="consultaCadastro2">
      <wsdl:input message="tns:cadConsultaCadastro2Soap12In">
    </wsdl:input>
      <wsdl:output message="tns:cadConsultaCadastro2Soap12Out">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="CadConsultaCadastro2Soap12" type="tns:CadConsultaCadastro2Soap12">
    <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="consultaCadastro2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2/consultaCadastro2" style="document"/>
      <wsdl:input>
        <soap12:body use="literal"/>
        <soap12:header message="tns:cadConsultaCadastro2nfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:input>
      <wsdl:output>
        <soap12:body use="literal"/>
        <soap12:header message="tns:cadConsultaCadastro2nfeCabecMsg" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="CadConsultaCadastro2">
    <wsdl:port binding="tns:CadConsultaCadastro2Soap12" name="CadConsultaCadastro2Soap12">
      <soap12:address location="https://nfeh.sefaz.ce.gov.br/nfe2/services/CadConsultaCadastro2"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>