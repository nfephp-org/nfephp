<?xml version="1.0" encoding="UTF-8"?>
HTTP/1.1 200 OK
Date: Tue, 21 Oct 2014 18:46:24 GMT
Server: IBM_HTTP_Server
X-Powered-By: Servlet/3.0
Connection: close
Transfer-Encoding: chunked
Content-Type: text/xml; charset=utf-8
Content-Language: en-US

<?xml version="1.0" encoding="UTF-8"?>
<definitions targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao" xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">

     <types>

          <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao">

               <s:element name="nfeDadosMsg">

                    <s:complexType mixed="true">

                         <s:sequence>

                              <s:any/>

                         </s:sequence>

                    </s:complexType>

               </s:element>

               <s:element name="nfeAutorizacaoLoteResult">

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

               <s:element name="nfeDadosMsgZip" type="s:string"/>

               <s:element name="nfeAutorizacaoLoteZipResult">

                    <s:complexType mixed="true">

                         <s:sequence>

                              <s:any/>

                         </s:sequence>

                    </s:complexType>

               </s:element>

          </s:schema>

     </types>

     <message name="nfeAutorizacaoLoteSoap12In">

          <part element="tns:nfeDadosMsg" name="nfeDadosMsg"/>

     </message>

     <message name="nfeAutorizacaoLoteSoap12Out">

          <part element="tns:nfeAutorizacaoLoteResult" name="nfeAutorizacaoLoteResult"/>

     </message>

     <message name="nfeAutorizacaoLoteZipSoap12In">

          <part element="tns:nfeDadosMsgZip" name="nfeDadosMsgZip"/>

     </message>

     <message name="nfeAutorizacaoLoteZipSoap12Out">

          <part element="tns:nfeAutorizacaoLoteZipResult" name="nfeAutorizacaoLoteZipResult"/>

     </message>

     <message name="nfeAutorizacaoLotenfeCabecMsg">

          <part element="tns:nfeCabecMsg" name="nfeCabecMsg"/>

     </message>

     <message name="nfeAutorizacaoLoteZipnfeCabecMsg">

          <part element="tns:nfeCabecMsg" name="nfeCabecMsg"/>

     </message>

     <portType name="NfeAutorizacaoSoap12">

          <operation name="nfeAutorizacaoLote">

               <input message="tns:nfeAutorizacaoLoteSoap12In"/>

               <output message="tns:nfeAutorizacaoLoteSoap12Out"/>

          </operation>

          <operation name="nfeAutorizacaoLoteZip">

               <input message="tns:nfeAutorizacaoLoteZipSoap12In"/>

               <output message="tns:nfeAutorizacaoLoteZipSoap12Out"/>

          </operation>

     </portType>

     <binding name="NfeAutorizacaoSoap12" type="tns:NfeAutorizacaoSoap12">

          <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>

          <operation name="nfeAutorizacaoLote">

               <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/nfeAutorizacaoLote" soapActionRequired="false" style="document"/>

               <input>

                    <soap12:body use="literal"/>

                    <soap12:header message="tns:nfeAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal"/>

               </input>

               <output>

                    <soap12:body use="literal"/>

                    <soap12:header message="tns:nfeAutorizacaoLotenfeCabecMsg" part="nfeCabecMsg" use="literal"/>

               </output>

          </operation>

          <operation name="nfeAutorizacaoLoteZip">

               <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeAutorizacao/nfeAutorizacaoLoteZip" soapActionRequired="false" style="document"/>

               <input>

                    <soap12:body use="literal"/>

                    <soap12:header message="tns:nfeAutorizacaoLoteZipnfeCabecMsg" part="nfeCabecMsg" use="literal"/>

               </input>

               <output>

                    <soap12:body use="literal"/>

                    <soap12:header message="tns:nfeAutorizacaoLoteZipnfeCabecMsg" part="nfeCabecMsg" use="literal"/>

               </output>

          </operation>

     </binding>

     <service name="NfeAutorizacao">

          <port binding="tns:NfeAutorizacaoSoap12" name="NfeAutorizacaoSoap12">

               <soap12:address location="https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/NfeAutorizacao"/>

          </port>

     </service>

</definitions>