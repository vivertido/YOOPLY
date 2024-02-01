<style>

#myAlerts{
background-color:rgba(219,87,5,0.2);
height:100%;
border-style:solid;
border-width:1px;
border-radius:5px;
border-color:#2489ce;
padding-bottom:10px;
}

</style>

<?php if(!empty($pendingreferrals)): ?>
<div id="myAlerts">
	<img src="/images/caution.png" style="display:inline;margin-left:5px;margin-top:5px;"><h3 style="font-family:'Dosis'; display:inline; margin-left:15px; ">Alerts:</h3>
	<div id="recent-activity" style="padding-left:20px;padding-right:20px;"> 	
		<ul data-role="listview" data-inset="true">
			<?php foreach($pendingreferrals as $referral): ?>
		    <li><a href="/referral/edit/<?= $referral->referralid ?>"><img src="/images/document.png" class="ui-li-icon ui-corner-none">Pending Referral: <?= $referral->firstname ?> <?= $referral->lastname ?></a></li>
		   	<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
<?php 
$feature_required = array(
	'/detention/mystudents' => $features->detentions,
	'/teacher/interventions' => $features->interventions,
	'/demerit/mine' => $features->demerits,
	'/referral/mine' => $features->referrals,
	'/teacher/reports' => $features->referrals.$features->demerits
); 
?>

<ul data-role="listview" data-inset="true">
	<?php foreach($menu->menu as $k=>$v): 
		if(array_key_exists($k, $feature_required) && strpos($feature_required[$k], $this->session->userdata('role')) === false): 
			continue; 
		endif; ?>
	<li><a href="<?= $k ?>" data-ajax="false"><?= $v ?></a></li>
	<?php endforeach; ?>
</ul>