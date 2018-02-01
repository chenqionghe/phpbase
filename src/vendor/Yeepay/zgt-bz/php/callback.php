<?php
require_once(__DIR__ . "/../inc/config.php");

define("__BIZ__", "pay");

if ( !isViaArray($_REQUEST, "data") ) {

	throw new ZGTException("callback param data is null.");
}


$data = $_REQUEST["data"];

if( "" == trim($data) ) {
	
	echo("ONEKEY 一键支付成功");
	exit();
}

//解析返回值
if ( !isViaArray($infConfig) ) {
	
	throw new ZGTException("infConfig is null.");
}
		
if ( !array_key_exists(__BIZ__, $infConfig) ) {
			
	throw new ZGTException("biz of infConfig is not found[" . __BIZ__ . "].");
}
		
$customernumber = getCustomerNumber();
$keyForHmac = getKeyValue();
$keyForAES = getKeyForAes();
$bizConfig = $infConfig[__BIZ__];

$responseData = getDeAes($data, $keyForAES);
$result = json_decode($responseData, true);

//进行UTF-8->GBK转码
$resultLocale = array();
foreach ( $result as $rKey => $rValue ) {
	
	$resultLocale[$rKey] = iconv(getRemoteCode(), getLocaleCode(), $rValue);
}

echo "resultLocale<br />";
print_r($resultLocale);
echo "<br /><hr />";

if ( "1" != $result["code"] ) {

	throw new ZGTException("response error, errmsg = [" . $resultLocale["msg"] . "], errcode = [" . $resultLocale["code"] . "].", $result["code"]);
}

if ( array_key_exists("customError", $result)
		 && "" != $result["customError"] ) {
	
	throw new ZGTException("response.customError error, errmsg = [" . $resultLocale["customError"] . "], errcode = [" . $resultLocale["code"] . "].", $result["code"]);
}

if ( $result["customernumber"] != $customernumber ) {
	
	throw new ZGTException("customernumber not equals, request is [" . $customernumber . "], response is [" . $hmacData["customernumber"] . "].");
}

//验证返回签名
$hmacGenConfig = $bizConfig["needCallbackHmac"];
$hmacData = array();
foreach ( $hmacGenConfig as $hKey => $hValue ) {
	
	$v = "";
	//判断$queryData中是否存在此索引并且是否可访问
	if ( isViaArray($result, $hValue) && $result[$hValue] ) {
		
		$v = $result[$hValue];
	}
	
	//取得对应加密的明文的值
	$hmacData[$hKey] = $v;

}
$hmac = getHmac($hmacData, $keyForHmac);

if ( $hmac != $result["hmac"] ) {
	
	throw new ZGTException("hmac not equals, response is [" . $result["hmac"] . "], gen is [" . $hmac . "].");
}

if ( "SERVER" == $result["notifytype"] ) {
	
	echo "SUCCESS";
	exit();
}

?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=__LOCALE__CODE__?>">
		<title>通知-订单支付成功</title>
	</head>
	<body>	
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
					通知-订单支付成功
				</th>
		  	</tr>

			<tr>
				<td width="15%" align="left">&nbsp;商户编号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["customernumber"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">customernumber</td> 
			</tr>

			<tr>
				<td width="15%" align="left">&nbsp;返回码</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["code"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">code</td> 
			</tr>

			<tr>
				<td width="15%" align="left">&nbsp;通知类型</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["notifytype"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">notifytype</td> 
			</tr>

			<tr>
				<td width="15%" align="left">&nbsp;商户订单号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["requestid"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">requestid</td> 
			</tr>

			<tr>
				<td width="15%" align="left">&nbsp;交易流水号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["externalid"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">externalid</td> 
			</tr>

			<tr>
				<td width="15%" align="left">&nbsp;订单金额</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["amount"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">amount</td> 
			</tr>

			<tr>
				<td width="15%" align="left">&nbsp;银行卡号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["cardno"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">cardno</td> 
			</tr>

			<tr>
				<td width="15%" align="left">&nbsp;银行编码</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["bankcode"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">bankcode</td> 
			</tr>
			
					<tr>
				<td width="15%" align="left">&nbsp;银行卡类别</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["cardtype"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">cardtype</td> 
			</tr>
			<tr>
				<td width="15%" align="left">&nbsp;支付时间</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["paydate"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">paydate</td> 
			</tr>			
			
			<tr>
				<td width="15%" align="left">&nbsp;支付方式</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["payProduct"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">payProduct</td> 
			</tr>
                        <tr>
				<td width="15%" align="left">&nbsp;支付方式</td>
				<td width="5%"  align="center"> : </td> 
				<td width="60%" align="left"> <?=$resultLocale["payProduct"]?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">payProduct</td> 
			</tr>
	</body>

</html>
