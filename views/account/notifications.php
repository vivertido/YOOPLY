<? $this->load->view('account/menu', array('tab' => 'notifications')) ?>
You can choose to receive notifications by SMS, email, or both. 

<form action="/account/notifications" method="POST" data-ajax="false">
	<input type="checkbox" name="sms" value="1" id="sms"<?= !empty($user->phone) && (!isset($preferences->sms) || $preferences->sms == '1') ? ' checked="checked"' : '' ?><?= empty($user->phone) ? ' disabled="disabled"' : '' ?> /><label for="sms">SMS<?php if(empty($user->phone)): ?> <br /><small>You must enter a phone number in Personal Information</small><?php endif; ?></label>
	<input type="checkbox" name="email" value="1" id="email"<?= !isset($preferences->email) || $preferences->email == '1' ? ' checked="checked"' : '' ?> /><label for="email">Email</label>

	<input type="submit" name="submit" value="Save Changes" />
</form>
