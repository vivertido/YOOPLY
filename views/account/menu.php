<?php $menu = array(
	'personal' => 'Personal Information', 
	'avatar' => 'Avatar', 
	'notifications' => 'Notification Preferences'
);

if($this->session->userdata('role') != 's'): unset($menu['avatar']); endif; 

if(isset($menushowlogin))
{
	$menu['login'] = 'Login';
}
?>
<div>
	<a href="#accountmenu" data-icon="bars" data-rel="popup" data-mini="true" data-role="button" data-inline="true" data-transition="fade"><?= $menu[$tab] ?></a>
	<div data-role="popup" id="accountmenu" data-overlay-theme="b">
		<ul data-role="listview" data-inset="true" style="width:210px;" data-theme="b">
			<?php foreach($menu as $k=>$v): if($k == $tab): continue; endif; ?>
			<li><a href="/account/<?= $k ?>" data-ajax="false"><?= $v ?></a></li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>
<br />