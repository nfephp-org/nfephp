<?xml version="1.0" encoding="UTF-8"?>
HTTP/1.1 200 OK
Server: Apache-Coyote/1.1
Content-Type: text/xml
Transfer-Encoding: chunked
Date: Tue, 21 Oct 2014 18:45:42 GMT

<?xml version='1.0' encoding='UTF-8'?><definitions xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:wsp1_2="http://schemas.xmlsoap.org/ws/2004/09/policy" xmlns:wsp="http://www.w3.org/ns/ws-policy" xmlns:wsam="http://www.w3.org/2007/05/addressing/metadata" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns="http://schemas.xmlsoap.org/wsdl/" name="NfeDownloadNF" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF">
  <types>
<xsd:schema xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:wsp1_2="http://schemas.xmlsoap.org/ws/2004/09/policy" xmlns:wsp="http://www.w3.org/ns/ws-policy" xmlns:wsam="http://www.w3.org/2007/05/addressing/metadata" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns="http://schemas.xmlsoap.org/wsdl/" elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF">
				<xsd:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
				<xsd:element name="nfeDownloadNFResult">
					<xsd:complexType mixed="true">
						<xsd:sequence>
							<xsd:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
						</xsd:sequence>
					</xsd:complexType>
				</xsd:element>
				<xsd:element name="nfeDadosMsg">
					<xsd:complexType mixed="true">
						<xsd:sequence>
							<xsd:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
						</xsd:sequence>
					</xsd:complexType>
				</xsd:element>
				<xsd:complexType name="nfeCabecMsg">
					<xsd:sequence>
						<xsd:element minOccurs="0" name="cUF" type="xsd:string"/>
						<xsd:element minOccurs="0" name="versaoDados" type="xsd:string"/>
					</xsd:sequence>
					<xsd:anyAttribute namespace="##other" processContents="skip"/>
				</xsd:complexType>
			</xsd:schema>
  </types>
  <message name="nfeDownloadNFResponse">
    <part element="tns:nfeDownloadNFResult" name="nfeDownloadNFResult">
    </part>
    <part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </part>
  </message>
  <message name="nfeDownloadNF">
    <part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </part>
    <part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </part>
  </message>
  <portType name="NfeDownloadNFPort">
    <operation name="nfeDownloadNF" parameterOrder="nfeCabecMsg nfeDadosMsg">
      <input message="tns:nfeDownloadNF" wsam:Action="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF/nfeDownloadNF">
    </input>
      <output message="tns:nfeDownloadNFResponse" wsam:Action="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF/NfeDownloadNFPort/nfeDownloadNFResponse">
    </output>
    </operation>
  </portType>
  <binding name="NfeDownloadNFBinding" type="tns:NfeDownloadNFPort">
    <soap12:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
    <operation name="nfeDownloadNF">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeDownloadNF/nfeDownloadNF"/>
      <input>
        <soap12:body parts="nfeDadosMsg" use="literal"/>
        <soap12:header message="tns:nfeDownloadNF" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </input>
      <output>
        <soap12:body parts="nfeDownloadNFResult" use="literal"/>
        <soap12:header message="tns:nfeDownloadNFResponse" part="nfeCabecMsg" use="literal">
        </soap12:header>
      </output>
    </operation>
  </binding>
  <service name="NfeDownloadNF">
    <port binding="tns:NfeDownloadNFBinding" name="NfeDownloadNF">
      <soap12:address location="https://nfe.sefaz.ce.gov.br/nfe2/services/NfeDownloadNF"/>
    </port>
  </service>
</definitions>