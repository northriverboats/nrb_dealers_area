<?php isset($row) or die('Direct Access to this location is not allowed.'); ?>
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
Dear <?= $first_name . " " . $last_name ?>,

Congratulations on your new boat purchase and thank you for choosing North River Boats!

North River Boats prides itself on quality products and excellent customer service. As a valued customer your input is important, as it ensures we are meeting and providing the highest level of customer service in our business.

The Customer Satisfaction Survey is one way we seek feedback from the people who know and love our boats. We would appreciate if you could take the time to fill out this brief web survey.

WE WILL SEND YOU TWO FREE NORTH RIVER HATS FOR COMPLETING THIS FORM

Please go to http://www.northriverboats.com/survey. Enter your survey number: <?php echo $survey_id ?>
