<?php require_once('../Connections/wistream.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if(isset($_POST['MM_insert']) && $_POST['MM_insert'] == "addnew")
{
// New Name of File
if(isset($_FILES['upfile']['name']) && $_FILES['upfile']['name'] != "")
{
$newname = uniqid(mt_rand(1,9999999999)) . ".jpg";
}
$replace = array(" ","@", ",", "?", "!", "+", "=", "#", "$", "&", "*", "(", ")", "/", "'", "~", "%", "[", "]", ":", ";", "<", ">", ".");
$sqlcreate = sprintf("INSERT INTO content (title, content, dnt, category, photo, shorturl, uname) VALUES ('%s', '%s', '%s', '%s','%s', '%s','%s')",
				ucwords(stripslashes(strip_tags($_POST['title']))),
				mysql_real_escape_string(stripslashes($_POST['content'])),
				mysql_real_escape_string($_POST['dnt']),
				mysql_real_escape_string($_POST['category']),
				mysql_real_escape_string($newname),
				mysql_real_escape_string(str_replace($replace ,"-", strtolower($_POST['title']))),
				mysql_real_escape_string($_POST['uname']));
				
	mysql_select_db($database_wistream);
	$runadd = mysql_query($sqlcreate) or die("Unexpected Error: " . mysql_error());
// File  Upload
if($_FILES['upfile']['name'] != "")
{
$uplfile = move_uploaded_file($_FILES['upfile']['tmp_name'], "contimg/" . $newname) or die($_FILES['upfile']['error']);
}

// Redirect
header("Location: cats.php?c=$_POST[category]&status=newpost"); 
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Wistream CMS</title>
<style type="text/css">
body {
	margin-top: 0px;
	
}
</style>		
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="ckeditor/samples/sample.css">
</head>

<body>
<table width="79%" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" bgcolor="#FFFFFF"><img src="images/wicee.jpg" alt="" width="127" height="109" align="middle" /></td>
  </tr>
</table>
<table width="79%" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="54%" height="26" bgcolor="#FFCC33"><strong>Wistream CMS</strong></td>
    <td width="46%" align="right" bgcolor="#FFCC33"><a href="<?php echo $logoutAction ?>">Log Out</a></td>
  </tr>
  <tr>
    <td height="26" colspan="2" align="center" bgcolor="#FFFFCC"><b><font size="+1">Create New Post</font></b></td>
  </tr>
  <tr>
    <td height="436" colspan="2" align="center" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td height="19" bgcolor="#FFFF00"><a href="cpanel.php">Categories</a> &gt; <a href="cats.php?c=<?php echo $_GET['c']; ?>">Return</a> &gt;<strong> 
		<?php echo ucwords($_GET['c']); ?></strong></td>
      </tr>
    </table>
      <form action="" method="POST" enctype="multipart/form-data" name="addnew" id="addnew">
        <table width="100%" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="13%" height="46" align="center">
            <label for="title">Title:</label></td>
            <td width="87%" align="left"><span id="sprytextfield1">
              <input name="title" type="text" id="title" style="font-size:18px;font-family:Arial;" size="95" />
            <span class="textfieldRequiredMsg"><br />
            Enter Title</span></span></td>
          </tr>
          <tr>
            <td height="348" align="left"><label for="content"></label></td>
            <td height="348" align="left"><span id="sprytextarea1">
            <label for="content2"></label>
            <textarea name="content" class="ckeditor" id="content2" style="font-family:Arial;" cols="132" rows="20"></textarea>
            <span class="textareaRequiredMsg"><br />
Enter Body</span></span></td>
          </tr>
          <tr>
            <td colspan="2" align="center"><table width="275" border="1" cellpadding="1" cellspacing="0">
              <tr>
                <td width="269"><label for="upfile3"></label>
                  Attach Image:
                  <input type="file" name="upfile" id="upfile4" /></td>
                  
                <?php if(isset($_GET['c']) && $_GET['c'] == "cars") { ?>
                <?php } ?>
                </tr>
            </table></td>
          </tr>
        </table>
        <input name="category" type="hidden" id="category" value="<?php echo $_GET['c']; ?>" />
        <input name="dnt" type="hidden" id="dnt" value="<?php echo date('d-m-Y h:i:s a'); ?>" />
        <input name="uname" type="hidden" id="uname" value="<?php echo $_SESSION['MM_Username']; ?>" />
        <input type="submit" style="font-weight:bold;font-size:20px;background-color:#FF3;" name="btncreate" id="btncreate" value="Create" />
		<input type="hidden" name="MM_insert" value="addnew" />
    </form></td>
  </tr>
  <tr>
    <td height="24" colspan="2" align="center" bgcolor="#FFD9B3">powered by <a href="http://wiceeweb.com/" target="_blank">Wicee</a></td>
  </tr>
</table>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {validateOn:["change"]});
</script>
</body>
</html>
