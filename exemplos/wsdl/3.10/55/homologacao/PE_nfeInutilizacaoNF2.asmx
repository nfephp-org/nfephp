<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">

   <wsdl:types>

      <s:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2">

         <s:element name="nfeDadosMsg">

            <s:complexType mixed="true">

               <s:sequence>

                  <s:any/>

               </s:sequence>

            </s:complexType>

         </s:element>

         <s:element name="nfeInutilizacaoNF2Result">

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

   <wsdl:message name="nfeInutilizacaoNF2Soap12In">

      <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg"/>

   </wsdl:message>

   <wsdl:message name="nfeInutilizacaoNF2Soap12Out">

      <wsdl:part element="tns:nfeInutilizacaoNF2Result" name="nfeInutilizacaoNF2Result"/>

   </wsdl:message>

   <wsdl:message name="nfeInutilizacaoNF2nfeCabecMsg">

      <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg"/>

   </wsdl:message>

   <wsdl:portType name="NfeInutilizacao2Soap12">

      <wsdl:operation name="nfeInutilizacaoNF2">

         <wsdl:input message="tns:nfeInutilizacaoNF2Soap12In"/>

         <wsdl:output message="tns:nfeInutilizacaoNF2Soap12Out"/>

      </wsdl:operation>

   </wsdl:portType>

   <wsdl:binding name="NfeInutilizacao2Soap12" type="tns:NfeInutilizacao2Soap12">

      <soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>

      <wsdl:operation name="nfeInutilizacaoNF2">

         <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2/nfeInutilizacaoNF2" style="document"/>

         <wsdl:input>

            <soap12:body use="literal"/>

            <soap12:header message="tns:nfeInutilizacaoNF2nfeCabecMsg" part="nfeCabecMsg" use="literal"/>

         </wsdl:input>

         <wsdl:output>

            <soap12:body use="literal"/>

            <soap12:header message="tns:nfeInutilizacaoNF2nfeCabecMsg" part="nfeCabecMsg" use="literal"/>

         </wsdl:output>

      </wsdl:operation>

   </wsdl:binding>

   <wsdl:service name="NfeInutilizacao2">

      <wsdl:port binding="tns:NfeInutilizacao2Soap12" name="NfeInutilizacao2">

         <soap12:address location="https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/NfeInutilizacao2"/>

      </wsdl:port>

   </wsdl:service>

</wsdl:definitions>