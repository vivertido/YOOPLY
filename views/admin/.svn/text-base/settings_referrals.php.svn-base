<style>
.ui-input-text
{
	border-style: none;
}
</style>
<script>
$(function() {
                $( ".sortable" ).sortable();
                $( ".sortable" ).disableSelection();
        });
</script>
<h2>Edit Referral Reasons</h2>
<p>In the fields below, enter the possible reasons why a student is sent to the office for a referral.</p>

<form action="/admin/settings/referrals" method="POST" data-ajax="false">
<?php inflate_edit_form($settings->questions, 'f', true, $settings->keys); ?>
<input type="submit" name="submit" value="Save Changes" data-theme="c"/>
</form>