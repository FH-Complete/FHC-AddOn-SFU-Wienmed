<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet 
    xmlns:fo="http://www.w3.org/1999/XSL/Format" 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"
    xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" 
    xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" 
    xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" 
    xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" 
    xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0"
>
    <xsl:output method="xml" version="1.0" indent="yes"/>
    <xsl:template match="zertifikate">
        <office:document-content xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:rpt="http://openoffice.org/2005/report" xmlns:of="urn:oasis:names:tc:opendocument:xmlns:of:1.2" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:grddl="http://www.w3.org/2003/g/data-view#" xmlns:officeooo="http://openoffice.org/2009/office" xmlns:tableooo="http://openoffice.org/2009/table" xmlns:drawooo="http://openoffice.org/2010/draw" xmlns:calcext="urn:org:documentfoundation:names:experimental:calc:xmlns:calcext:1.0" xmlns:loext="urn:org:documentfoundation:names:experimental:office:xmlns:loext:1.0" xmlns:field="urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0" xmlns:formx="urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0" xmlns:css3t="http://www.w3.org/TR/css3-text/" office:version="1.2">
            <office:scripts/>
            <office:font-face-decls>
                <style:font-face style:name="Mangal1" svg:font-family="Mangal"/>
                <style:font-face style:name="Arial" svg:font-family="Arial" style:font-family-generic="roman" style:font-pitch="variable"/>
                <style:font-face style:name="Tahoma" svg:font-family="Tahoma" style:font-family-generic="roman" style:font-pitch="variable"/>
                <style:font-face style:name="Times New Roman" svg:font-family="'Times New Roman'" style:font-family-generic="roman" style:font-pitch="variable"/>
                <style:font-face style:name="Liberation Sans" svg:font-family="'Liberation Sans'" style:font-family-generic="swiss" style:font-pitch="variable"/>
                <style:font-face style:name="Arial1" svg:font-family="Arial" style:font-family-generic="system" style:font-pitch="variable"/>
                <style:font-face style:name="Mangal" svg:font-family="Mangal" style:font-family-generic="system" style:font-pitch="variable"/>
                <style:font-face style:name="Microsoft YaHei" svg:font-family="'Microsoft YaHei'" style:font-family-generic="system" style:font-pitch="variable"/>
                <style:font-face style:name="Tahoma1" svg:font-family="Tahoma" style:font-family-generic="system" style:font-pitch="variable"/>
                <style:font-face style:name="Times New Roman1" svg:font-family="'Times New Roman'" style:font-family-generic="system" style:font-pitch="variable"/>
            </office:font-face-decls>
            <office:automatic-styles>
                <style:style style:name="Tabelle1" style:family="table">
                    <style:table-properties style:width="16.431cm" fo:margin-left="-0.199cm" fo:margin-top="0cm" fo:margin-bottom="0cm" table:align="left" style:writing-mode="lr-tb"/>
                </style:style>
                <style:style style:name="Tabelle1.A" style:family="table-column">
                    <style:table-column-properties style:column-width="8.705cm"/>
                </style:style>
                <style:style style:name="Tabelle1.B" style:family="table-column">
                    <style:table-column-properties style:column-width="3.485cm"/>
                </style:style>
                <style:style style:name="Tabelle1.C" style:family="table-column">
                    <style:table-column-properties style:column-width="4.24cm"/>
                </style:style>
                <style:style style:name="Tabelle1.1" style:family="table-row">
                    <style:table-row-properties style:min-row-height="1cm" fo:keep-together="auto"/>
                </style:style>
                <style:style style:name="Tabelle1.A1" style:family="table-cell">
                    <style:table-cell-properties fo:background-color="#ffffff" fo:padding-left="0.199cm" fo:padding-right="0.191cm" fo:padding-top="0cm" fo:padding-bottom="0cm" fo:border-left="0.5pt solid #00000a" fo:border-right="none" fo:border-top="0.5pt solid #00000a" fo:border-bottom="0.5pt solid #00000a">
                        <style:background-image/>
                    </style:table-cell-properties>
                </style:style>
                <style:style style:name="Tabelle1.B1" style:family="table-cell">
                    <style:table-cell-properties fo:background-color="#ffffff" fo:padding-left="0.199cm" fo:padding-right="0.191cm" fo:padding-top="0cm" fo:padding-bottom="0cm" fo:border="0.5pt solid #00000a">
                        <style:background-image/>
                    </style:table-cell-properties>
                </style:style>
                <style:style style:name="Tabelle1.2" style:family="table-row">
                    <style:table-row-properties style:min-row-height="0.501cm" fo:keep-together="auto"/>
                </style:style>
                <style:style style:name="Tabelle4" style:family="table">
                    <style:table-properties style:width="16.443cm" fo:margin-left="-0.199cm" fo:margin-top="0cm" fo:margin-bottom="0cm" table:align="left" style:writing-mode="lr-tb"/>
                </style:style>
                <style:style style:name="Tabelle4.A" style:family="table-column">
                    <style:table-column-properties style:column-width="3.69cm"/>
                </style:style>
                <style:style style:name="Tabelle4.B" style:family="table-column">
                    <style:table-column-properties style:column-width="12.753cm"/>
                </style:style>
                <style:style style:name="Tabelle4.1" style:family="table-row">
                    <style:table-row-properties style:row-height="0.8cm" fo:keep-together="auto"/>
                </style:style>
                <style:style style:name="Tabelle4.A1" style:family="table-cell">
                    <style:table-cell-properties fo:padding-left="0.199cm" fo:padding-right="0.191cm" fo:padding-top="0cm" fo:padding-bottom="0cm" fo:border="0.5pt solid #00000a"/>
                </style:style>
                <style:style style:name="Tabelle2" style:family="table">
                    <style:table-properties style:width="16.4cm" fo:margin-left="-0.173cm" table:align="left" style:writing-mode="lr-tb"/>
                </style:style>
                <style:style style:name="Tabelle2.A" style:family="table-column">
                    <style:table-column-properties style:column-width="1.2cm"/>
                </style:style>
                <style:style style:name="Tabelle2.B" style:family="table-column">
                    <style:table-column-properties style:column-width="7.1cm"/>
                </style:style>
                <style:style style:name="Tabelle2.C" style:family="table-column">
                    <style:table-column-properties style:column-width="2.1cm"/>
                </style:style>
                <style:style style:name="Tabelle2.D" style:family="table-column">
                    <style:table-column-properties style:column-width="2.1cm"/>
                </style:style>
                <style:style style:name="Tabelle2.E" style:family="table-column">
                    <style:table-column-properties style:column-width="1.3cm"/>
                </style:style>
                <style:style style:name="Tabelle2.F" style:family="table-column">
                    <style:table-column-properties style:column-width="1.3cm"/>
                </style:style>
                <style:style style:name="Tabelle2.G" style:family="table-column">
                    <style:table-column-properties style:column-width="1.3cm"/>
                </style:style>
                <style:style style:name="Tabelle2.A1" style:family="table-cell">
                    <style:table-cell-properties fo:background-color="#cccccc" fo:padding="0.097cm" fo:border-left="0.05pt solid #000000" fo:border-right="none" fo:border-top="0.05pt solid #000000" fo:border-bottom="0.05pt solid #000000">
                        <style:background-image/>
                    </style:table-cell-properties>
                </style:style>
                <style:style style:name="Tabelle2.A11" style:family="table-cell">
                    <style:table-cell-properties fo:background-color="#cccccc" fo:padding="0.097cm" fo:border-left="0.05pt solid #000000" fo:border-right="0.05pt solid #000000" fo:border-top="0.05pt solid #000000" fo:border-bottom="0.05pt solid #000000">
                        <style:background-image/>
                    </style:table-cell-properties>
                </style:style>
                <style:style style:name="Tabelle2.F1" style:family="table-cell">
                    <style:table-cell-properties fo:background-color="#cccccc" fo:padding="0.097cm" fo:border="0.05pt solid #000000">
                        <style:background-image/>
                    </style:table-cell-properties>
                </style:style>
                <style:style style:name="Tabelle2.A2" style:family="table-cell">
                    <style:table-cell-properties fo:padding="0.097cm" fo:border-left="0.05pt solid #000000" fo:border-right="none" fo:border-top="none" fo:border-bottom="0.05pt solid #000000"/>
                </style:style>
                <style:style style:name="Tabelle2.B2" style:family="table-cell">
                    <style:table-cell-properties fo:padding="0.097cm" fo:border-left="0.05pt solid #000000" fo:border-right="none" fo:border-top="none" fo:border-bottom="0.05pt solid #000000"/>
                </style:style>
                <style:style style:name="Tabelle2.C2" style:family="table-cell">
                    <style:table-cell-properties fo:padding="0.097cm" fo:border-left="0.05pt solid #000000" fo:border-right="none" fo:border-top="none" fo:border-bottom="0.05pt solid #000000"/>
                </style:style>
                <style:style style:name="Tabelle2.D2" style:family="table-cell">
                    <style:table-cell-properties fo:padding="0.097cm" fo:border-left="0.05pt solid #000000" fo:border-right="none" fo:border-top="none" fo:border-bottom="0.05pt solid #000000"/>
                </style:style>
                <style:style style:name="Tabelle2.E2" style:family="table-cell">
                    <style:table-cell-properties fo:padding="0.097cm" fo:border-left="0.05pt solid #000000" fo:border-right="none" fo:border-top="none" fo:border-bottom="0.05pt solid #000000"/>
                </style:style>
                <style:style style:name="Tabelle2.F2" style:family="table-cell">
                    <style:table-cell-properties fo:padding="0.097cm" fo:border-left="0.05pt solid #000000" fo:border-right="0.05pt solid #000000" fo:border-top="none" fo:border-bottom="0.05pt solid #000000"/>
                </style:style>
                <style:style style:name="P1" style:family="paragraph" style:parent-style-name="Header">
                    <style:text-properties officeooo:rsid="0003921e" officeooo:paragraph-rsid="0003921e"/>
                </style:style>
                <style:style style:name="P2" style:family="paragraph" style:parent-style-name="Header">
                    <style:text-properties fo:font-size="8pt" style:font-size-asian="8pt" style:font-size-complex="8pt"/>
                </style:style>
                <style:style style:name="P3" style:family="paragraph" style:parent-style-name="Footer">
                    <style:paragraph-properties>
                        <style:tab-stops>
                            <style:tab-stop style:position="8.001cm" style:type="center"/>
                            <style:tab-stop style:position="15.998cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="8pt" style:font-size-asian="8pt" style:font-size-complex="8pt"/>
                </style:style>
                <style:style style:name="P4" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties>
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="11pt" style:font-size-asian="11pt" style:font-size-complex="11pt"/>
                </style:style>
                <style:style style:name="P5" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties>
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                </style:style>
                <style:style style:name="P6" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties fo:text-align="end" style:justify-single-word="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                </style:style>
                <style:style style:name="P7" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties fo:margin-top="0.106cm" fo:margin-bottom="0cm" style:contextual-spacing="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="11pt" style:font-size-asian="11pt" style:font-size-complex="11pt"/>
                </style:style>
                <style:style style:name="P8" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties fo:margin-top="0.106cm" fo:margin-bottom="0cm" style:contextual-spacing="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="9pt" officeooo:rsid="000428ea" officeooo:paragraph-rsid="000428ea" style:font-size-asian="9pt" style:font-size-complex="9pt"/>
                </style:style>
                <style:style style:name="P9" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties fo:margin-top="0.106cm" fo:margin-bottom="0cm" style:contextual-spacing="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="9pt" officeooo:rsid="00094b9a" officeooo:paragraph-rsid="00094b9a" style:font-size-asian="9pt" style:font-size-complex="9pt"/>
                </style:style>
                <style:style style:name="P10" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties fo:margin-top="0.106cm" fo:margin-bottom="0cm" style:contextual-spacing="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="9pt" officeooo:rsid="000d4b5b" officeooo:paragraph-rsid="000d4b5b" style:font-size-asian="9pt" style:font-size-complex="9pt"/>
                </style:style>
                <style:style style:name="P11" style:family="paragraph" style:parent-style-name="Header">
                    <style:paragraph-properties fo:margin-top="0.494cm" fo:margin-bottom="0.423cm" style:contextual-spacing="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="7.502cm"/>
                            <style:tab-stop style:position="9.502cm"/>
                            <style:tab-stop style:position="14.753cm"/>
                            <style:tab-stop style:position="15.503cm"/>
                            <style:tab-stop style:position="16.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="11pt" style:font-size-asian="11pt" style:font-size-complex="11pt"/>
                </style:style>
                <style:style style:name="P12" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties fo:text-align="end" style:justify-single-word="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="9pt" officeooo:rsid="000e2cec" officeooo:paragraph-rsid="000e2cec" style:font-size-asian="9pt" style:font-size-complex="9pt"/>
                </style:style>
                <style:style style:name="P13" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties fo:margin-top="0.106cm" fo:margin-bottom="0cm" style:contextual-spacing="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties style:font-name="Arial" fo:font-size="11pt" fo:font-weight="bold" officeooo:rsid="000d4b5b" officeooo:paragraph-rsid="000d4b5b" style:font-size-asian="11pt" style:language-asian="de" style:country-asian="DE" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="11pt" style:font-weight-complex="bold"/>
                </style:style>
                <style:style style:name="P14" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties fo:margin-top="0.106cm" fo:margin-bottom="0cm" style:contextual-spacing="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties style:font-name="Arial" fo:font-size="11pt" fo:font-weight="bold" officeooo:rsid="00094b9a" officeooo:paragraph-rsid="000d4b5b" style:font-size-asian="11pt" style:language-asian="de" style:country-asian="DE" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="11pt" style:font-weight-complex="bold"/>
                </style:style>
                <style:style style:name="P15" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties fo:margin-top="0.106cm" fo:margin-bottom="0cm" style:contextual-spacing="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="11pt" fo:font-weight="bold" officeooo:rsid="00026b08" officeooo:paragraph-rsid="00026b08" style:font-size-asian="11pt" style:font-weight-asian="bold" style:font-size-complex="11pt" style:font-weight-complex="bold"/>
                </style:style>
                <style:style style:name="P16" style:family="paragraph" style:parent-style-name="Standard">
                    <style:paragraph-properties fo:margin-top="0.106cm" fo:margin-bottom="0cm" style:contextual-spacing="false">
                        <style:tab-stops>
                            <style:tab-stop style:position="6.752cm"/>
                            <style:tab-stop style:position="13.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="11pt" style:font-size-asian="11pt" style:font-size-complex="11pt"/>
                </style:style>
                <style:style style:name="P17" style:family="paragraph" style:parent-style-name="Header" style:master-page-name="Standard">
                    <style:paragraph-properties fo:margin-top="0.423cm" fo:margin-bottom="0.423cm" style:contextual-spacing="false" style:page-number="auto">
                        <style:tab-stops>
                            <style:tab-stop style:position="7.502cm"/>
                            <style:tab-stop style:position="9.502cm"/>
                            <style:tab-stop style:position="14.753cm"/>
                            <style:tab-stop style:position="15.503cm"/>
                            <style:tab-stop style:position="16.002cm" style:type="right"/>
                        </style:tab-stops>
                    </style:paragraph-properties>
                    <style:text-properties fo:font-size="14pt" style:font-size-asian="14pt" style:font-size-complex="14pt"/>
                </style:style>
                <style:style style:name="P18" style:family="paragraph" style:parent-style-name="Table_20_Contents">
                    <style:text-properties style:font-name="Arial" fo:font-size="9pt" officeooo:rsid="00121e89" officeooo:paragraph-rsid="00121e89" style:font-size-asian="9pt" style:language-asian="de" style:country-asian="DE" style:font-name-complex="Arial1" style:font-size-complex="9pt"/>
                </style:style>
                <style:style style:name="P18.1" style:family="paragraph" style:parent-style-name="Table_20_Contents">
                    <style:paragraph-properties fo:text-align="center" style:justify-single-word="false"/>
                    <style:text-properties style:font-name="Arial" fo:font-size="9pt" officeooo:rsid="00121e89" officeooo:paragraph-rsid="00121e89" style:font-size-asian="9pt" style:language-asian="de" style:country-asian="DE" style:font-name-complex="Arial1" style:font-size-complex="9pt"/>
                </style:style>
                <style:style style:name="P19" style:family="paragraph" style:parent-style-name="Table_20_Contents">
                    <style:text-properties style:font-name="Arial" fo:font-size="9pt" fo:font-weight="bold" officeooo:rsid="0010dfec" officeooo:paragraph-rsid="0010dfec" fo:background-color="transparent" style:font-size-asian="9pt" style:language-asian="de" style:country-asian="DE" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="9pt" style:font-weight-complex="bold"/>
                </style:style>
                <style:style style:name="T1" style:family="text">
                    <style:text-properties officeooo:rsid="0003921e"/>
                </style:style>
                <style:style style:name="T2" style:family="text">
                    <style:text-properties fo:font-size="9pt" style:font-size-asian="9pt" style:font-size-complex="9pt"/>
                </style:style>
                <style:style style:name="T3" style:family="text">
                    <style:text-properties fo:font-size="9pt" officeooo:rsid="00026b08" style:font-size-asian="9pt" style:font-size-complex="9pt"/>
                </style:style>
                <style:style style:name="T4" style:family="text">
                    <style:text-properties officeooo:rsid="00094b9a"/>
                </style:style>
                <style:style style:name="T5" style:family="text">
                    <style:text-properties officeooo:rsid="000bcc78"/>
                </style:style>
                <style:style style:name="T6" style:family="text">
                    <style:text-properties officeooo:rsid="000d4b5b"/>
                </style:style>
                <style:style style:name="T7" style:family="text">
                    <style:text-properties officeooo:rsid="001380c9"/>
                </style:style>
                <style:style style:name="fr1" style:family="graphic" style:parent-style-name="Graphics">
                    <style:graphic-properties style:vertical-pos="from-top" style:vertical-rel="paragraph" style:horizontal-pos="from-left" style:horizontal-rel="paragraph" style:mirror="none" fo:clip="rect(0cm, 0cm, 0cm, 0cm)" draw:luminance="0%" draw:contrast="0%" draw:red="0%" draw:green="0%" draw:blue="0%" draw:gamma="100%" draw:color-inversion="false" draw:image-opacity="100%" draw:color-mode="standard"/>
                </style:style>
            </office:automatic-styles>
            <office:body>
                <office:text>
                    <text:sequence-decls>
                        <text:sequence-decl text:display-outline-level="0" text:name="Illustration"/>
                        <text:sequence-decl text:display-outline-level="0" text:name="Table"/>
                        <text:sequence-decl text:display-outline-level="0" text:name="Text"/>
                        <text:sequence-decl text:display-outline-level="0" text:name="Drawing"/>
                    </text:sequence-decls>
                    <text:p text:style-name="P17">Lehrveranstaltungszeugnis</text:p>
                                        
                    <xsl:apply-templates select="zertifikat"/>
                
                    <text:p text:style-name="P6"/>
                    <text:p text:style-name="P5">
                        <text:span text:style-name="T2">Beurteilung: Sehr gut (1), Gut (2), Befriedigend (3), Genügend (4), Nicht genügend (5), anerkannt (anerk), erfolgreich absolviert (ea)<text:line-break/>1 SWS = 15 Unterrichtseinheiten (UE)<text:line-break/>ECTS = European Credit Transfer System</text:span>
                    </text:p>
                </office:text>
            </office:body>
        </office:document-content>
    </xsl:template>

    <xsl:template match="zertifikat">
        <text:p text:style-name="P7">
            <xsl:value-of select="finanzamt" />
        </text:p>
        <text:p text:style-name="P7"/>
        <table:table table:name="Tabelle1" table:style-name="Tabelle1">
            <table:table-column table:style-name="Tabelle1.A"/>
            <table:table-column table:style-name="Tabelle1.B"/>
            <table:table-column table:style-name="Tabelle1.C"/>
            <table:table-row table:style-name="Tabelle1.1">
                <table:table-cell table:style-name="Tabelle1.A1" office:value-type="string">
                    <text:p text:style-name="P9">Name</text:p>
                    <text:p text:style-name="P15">
                        <text:span text:style-name="T4">
                            <xsl:value-of select="name" />
                        </text:span>
                    </text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle1.B1" office:value-type="string">
                    <text:p text:style-name="P10">Geburtsdatum</text:p>
                    <text:p text:style-name="P13">
                        <xsl:value-of select="gebdatum" />
                    </text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle1.B1" office:value-type="string">
                    <text:p text:style-name="P8">Matrikelnummer</text:p>
                    <text:p text:style-name="P15">
                        <xsl:value-of select="matrikelnr" />
                    </text:p>
                </table:table-cell>
            </table:table-row>
            <table:table-row table:style-name="Tabelle1.2">
                <table:table-cell table:style-name="Tabelle1.A1" table:number-columns-spanned="2" office:value-type="string">
                    <text:p text:style-name="P10">Studienrichtung und Studiengang</text:p>
                    <text:p text:style-name="P15">
                        <text:span text:style-name="T6">
                            <xsl:value-of select="lv_studiengang_typ " />
                            <xsl:text> </xsl:text>
                            <xsl:value-of select="lv_studiengang_bezeichnung" />
                        </text:span>
                    </text:p>
                </table:table-cell>
                <table:covered-table-cell/>
                <table:table-cell table:style-name="Tabelle1.B1" office:value-type="string">
                    <text:p text:style-name="P10">
                        <text:span text:style-name="T7">Studienkennzahl</text:span>
                    </text:p>
                    <text:p text:style-name="P14">
                        <xsl:value-of select="lv_studiengang_kennzahl" />
                    </text:p>
                </table:table-cell>
            </table:table-row>
        </table:table>
        
        <text:p text:style-name="P7"/>
        
        <table:table table:name="Tabelle2" table:style-name="Tabelle2">
            <table:table-column table:style-name="Tabelle2.A"/>
            <table:table-column table:style-name="Tabelle2.B"/>
            <table:table-column table:style-name="Tabelle2.G"/>
            <table:table-column table:style-name="Tabelle2.C"/>
            <table:table-column table:style-name="Tabelle2.D"/>
            <table:table-column table:style-name="Tabelle2.E"/>
            <table:table-column table:style-name="Tabelle2.F"/>
            <!--
            <table:table-row>
                <table:table-cell table:style-name="Tabelle2.A11" table:number-columns-spanned="6" office:value-type="string">
                    <text:p text:style-name="P19">
                        <xsl:value-of select="studiensemester_kurzbz" />
                    </text:p>
                </table:table-cell>
            </table:table-row>
            -->
            <table:table-row>
                <table:table-cell table:style-name="Tabelle2.A1" office:value-type="string">
                    <text:p text:style-name="P19">Sem.</text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.A1" office:value-type="string">
                    <text:p text:style-name="P19">Lehrveranstaltung</text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.A1" office:value-type="string">
                    <text:p text:style-name="P19">LV-Typ</text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.A1" office:value-type="string">
                    <text:p text:style-name="P19">Datum</text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.A1" office:value-type="string">
                    <text:p text:style-name="P19">Beurteilung</text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.A1" office:value-type="string">
                    <text:p text:style-name="P19">SWS</text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.F1" office:value-type="string">
                    <text:p text:style-name="P19">ECTS</text:p>
                </table:table-cell>
            </table:table-row>
            <table:table-row>
                <table:table-cell table:style-name="Tabelle2.A2" office:value-type="string">
                    <text:p text:style-name="P18">
                        <xsl:value-of select="studplansem" />
                    </text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.B2" office:value-type="string">
                    <text:p text:style-name="P18">
                        <xsl:value-of select="bezeichnung" />
                    </text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.B2" office:value-type="string">
                    <text:p text:style-name="P18.1">
                        <xsl:value-of select="lehrform" />
                    </text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.C2" office:value-type="string">
                    <text:p text:style-name="P18.1">
                        <xsl:value-of select="benotungsdatum" />
                    </text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.D2" office:value-type="string">
                    <text:p text:style-name="P18.1">
                        <xsl:value-of select="note" />
                    </text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.E2" office:value-type="string">
                    <text:p text:style-name="P18.1">
                        <xsl:value-of select="sws" />
                    </text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle2.F2" office:value-type="string">
                    <text:p text:style-name="P18.1">
                        <xsl:value-of select="ects" />
                    </text:p>
                </table:table-cell>
            </table:table-row>
        </table:table>
        <text:p text:style-name="P4"/>
        <text:p text:style-name="P4"/>
        <table:table table:name="Tabelle4" table:style-name="Tabelle4">
            <table:table-column table:style-name="Tabelle4.A"/>
            <table:table-column table:style-name="Tabelle4.B"/>
            <table:table-row table:style-name="Tabelle4.1">
                <table:table-cell table:style-name="Tabelle4.A1" office:value-type="string">
                    <text:p text:style-name="P5">
                        <text:span text:style-name="T2">Datum: </text:span>
                        <text:span text:style-name="T3">
                            <xsl:value-of select="datum" />
                        </text:span>
                    </text:p>
                </table:table-cell>
                <table:table-cell table:style-name="Tabelle4.A1" office:value-type="string">
                    <text:p text:style-name="P12">Vizerektor für Lehre: MMag. Dr. Stefan Hampl</text:p>
                    <text:p text:style-name="P12">Gilt auch ohne Unterschrift und Stempel</text:p>
                </table:table-cell>
            </table:table-row>
        </table:table>
    </xsl:template>
</xsl:stylesheet>