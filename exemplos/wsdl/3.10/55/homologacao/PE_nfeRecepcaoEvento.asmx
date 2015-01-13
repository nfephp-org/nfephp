<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">

   <wsdl:types>

      <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento">

         <s:element name="nfeDadosMsg">

            <s:complexType mixed="true">

               <s:sequence>

                  <s:any/>

               </s:sequence>

            </s:complexType>

         </s:element>

         <s:element name="nfeRecepcaoEventoResult">

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

   <wsdl:message name="nfeRecepcaoEventoSoap12In">

      <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg"/>

   </wsdl:message>

   <wsdl:message name="nfeRecepcaoEventoSoap12Out">

      <wsdl:part element="tns:nfeRecepcaoEventoResult" name="nfeRecepcaoEventoResult"/>

   </wsdl:message>

   <wsdl:message name="nfeRecepcaoEventonfeCabecMsg">

      <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg"/>

   </wsdl:message>

   <wsdl:portType name="RecepcaoEventoSoap12">

      <wsdl:operation name="nfeRecepcaoEvento">

         <wsdl:input message="tns:nfeRecepcaoEventoSoap12In"/>

         <wsdl:output message="tns:nfeRecepcaoEventoSoap12Out"/>

      </wsdl:operation>

   </wsdl:portType>

   <wsdl:binding name="RecepcaoEventoSoap12" type="tns:RecepcaoEventoSoap12">

      <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>

      <wsdl:operation name="nfeRecepcaoEvento">

         <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento/nfeRecepcaoEvento" style="document"/>

         <wsdl:input>

            <soap12:body use="literal"/>

            <soap12:header message="tns:nfeRecepcaoEventonfeCabecMsg" part="nfeCabecMsg" use="literal"/>

         </wsdl:input>

         <wsdl:output>

            <soap12:body use="literal"/>

            <soap12:header message="tns:nfeRecepcaoEventonfeCabecMsg" part="nfeCabecMsg" use="literal"/>

         </wsdl:output>

      </wsdl:operation>

   </wsdl:binding>

   <wsdl:service name="RecepcaoEvento">

      <wsdl:port binding="tns:RecepcaoEventoSoap12" name="RecepcaoEvento">

         <soap12:address location="https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/RecepcaoEvento"/>

      </wsdl:port>

   </wsdl:service>

</wsdl:definitions>