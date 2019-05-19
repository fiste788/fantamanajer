<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?= $this->fetch('title') ?></title>
        <style type="text/css">
            .ReadMsgBody {width: 100%; background-color: #ffffff;}
            .ExternalClass {width: 100%; background-color: #ffffff;}
            body	 {width: 100%; background-color: #ffffff; margin:0; padding:0; -webkit-font-smoothing: antialiased;font-family: Roboto, Arial, sans-serif}
            table {border-collapse: collapse;}

            @media only screen and (max-width: 640px)  {
                .deviceWidth {width:440px!important; padding:0;}
                .center {text-align: center!important;}
            }

            @media only screen and (max-width: 479px) {
                .deviceWidth {width:280px!important; padding:0;}
                .center {text-align: center!important;}
            }
        </style>
    </head>
    <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="font-family: Roboto, Arial, sans-serif">
        <!-- Wrapper -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
            <tr>
                <td width="100%" valign="top" bgcolor="#ffffff" style="padding-top:20px">

                    <!-- Start Header-->
                    <table width="580" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth" style="margin:0 auto;">
                        <tr>
                            <td width="100%" bgcolor="#ffffff">

                                <!-- Logo -->
                                <table border="0" cellpadding="0" cellspacing="0" align="left" class="deviceWidth">
                                    <tr>
                                        <td style="padding:10px 20px" class="center">
                                            <a style="text-decoration:none;font-size:16px; font-weight: bold; color: #333;" href="https://dev.fantamanajer.it/">
                                                <img src="https://dev.fantamanajer.it/assets/favicon-32x32.png" alt="" border="0" />
                                                <span> FantaManajer</span>
                                            </a>
                                        </td>
                                    </tr>
                                </table><!-- End Logo -->
                                
                            </td>
                        </tr>
                    </table><!-- End Header -->

                    <!-- One Column -->
                    <table width="580"  class="deviceWidth" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#fafafa" style="margin:0 auto;">
                        <tr>
                            <td style="font-size: 13px; color: #959595; font-weight: normal; text-align: left; font-family: Roboto, Arial, sans-serif; line-height: 24px; vertical-align: top; padding:10px 8px 10px 8px" bgcolor="#fafafa">
                                <?= $this->fetch('content') ?>
                            </td>
                        </tr>
                    </table><!-- End One Column -->

                    <div style="height:15px;margin:0 auto;">&nbsp;</div><!-- spacer -->
                </td>
            </tr>
        </table> <!-- End Wrapper -->
        <div style="display:none; white-space:nowrap; font:15px courier; color:#ffffff;">
            - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        </div>
    </body>
</html>
