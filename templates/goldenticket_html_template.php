<?php isset($row) or die('Direct Access to this location is not allowed.'); ?>
<body style="font-size: 12pt; text-align: left; margin-left: 8pt;">
<a href="https://www.northriverboats.com/registration/#/<?= str_replace(' ', '', $row['hull_serial_number']) ?>/<?= $row['pin'] ?>">
<img  src='cid:logoimg' alt='OnlyPict' border='0' />
</a>

<p>&nbsp;</p>

<p>Just in case the image above can't be displayed:</p>

<p>&nbsp;</p>

<h3>Congratulations on your new boat purchase and thank you for choosing North River Boats</h3>

<h3 style='color:red'>Receive Two Free Hats and 25% off NRB Gear Online</h3>

<h3>By clicking <a href="https://www.northriverboats.com/registration/#/<?= str_replace(' ', '', $row['hull_serial_number']) ?>/<?= $row['pin'] ?>">HERE</a></h3>

<p>North River Boats prides itself on quality products and excellent customer servie. As a valued customer your input is important, as it ensures we are meeting and provind the highest level of customer service in our business.</p>

<p>The Customer Satisfaction Survey is one way we seek feedback from the people who know and love our boats. We would apprectiate if you could take the time to fill out this brief web survey.</p>

<p>Thank you from all of us at North River Boats!</p>
</body>
