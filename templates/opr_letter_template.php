<?php isset($row) or die('Direct Access to this location is not allowed.'); ?>
<body style="font-size: 12pt; text-align: left; margin-left: 72pt;">
<?php
foreach ($row as $key => $value) {
    $title = ucwords(str_replace('_',' ', $key));
    $val = $value;
    if ($key == 'survey_id') { $survey_id = strtoupper(substr("00000".dechex($value*19),-5)); }
    if ($key == 'first_name') { $first_name = $value; }
    if ($key == 'last_name')  { $last_name = $value; }
    if ($key == 'mailing_address') { $mailing_address = $value; }
    if ($key == 'mailing_city') { $mailing_city = $value; }
    if ($key == 'mailing_state') { $mailing_state = state_abbr($value); }
    if ($key == 'mailing_zip') { $mailing_zip = $value; }
}

?>
<h2 style="text-align: center;">
	<input alt="" src="https://www.northriverboats.com/images/nrboats-grn.png" style="width: 443px; height: 130px;" type="image" />
</h2>
<br />
<?= $first_name ?> <?= $last_name ?><br />
<?= $mailing_address ?><br />
<?= $mailing_city ?>, <?= $mailing_state ?> <?= $mailing_zip ?><br />
<p>Re: Customer Satisfaction Survey</p>
<p>Dear <?= $first_name . " " . $last_name ?>,</p>

<h1>Congratulations on your new boat purchase and thank you for choosing North River Boats!</h1>

<p>North River Boats prides itself on quality products and excellent customer service. As a valued customer your input is important, as it ensures we are meeting and providing the highest level of customer service in our business.</p>
<p>The Customer Satisfaction Survey is one way we seek feedback from the people who know and love our boats. We would appreciate if you could take the time to fill out this brief web survey.</p>
<table border="0">
  <tbody>
    <tr>
      <td>
        <img alt="" src="https://www.northriverboats.com/2caps.jpg" style="width: 200px; height: 200px;" /></td>
      <td align="center">
        <h2>WE WILL SEND YOU TWO FREE NORTH RIVER HATS FOR COMPLETING THIS FORM</h2>
      </td>
    </tr>
  </tbody>
</table>
<p>Please go to <a href="https://www.northriverboats.com/survey?id=<?php echo urlencode($survey_id) ?>">https://www.northriverboats.com/survey</a>. Enter your survey number: <span style="color: red;font-weight: bold;"><?php echo $survey_id ?></span></p>
<p>Thank you from all of us at North River Boats!</p>
</body>
