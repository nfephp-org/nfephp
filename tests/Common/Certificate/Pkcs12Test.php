<?php

/**
 * Class Pkcs12Test
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */
use NFePHP\Common\Certificate\Asn;
use NFePHP\Common\Certificate\Pkcs12;

class Pkcs12Test extends PHPUnit_Framework_TestCase
{
    protected $pubPem = '-----BEGIN CERTIFICATE-----
MIIEqzCCA5OgAwIBAgIDMTg4MA0GCSqGSIb3DQEBBQUAMIGSMQswCQYDVQQGEwJC
UjELMAkGA1UECBMCUlMxFTATBgNVBAcTDFBvcnRvIEFsZWdyZTEdMBsGA1UEChMU
VGVzdGUgUHJvamV0byBORmUgUlMxHTAbBgNVBAsTFFRlc3RlIFByb2pldG8gTkZl
IFJTMSEwHwYDVQQDExhORmUgLSBBQyBJbnRlcm1lZGlhcmlhIDEwHhcNMDkwNTIy
MTcwNzAzWhcNMTAxMDAyMTcwNzAzWjCBnjELMAkGA1UECBMCUlMxHTAbBgNVBAsT
FFRlc3RlIFByb2pldG8gTkZlIFJTMR0wGwYDVQQKExRUZXN0ZSBQcm9qZXRvIE5G
ZSBSUzEVMBMGA1UEBxMMUE9SVE8gQUxFR1JFMQswCQYDVQQGEwJCUjEtMCsGA1UE
AxMkTkZlIC0gQXNzb2NpYWNhbyBORi1lOjk5OTk5MDkwOTEwMjcwMIGfMA0GCSqG
SIb3DQEBAQUAA4GNADCBiQKBgQCx1O/e1Q+xh+wCoxa4pr/5aEFt2dEX9iBJyYu/
2a78emtorZKbWeyK435SRTbHxHSjqe1sWtIhXBaFa2dHiukT1WJyoAcXwB1GtxjT
2VVESQGtRiujMa+opus6dufJJl7RslAjqN/ZPxcBXaezt0nHvnUB/uB1K8WT9G7E
S0V17wIDAQABo4IBfjCCAXowIgYDVR0jAQEABBgwFoAUPT5TqhNWAm+ZpcVsvB7m
alDBjEQwDwYDVR0TAQH/BAUwAwEBADAPBgNVHQ8BAf8EBQMDAOAAMAwGA1UdIAEB
AAQCMAAwgawGA1UdEQEBAASBoTCBnqA4BgVgTAEDBKAvBC0yMjA4MTk3Nzk5OTk5
OTk5OTk5MDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDCgEgYFYEwBAwKgCQQHREZU
LU5GZaAZBgVgTAEDA6AQBA45OTk5OTA5MDkxMDI3MKAXBgVgTAEDB6AOBAwwMDAw
MDAwMDAwMDCBGmRmdC1uZmVAcHJvY2VyZ3MucnMuZ292LmJyMCAGA1UdJQEB/wQW
MBQGCCsGAQUFBwMCBggrBgEFBQcDBDBTBgNVHR8BAQAESTBHMEWgQ6BBhj9odHRw
Oi8vbmZlY2VydGlmaWNhZG8uc2VmYXoucnMuZ292LmJyL0xDUi9BQ0ludGVybWVk
aWFyaWEzOC5jcmwwDQYJKoZIhvcNAQEFBQADggEBAJFytXuiS02eJO0iMQr/Hi+O
x7/vYiPewiDL7s5EwO8A9jKx9G2Baz0KEjcdaeZk9a2NzDEgX9zboPxhw0RkWahV
CP2xvRFWswDIa2WRUT/LHTEuTeKCJ0iF/um/kYM8PmWxPsDWzvsCCRp146lc0lz9
LGm5ruPVYPZ/7DAoimUk3bdCMW/rzkVYg7iitxHrhklxH7YWQHUwbcqPt7Jv0RJx
clc1MhQlV2eM2MO1iIlk8Eti86dRrJVoicR1bwc6/YDqDp4PFONTi1ddewRu6elG
S74AzCcNYRSVTINYiZLpBZO0uivrnTEnsFguVnNtWb9MAHGt3tkR0gAVs6S0fm8=
-----END CERTIFICATE-----
';
    
    protected $priPem = '-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALHU797VD7GH7AKj
Frimv/loQW3Z0Rf2IEnJi7/Zrvx6a2itkptZ7IrjflJFNsfEdKOp7Wxa0iFcFoVr
Z0eK6RPVYnKgBxfAHUa3GNPZVURJAa1GK6Mxr6im6zp258kmXtGyUCOo39k/FwFd
p7O3Sce+dQH+4HUrxZP0bsRLRXXvAgMBAAECgYABg5yfOxUtH8kkpJrW66SKzRZx
hv8+wvu3ZR3pfkL9J1WuyHuNExDuhc1XiftTbBrKIfJBj+xmGFCgxi9U7pvZab9q
er4XkvZA1PQVTFyRG6AO0fq1jrCpuz4ChMr55MFxKjAHoc/on3JmyzTaNLOHXGpf
W3urt0kQmNMaahFzMQJBANGte7H03kF2fO69NAWbtbVxiTEuvE9n+AcVocsCN3+H
b1wbVROmSZR4oUMM8RMTirpmaDexY4B4xkG1lADud/UCQQDZHmFLZn6+9csQbPFJ
PBgfalEGhdTO6PzTVioyF7hP+km7gfcHNpvj9IQRl1wURrnUd06aq061CPU0PL/P
ctvTAkEAjVhoYS9TwEdysrGC5yDvXlAaDriVouXQcl4nwiVNaj/PVwTp1iQrx9WF
yCBqRtTOmRc9vAVtsQY5h8Qy8GnRHQJBAKsb3y+uIhta2GMkiG/f9V7kyeBrHpDG
W2IumOiLew1EwlENFuLPbcIUFPVMJRwxtQg10nPgqBHScnRtn/jcm1MCQEsnCRQ1
AMt3HHYQyJ/woxF+xMCpRDR3jBadQ/SE8cfiqNSb+6fTTRMXt4Hzm2zNfEbFYBNo
bHg49P3V1fDMOyE=
-----END PRIVATE KEY-----
';
    
    protected $acraizbr = '-----BEGIN CERTIFICATE-----
MIIGoTCCBImgAwIBAgIBATANBgkqhkiG9w0BAQ0FADCBlzELMAkGA1UEBhMCQlIx
EzARBgNVBAoTCklDUC1CcmFzaWwxPTA7BgNVBAsTNEluc3RpdHV0byBOYWNpb25h
bCBkZSBUZWNub2xvZ2lhIGRhIEluZm9ybWFjYW8gLSBJVEkxNDAyBgNVBAMTK0F1
dG9yaWRhZGUgQ2VydGlmaWNhZG9yYSBSYWl6IEJyYXNpbGVpcmEgdjIwHhcNMTAw
NjIxMTkwNDU3WhcNMjMwNjIxMTkwNDU3WjCBlzELMAkGA1UEBhMCQlIxEzARBgNV
BAoTCklDUC1CcmFzaWwxPTA7BgNVBAsTNEluc3RpdHV0byBOYWNpb25hbCBkZSBU
ZWNub2xvZ2lhIGRhIEluZm9ybWFjYW8gLSBJVEkxNDAyBgNVBAMTK0F1dG9yaWRh
ZGUgQ2VydGlmaWNhZG9yYSBSYWl6IEJyYXNpbGVpcmEgdjIwggIiMA0GCSqGSIb3
DQEBAQUAA4ICDwAwggIKAoICAQC6RqQO3edA8rWgfFKVV0X8bYTzhgHJhQOtmKvS
8l4Fmcm7b2Jn/XdEuQMHPNIbAGLUcCxCg3lmq5lWroG8akm983QPYrfrWwdmlEIk
nUasmkIYMPAkqFFB6quV8agrAnhptSknXpwuc8b+I6Xjps79bBtrAFTrAK1POkw8
5wqIW9pemgtW5LVUOB3yCpNkTsNBklMgKs/8dG7U2zM4YuT+jkxYHPePKk3/xZLZ
CVK9z3AAnWmaM2qIh0UhmRZRDTTfgr20aah8fNTd0/IVXEvFWBDqhRnLNiJYKnIM
mpbeys8IUWG/tAUpBiuGkP7pTcMEBUfLz3bZf3Gmh3sVQOQzgHgHHaTyjptAO8ly
UN9pvvAslh+QtdWudONltIwa6Wob+3JcxYJU6uBTB8TMEun33tcv1EgvRz8mYQSx
Epoza7WGSxMr0IadR+1p+/yEEmb4VuUOimx2xGsaesKgWhLRI4lYAXwIWNoVjhXZ
fn03tqRF9QOFzEf6i3lFuGZiM9MmSt4c6dR/5m0muTx9zQ8oCikPm91jq7mmRxqE
14WkA2UGBEtSjYM0Qn8xjhEu5rNnlUB+l3pAAPkRbIM4WK0DM1umxMHFsKwNqQbw
pmkBNLbp+JRITz6mdQnsSsU74MlesDL/n2lZzzwwbw3OJ1fsWhto/+xPb3gyPnnF
tF2VfwIDAQABo4H1MIHyME4GA1UdIARHMEUwQwYFYEwBAQAwOjA4BggrBgEFBQcC
ARYsaHR0cDovL2FjcmFpei5pY3BicmFzaWwuZ292LmJyL0RQQ2FjcmFpei5wZGYw
PwYDVR0fBDgwNjA0oDKgMIYuaHR0cDovL2FjcmFpei5pY3BicmFzaWwuZ292LmJy
L0xDUmFjcmFpenYyLmNybDAfBgNVHSMEGDAWgBQMOSA6twEfy9cofUGgx/pKrTIk
vjAdBgNVHQ4EFgQUDDkgOrcBH8vXKH1BoMf6Sq0yJL4wDwYDVR0TAQH/BAUwAwEB
/zAOBgNVHQ8BAf8EBAMCAQYwDQYJKoZIhvcNAQENBQADggIBAFmaFGkYbX0pQ3B9
dpth33eOGnbkqdbLdqQWDEyUEsaQ0YEDxa0G2S1EvLIJdgmAOWcAGDRtBgrmtRBZ
SLp1YPw/jh0YVXArnkuVrImrCncke2HEx5EmjkYTUTe2jCcK0w3wmisig4OzvYM1
rZs8vHiDKTVhNvgRcTMgVGNTRQHYE1qEO9dmEyS3xEbFIthzJO4cExeWyCXoGx7P
34VQbTzq91CeG5fep2vb1nPSz3xQwLCM5VMSeoY5rDVbZ8fq1PvRwl3qDpdzmK4p
v+Q68wQ2UCzt3h7bhegdhAnu86aDM1tvR3lPSLX8uCYTq6qz9GER+0Vn8x0+bv4q
SyZEGp+xouA82uDkBTp4rPuooU2/XSx3KZDNEx3vBijYtxTzW8jJnqd+MRKKeGLE
0QW8BgJjBCsNid3kXFsygETUQuwq8/JAhzHVPuIKMgwUjdVybQvm/Y3kqPMFjXUX
d5sKufqQkplliDJnQwWOLQsVuzXxYejZZ3ftFuXoAS1rND+Og7P36g9KHj41hJ2M
gDQ/qZXow63EzZ7KFBYsGZ7kNou5uaNCJQc+w+XVaE+gZhyms7ZzHJAaP0C5GlZC
cIf/by0PEf0e//eFMBUO4xcx7ieVzMnpmR6Xx21bB7UFaj3yRd+6gnkkcC6bgh9m
qaVtJ8z2KqLRX4Vv4EadqtKlTlUO
-----END CERTIFICATE-----
';
    
    protected $accmg5 = '-----BEGIN CERTIFICATE-----
MIIIrDCCBpSgAwIBAgIQQE8wGbCq/wL/9/GVhT2sOTANBgkqhkiG9w0BAQ0FADBy
MQswCQYDVQQGEwJCUjETMBEGA1UEChMKSUNQLUJyYXNpbDE0MDIGA1UECxMrQXV0
b3JpZGFkZSBDZXJ0aWZpY2Fkb3JhIFJhaXogQnJhc2lsZWlyYSB2MjEYMBYGA1UE
AxMPQUMgQ2VydGlzaWduIEc2MB4XDTExMDkyMzAwMDAwMFoXDTE5MDkyMjIzNTk1
OVowdDELMAkGA1UEBhMCQlIxEzARBgNVBAoTCklDUC1CcmFzaWwxLTArBgNVBAsT
JENlcnRpc2lnbiBDZXJ0aWZpY2Fkb3JhIERpZ2l0YWwgUy5BLjEhMB8GA1UEAxMY
QUMgQ2VydGlzaWduIE11bHRpcGxhIEc1MIICIjANBgkqhkiG9w0BAQEFAAOCAg8A
MIICCgKCAgEAy36Res/eZPe+zbRCqZU+G8ox+WX/vwwoRquKtzX7ocVGYMN3HHiR
R8343HJg2fivGfAbZHfL3c0qNs8wDI2aC+kj8Zu8iLyjiGeK/4uvgNHAcxc+fPro
St9mMKtfqjUDjFxdIjs8fHEpRaiO9zT7VKsR6erV1Etb1Ad9hgQfy9nLunlpK0iC
AQaJ67r3ZxvAX1ownLUxh8tDsRBCq7y7FO05eiYClHnCCWmqISa8PothRyuk/ihh
PR/sR7SXsqbcKLXcuZYcGOlZk/GQ7Kkd20ZFu5miWMc5FEblijfzGCRYOKnBuzlO
XQVPFk31un3wEeGOO/n+BJkNWaczik9o/fb16X7D+506FIS/WR4IvY3iZx4da+4g
eHedMiAnhgHXSDGFqBc7APsrf/NcPuk2IoCzmJ9sfBha3xr41Wlf5LJc4saZ70xy
ylmrah8Sse6Xkm1SfBR1qWwmEf3wGIHiL6dV7VItsXoo3prrptpyBnEuxPUip2P2
PFU5TjTZkIvgUblOMDEsQNa11vTZb6BnjFS/uDM8ouuM/ySRIpOQaRkqw7MTQTGY
MQdOgpd9KyMuAP2P4sIbhPRxOylqkekn6AYPFJDY35gj0d2sW3CN4x+ywQc54Ky3
mDxSS1LGQFV7nIzYgi/24dNJDAKLPfsBZGimCUaVLZPkh54UVsVlTeECAwEAAaOC
AzowggM2MBIGA1UdEwEB/wQIMAYBAf8CAQAwXwYDVR0fBFgwVjBUoFKgUIZOaHR0
cDovL2ljcC1icmFzaWwuY2VydGlzaWduLmNvbS5ici9yZXBvc2l0b3Jpby9sY3Iv
QUNDZXJ0aXNpZ25HNi9MYXRlc3RDUkwuY3JsMA4GA1UdDwEB/wQEAwIBBjCCAm0G
A1UdIASCAmQwggJgMEoGBmBMAQIBCzBAMD4GCCsGAQUFBwIBFjJodHRwOi8vaWNw
LWJyYXNpbC5jZXJ0aXNpZ24uY29tLmJyL3JlcG9zaXRvcmlvL2RwYzBKBgZgTAEC
AgMwQDA+BggrBgEFBQcCARYyaHR0cDovL2ljcC1icmFzaWwuY2VydGlzaWduLmNv
bS5ici9yZXBvc2l0b3Jpby9kcGMwSgYGYEwBAgMFMEAwPgYIKwYBBQUHAgEWMmh0
dHA6Ly9pY3AtYnJhc2lsLmNlcnRpc2lnbi5jb20uYnIvcmVwb3NpdG9yaW8vZHBj
MEoGBmBMAQIEAzBAMD4GCCsGAQUFBwIBFjJodHRwOi8vaWNwLWJyYXNpbC5jZXJ0
aXNpZ24uY29tLmJyL3JlcG9zaXRvcmlvL2RwYzBKBgZgTAECZQMwQDA+BggrBgEF
BQcCARYyaHR0cDovL2ljcC1icmFzaWwuY2VydGlzaWduLmNvbS5ici9yZXBvc2l0
b3Jpby9kcGMwSgYGYEwBAmYDMEAwPgYIKwYBBQUHAgEWMmh0dHA6Ly9pY3AtYnJh
c2lsLmNlcnRpc2lnbi5jb20uYnIvcmVwb3NpdG9yaW8vZHBjMEoGBmBMAQJnAzBA
MD4GCCsGAQUFBwIBFjJodHRwOi8vaWNwLWJyYXNpbC5jZXJ0aXNpZ24uY29tLmJy
L3JlcG9zaXRvcmlvL2RwYzBKBgZgTAECaAMwQDA+BggrBgEFBQcCARYyaHR0cDov
L2ljcC1icmFzaWwuY2VydGlzaWduLmNvbS5ici9yZXBvc2l0b3Jpby9kcGMwHQYD
VR0OBBYEFJ1Qz73/JMqvsTPrF+JCeo5pKo5TMB8GA1UdIwQYMBaAFEJnjpWMD/lw
0VK8PNBasYfYnYA4MA0GCSqGSIb3DQEBDQUAA4ICAQAysKiO+M0AbKoyXvi/yiQB
yMb6j6/FZh8m3/Wm1CAeWGNScPJJ2RcFBA7ipLE1Y2al0y3JN/ruFXYX1HTO7Nw0
IujjQ10O84PudtTSx6GdqH9x0K6TdLyAmZtKgLvW0LhrqvDGGzMFWrE4LUosw+N5
/bc8zgVOaR2aSsVOVbHSO3yXDWfYYCl0dqLOhNuB+MJCUhct0SfA7n6lKE60sMgG
JGV1IjlPZ0KFbjBwLC46phBbsStuDR4ZcSTQMOzeA300a5HMYU3JHho2I38oOORF
qpyFiUEbpcL89lRTLQcKf8X+42nxm73znMhnFDpU5DuvwogCAX07hhRGTZ3x4ELe
QHMAAjvTY5MitCRJMKQzaAbmcEy9uArj27r61NEc8t9DVK6aWL+xwq9qIgwAdYz6
Dr5IfyZygFgMtEq5eqz1L5p2FGVHOFUQW9wMfUYQjwkQ6KnhniE8sItYmJBucJQM
u2+5LQ4GcVhUnDXa1ydzZjKP+Xb1URxyp5oLySKVALHovxav9PpiUwub4L6oOJ2h
vaG8N/3dr4CCQ+qN7O4cpd+nLRYQxujwVOe++Qn+u5qOzK/cucbSAWhpIU7z/psU
tMbXKo/qEqHBfAPKeZA/WP5RrkZ03bDM/4/mGBwMVUJxhI/jX/avJRRV4dReoC40
BNz76g2K0ZkI9A+4VM63nA==
-----END CERTIFICATE-----
';

    protected $accg6 = '-----BEGIN CERTIFICATE-----
MIIGQTCCBCmgAwIBAgIBBTANBgkqhkiG9w0BAQ0FADCBlzELMAkGA1UEBhMCQlIx
EzARBgNVBAoTCklDUC1CcmFzaWwxPTA7BgNVBAsTNEluc3RpdHV0byBOYWNpb25h
bCBkZSBUZWNub2xvZ2lhIGRhIEluZm9ybWFjYW8gLSBJVEkxNDAyBgNVBAMTK0F1
dG9yaWRhZGUgQ2VydGlmaWNhZG9yYSBSYWl6IEJyYXNpbGVpcmEgdjIwHhcNMTEw
OTIwMTg1MTQwWhcNMjEwOTIwMTg1MTQwWjByMQswCQYDVQQGEwJCUjETMBEGA1UE
ChMKSUNQLUJyYXNpbDE0MDIGA1UECxMrQXV0b3JpZGFkZSBDZXJ0aWZpY2Fkb3Jh
IFJhaXogQnJhc2lsZWlyYSB2MjEYMBYGA1UEAxMPQUMgQ2VydGlzaWduIEc2MIIC
IjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAmG1gPG/50iiBd2xP4XShvyRe
l6lgbfwIUwHvKIHs1LqOIsyE2L6SPbRo2A1KATeG3O3F2hVJ+nvvqHsCbdHkroAr
wWEgiz8RVepmO5qQTLU97Macrx6ZO90GViy0IIhYA8n/fPCzA9P0PyRidDgo+Tms
+27xPLvRlcNmA1kepSMg6z81hVGiNB7UXvWOIy5oxVq59JNQ2ppmqMW3CAXrk7Ir
hTMbfhgRCFT4N1q9avAr1ltQ5zVpR/J3PpaI8wK21d+qWgzyY8jkjLv59RTaKNYo
07p1TWHbC6NdHNZSUov7C2PuiNHf7UNuhts/JNxElpeE6ToXM0JVJSflxs9tnKKa
JA78uA3CRTdyLNWDw5vaY29BcNwCyA0p0vcenapxHLA1pDGvQ5FrAkqc/MDZdR9H
/MD9KdQ/Mgu/YYEQCOuc56+xW4lFDo2Rluq8x+jXixzgSA4zuD5P7jnrO799WTS/
qcPcX01NU/SeRc4rSxEgyUbCKeBRzAHJcPvxWm3RMajLBDMbrf7Dt5Yrmo47Ux7O
w6wF5dn94nmrWhK5GE5OB7pBGLAHYp1dKpIR0sZNA1vkYSBLdw7fXvewW9UcKMY/
+JVRjL2hVW0PCHsRXxoI8P4L7XaOFrgGV6B8+y9N+cK6Y5BOfwGglq2cRdXhXLvt
Go+9KdHtpsgFHyCsfxcCAwEAAaOBuzCBuDAUBgNVHSAEDTALMAkGBWBMAQEFMAAw
PwYDVR0fBDgwNjA0oDKgMIYuaHR0cDovL2FjcmFpei5pY3BicmFzaWwuZ292LmJy
L0xDUmFjcmFpenYyLmNybDAfBgNVHSMEGDAWgBQMOSA6twEfy9cofUGgx/pKrTIk
vjAdBgNVHQ4EFgQUQmeOlYwP+XDRUrw80Fqxh9idgDgwDwYDVR0TAQH/BAUwAwEB
/zAOBgNVHQ8BAf8EBAMCAQYwDQYJKoZIhvcNAQENBQADggIBAKIgDXqL5JueJlsR
DE0YaWlfuIzCgVBC1j62RbzDGcBR8rn/JkOp+zXRiQePivDA+rMlUecTpUG7O6M8
UFKJqAnEURf8HVTQdYUr8TZs2XycVMaGCmL7lud6NsDTFvFNBKJU9RuJ8lj8k68S
g2XlBl2Bxykm7KrtWfpNWFWq6gr/U60N7fYlCVBLRH6dcCuLNcruvKmPdCgDUQs6
+7ilETF0m2GTGT/R85pBc2ldM0S9j3M8oGvWW7FAFruYozKGLWhHjmsj9LVNrfkJ
DcQ4KG3M7Gx/px+a7b276ehXxzPSr6EjZQLonhtlVUWpQ+4Y1GUrhIekIQYky9Vz
2CZ4MFs+2j9y6NMZa5Efy+GkIhJqdVRCRYWaL9B4dnTi4N2qO+odU4fURaOxcJhj
r7XL9ptxJMrh2Y9c/O+NDU4BUsmdhLHPU3TXHFxQj6W5rO+1KKqyU3ZrBBq47YZ0
kqaS/wPRg+1UwmRyuddceDnmsmx+2b7ocFYNmE0l7qsp8kw1z/PQcijXcp8Tlunu
NYjdmgIThhgdue2Y4CKSHcjdB+sXsgAP9jx0OYdrRVgdAyMt/2LPGQNW3Lhb9B+y
aw6Jcw8PUEIRFSrvhM3gz1S7VJELf0CEw1ED26MduNjPv4/ycpOfkriRYELHT/uF
VURh6kJDAMVhhB5URXSnHhuGrKGi
-----END CERTIFICATE-----
';


    public function testCarregarPfx()
    {
        $pathCerts = '';
        $cnpj = '99999090910270';
        $pubKey = '';
        $priKey = '';
        $certKey = '';
        $pkcs = new Pkcs12($pathCerts, $cnpj, $pubKey, $priKey, $certKey);
        $arquivopfx = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/certificado_teste.pfx';
        $pfxContent = file_get_contents($arquivopfx);
        $keyPass = 'associacao';
        $createFiles = false;
        $ignoreValidity = true;
        $ignoreOwner = true;
        $resp = $pkcs->loadPfx($pfxContent, $keyPass, $createFiles, $ignoreValidity, $ignoreOwner);
        $this->assertTrue($resp);
        $this->assertEquals($this->pubPem, $pkcs->pubKey);
        $this->assertEquals($this->priPem, $pkcs->priKey);
    }
    
    public function testCarregarPfxFile()
    {
        $pathCerts = '';
        $cnpj = '99999090910270';
        $pubKey = '';
        $priKey = '';
        $certKey = '';
        $pkcs = new Pkcs12($pathCerts, $cnpj, $pubKey, $priKey, $certKey);
        $arquivopfx = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/certificado_teste.pfx';
        $keyPass = 'associacao';
        $createFiles = false;
        $ignoreValidity = true;
        $ignoreOwner = true;
        $resp = $pkcs->loadPfxFile($arquivopfx, $keyPass, $createFiles, $ignoreValidity, $ignoreOwner);
        $this->assertTrue($resp);
    }

    /**
     * @expectedException NFePHP\Common\Exception\RuntimeException
     * @expectedExceptionMessage Data de validade vencida! [Valido até 02/10/10]
     */
    public function testValidadeCertificado()
    {
        $cnpj = '99999090910270';
        $pathCerts = '';
        $ignoreValidCert = false;
        new Pkcs12($pathCerts, $cnpj, $this->pubPem, $this->priPem, '', $ignoreValidCert);
    }

    /**
     * @expectedException NFePHP\Common\Exception\InvalidArgumentException
     * @expectedExceptionMessage O Certificado fornecido pertence a outro CNPJ!!
     */
    public function testProprietarioCertificado()
    {
        $pathCerts = '';
        $cnpj = '55599090910270';
        $pubKey = '';
        $priKey = '';
        $certKey = '';
        $pkcs = new Pkcs12($pathCerts, $cnpj, $pubKey, $priKey, $certKey);
        $arquivopfx = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/certificado_teste.pfx';
        $pfxContent = file_get_contents($arquivopfx);
        $keyPass = 'associacao';
        $createFiles = false;
        $ignoreValidity = true;
        $ignoreOwner = false;
        $pkcs->loadPfx($pfxContent, $keyPass, $createFiles, $ignoreValidity, $ignoreOwner);
    }
    
    public function testAssinarXml()
    {
        $cnpj = '99999090910270';
        $pathCerts = '';
        $ignoreValidCert = true;
        $pkcs = new Pkcs12($pathCerts, $cnpj, $this->pubPem, $this->priPem, '', $ignoreValidCert);
        $xmlpath = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/35101158716523000119550010000000011003000000-nfe.xml';
        $docxml = file_get_contents($xmlpath);
        $signedxmlpath = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/signed-nfe.xml';
        $docsigned= file_get_contents($signedxmlpath);
        $doc = $pkcs->signXML($docxml, 'infNFe');
        $this->assertEquals($doc, $docsigned);
    }
    
    public function testVerificarAssinatura()
    {
        $cnpj = '99999090910270';
        $pkcs = new Pkcs12('', $cnpj, '', '', '', true);
        $signedxmlpath = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/signed-nfe.xml';
        $ret = $pkcs->verifySignature($signedxmlpath, 'infNFe');
        $this->assertTrue($ret);
    }
    
    public function testAdicionarCadeiaCertificados()
    {
        $aCerts[] = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/ACCertisignG6_v2.cer';
        $aCerts[] = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/ACCertisignMultiplaG5.cer';
        $aCerts[] = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/ACRaizBrasileira_v2.cer';
        $cert = $this->priPem . "\r\n" .
            $this->pubPem . "\r\n" .
            $this->accg6 . "\r\n" .
            $this->accmg5 . "\r\n" .
            $this->acraizbr;
        $cert = str_replace(array("\r","\n"), '', $cert);
        $cnpj = '99999090910270';
        $pathCerts = '';
        $ignoreValidCert = true;
        $pkcs = new Pkcs12($pathCerts, $cnpj, $this->pubPem, $this->priPem, '', $ignoreValidCert);
        $pkcs->aadChain($aCerts);
        $cadeiacompleta = str_replace(array("\r","\n"), '', $pkcs->certKey);
        $this->assertEquals($cadeiacompleta, $cert);
    }
}
