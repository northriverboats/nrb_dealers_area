<?php

isset($row) or die('Direct Access to this location is not allowed.');

?>
<table width="640" border="0" cellpadding="0" cellspacing="0" bordercolor="#999">

<tr>

<td width="17" align="left" valign="top" style="background-color:#0066cc"></td>

<td width="123" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:24px; color:#FFF; background-color:#0066cc"><img src="https://www.northriverboats.com/logo-white2.png" width="205" height="75" /></td>

<td width="561" style="font-family:Arial, Helvetica, sans-serif; font-size:24px; color:#FFF; background-color:#0066cc; padding-left:15px;">Dealer Receipt Inpection Form</td>

<td width="17" align="left" valign="top" style="background-color:#0066cc"></td>

</tr>

<tr>

<td height="33" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:9px; padding:8px; background-color:#0066cc">&nbsp;</td>

<td height="33" colspan="2" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:9px; padding:8px; background-color:#ffffff"><table width="650" border="0" cellpadding="3" cellspacing="0">

<tr>

<td colspan="3" align="left" valign="top" bgcolor="#99FFFF" style="width:190px; background:#99FFFF; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; margin-left:10px;">Dealer Receipt Inspection Form :: 
<?= substr($row['submitted'],5,2) ."/".  substr($row['submitted'], 8,2) .'/' . substr($row['submitted'], 0, 4) ?></td>
</tr>

<!-- Spacer Row -->

<tr>

<td colspan="3" align="left" valign="top" style="height:5px;"></td>

</tr>

<?php

foreach ($row as $key => $value):
    $title = ucwords(str_replace('_',' ', $key));
    $val = $value;
    if ($key == 'id') { continue; }
    if ($key == 'uid') { continue; }
    if ($key == 'old_id') { continue; }
    // if ($key == 'old_id') { $title = "Survey id"; $val =  strtoupper(urlencode(substr("00000".dechex($value*19),-5))); }
    if (substr($key,0,4) == 'file') {
        $val = str_replace("/var/www/wordpress",'https://www.northriverboats.com', $val);
        $val = str_replace("/var/www/nrb/media",'https://www.northriverbotas.com/wp-content', $val);
        $val = '<a href="' . $val . '">' . $val . '</a>';
    }


?>

    <tr>

    <td width="25%" align="left" valign="top" bgcolor="#eef0f3" style="background-color:#eef0f3; Font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; margin-left:10px; border-top:thin; border-top-color:#999; border-top-style:solid; border-left:thin; border-left-color:#999; border-left-style:solid;"><?= $title ?> </td>

    <td colspan="2" align="left" valign="top" bgcolor="#eef0f3" style="height:22px; height:22px; background-color:#eef0f3; font-family:Arial, Helvetica, sans-serif; font-size:11px; margin-left:10px; border-top:thin; border-top-color:#999; border-top-style:solid; border-left:thin; border-left-color:#999; border-left-style:solid; border-right:thin; border-right-color:#999; border-right-style:solid;"><?= $val ?> </td>

    </tr>

    <!-- Spacer Row -->

    <tr>

    <td colspan="3" align="left" valign="top" style="height:5px; border-top:thin; border-top-color:#999; border-top-style:solid"></td>

    </tr>

<?php endforeach; ?>


<tr>

<td colspan="3" align="left" valign="top" style="height:5px; border-top:thin; border-top-color:#999; border-top-style:solid"></td>

</tr>

<tr>

</table></td>

<td height="33" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:9px; padding:8px; background-color:#0066cc">&nbsp;</td>

</tr>

<tr>

<td height="20" colspan="4" align="left" valign="middle" style="background-color:#0066cc; color:#CCC; font-family:Verdana, Geneva, sans-serif; font-size:9px; padding-left:10px; text-decoration:none;"></td>

</tr>

</table>
