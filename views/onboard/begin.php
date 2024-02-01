Welcome to Yoop.ly

<?php if(isset($error)): ?><div class="<?php
switch($error):
case 'invalidcode':
?>error">The verification code you entered is not valid<?php
break;
endswitch;
?></div><?php
endif;
?>
<form action="/onboard/begin/<?= $invitecode ?>" method="POST" data-ajax="false">
	Please enter the invite verification code provided to you by your Yooply representative.
	<input type="text" name="verificationcode" />

	<input type="submit" name="submit" value="Next" />
</form>