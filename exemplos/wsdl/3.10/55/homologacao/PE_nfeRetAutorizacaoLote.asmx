<?xml version="1.0" encoding="UTF-8"?>
HTTP/1.1 200 OK
Date: Tue, 21 Oct 2014 18:46:26 GMT
Server: IBM_HTTP_Server
X-Powered-By: Servlet/3.0
Connection: close
Transfer-Encoding: chunked
Content-Type: text/xml; charset=utf-8
Content-Language: en-US

<?xml version="1.0" encoding="UTF-8"?>
<definitions targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao" xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">

     <types>

          <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao">

               <s:element name="nfeDadosMsg">

                    <s:complexType mixed="true">

                         <s:sequence>

                              <s:any/>

                         </s:sequence>

                    </s:complexType>

               </s:element>

               <s:element name="nfeRetAutorizacaoLoteResult">

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

     <message name="nfeRetAutorizacaoLoteSoap12In">

          <part element="tns:nfeDadosMsg" name="nfeDadosMsg"/>

     </message>

     <message name="nfeRetAutorizacaoLoteSoap12Out">

          <part element="tns:nfeRetAutorizacaoLoteResult" name="nfeRetAutorizacaoLoteResult"/>

     </message>

     <message name="nfeRetAutorizacaoLotenfeCabecMsg">

          <part element="tns:nfeCabecMsg" name="nfeCabecMsg"/>

     </message>

     <portType name="NfeRetAutorizacaoSoap12">

          <operation name="nfeRetAutorizacaoLote">

               <input message="tns:nfeRetAutorizacaoLoteSoap12In"/>

               <output message="tns:nfeRetAutorizacaoLoteSoap12Out"/>

          </operation>

     </portType>

     <binding name="NfeRetAutorizacaoSoap12" type="tns:NfeRetAutorizacaoSoap12">

          <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>

          <operation name="nfeRetAutorizacaoLote">

               <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetAutorizacao/nfeRetAutorizacaoLote" soapActionRequired="false" style="document"/>

               <input>

                    <soap12:body use="literal"/>

                    <soap12:header message="tns:nfeRetAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal"/>

               </input>

               <output>

                    <soap12:body use="literal"/>

                    <soap12:header message="tns:nfeRetAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal"/>

               </output>

          </operation>

     </binding>

     <service name="NfeRetAutorizacao">

          <port binding="tns:NfeRetAutorizacaoSoap12" name="NfeRetAutorizacaoSoap12">

               <soap12:address location="https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/NfeRetAutorizacao"/>

          </port>

     </service>

</definitions>