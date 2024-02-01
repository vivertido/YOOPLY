<style>

.avatar.active
{
	border: 4px  solid #603813;
	 
}
#container{

margin-right:20px;
padding: 20px;
 

}
 
</style>
<? $this->load->view('account/menu', array('tab' => 'avatar')) ?>
<div id="container" style="background-color:#fff; margin:20px; padding:10px">

<img id="currentavatar" src=" /images/<?= $student->profileimage ?>"  style="float:left; margin-right:10px; width:60px; height:60px"><h3 style="font-weight:normal">Choose your avatar </h3> <hr>
<?php foreach($avatars as $avatar): ?>
<img class="avatar<?php if($student->profileimage == $avatar.'.png'): ?> active<?php endif; ?>" style="width:60px; height:60px" data-img="<?= $avatar ?>" src="/images/<?= $avatar ?>.png" />
<?php endforeach; ?>
</div>
<form action="/account/avatar" method="POST" data-ajax="false">
	<input type="hidden" name="avatar" id="avatar" value="<?= strstr($student->profileimage, '.', true) ?>" />

	<input type="submit" data-theme="c" name="submit" value="Save Changes" />
</form>

<script>
$().ready(function()
{
	$('.avatar').click(function()
	{
		$('#avatar').val($(this).attr('data-img'));
		$('.avatar').removeClass('active');
		$(this).addClass('active');
		$('#currentavatar').attr('src', $(this).attr('src'));
	});
	
});
</script>
