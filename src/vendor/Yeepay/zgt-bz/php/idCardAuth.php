<?php
require_once(__DIR__ . "/../inc/config.php");

$requestid = "ZGTDIVIDE" . date("ymd_His") . rand(10, 99);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=<?= __LOCALE__CODE__ ?>" />
        <title>企业法人补充身份证认证</title>
    </head>
    <body>
        <br /> <br />
        <table width="80%" border="0" align="center" cellpadding="9" cellspacing="0" style="border:solid 1px #107929">
            <tr>
                <th align="center" height="30" colspan="5" bgcolor="#6BBE18">
                    请输企业法人补充身份证认证参数	
                </th>
            </tr> 

            <form method="post" action="../php/sendidCardAuth.php" target="_blank" accept-charset="<?= __LOCALE__CODE__ ?>">

                <tr >
                    <td width="20%" align="left">&nbsp;商户请求号</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="requestid" value="<?= $requestid ?>" />
                        <span style="color:#FF0000;font-weight:100;">*</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">requestid</td> 
                </tr>

                <tr >
                    <td width="20%" align="left">&nbsp;子账户编号</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="ledgerno" value="" />
                        <span style="color:#FF0000;font-weight:100;">*</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">ledgerno</td> 
                </tr> 

                <tr >
                    <td width="20%" align="left">&nbsp;身份证号</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="idcard" value="" />
                        <span style="color:#FF0000;font-weight:100;">*</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">idcard</td> 
                </tr>
                <tr >
                    <td width="20%" align="left">&nbsp;</td>
                    <td width="5%"  align="center">&nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input type="submit" value="单击查询" />
                    </td>
                    <td width="5%"  align="center">&nbsp;</td> 
                    <td width="15%" align="left">&nbsp;</td> 
                </tr>

            </form>
        </table>
    </body>
</html>