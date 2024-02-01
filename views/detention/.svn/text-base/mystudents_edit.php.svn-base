<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Yoop.ly</title>
	
	<link href='https://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="/themes/jquery.mobile-1.3.0-beta.1.css" />

	<link rel="stylesheet" type="text/css" href="/js/jquery.mobile.simpledialog.min.css" />
	<link rel="stylesheet" href="/themes/Yoopl1.css" />
	<link rel="stylesheet" href="/themes/custom.css" />
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="/js/jquery.mobile-1.3.0-beta.1.js"></script>
	<script type="text/javascript" src="/js/jquery.mobile.simpledialog2.min.js"></script>

	<style type="text/css">

   /*admin page styles*/
#listAnchorText{
font-family: 'News Cycle';

font-weight:normal;

}
p.listViewSubtext{
	font-family: 'News Cycle';

font-weight:normal;
font-size:10px;



}

p.listSubtext{
  margin-left:15px; margin-right:15px;
}

p{

	font-family: 'News Cycle';

font-weight:normal;
}

th{ text-align:left;}
	</style>
</head>
<body>


<div data-role="page" style="background:none; background-color: white">
	<div data-role="content">
<?php if(empty($detentions)): ?>
Yay! None of your students have detention today!
<?php else: ?>
<table width="100%" border="1" cellpadding="5" cellspacing="0">
		<?php
		$header = '';
		foreach($detentions as $detention):
			$h = addOrdinalNumberSuffix($detention->grade).' Grade';
			if($header != $h) { $header = $h;
?>
		<tr>
			<td colspan="2"><b style="font-size:11pt"><?= $header ?></b></td>
		</tr>
		<tr>
			<th>Name</th>
			<th>Minutes to date</th>
		</tr>		
<?php							}
			?>
		<tr>
			<td><?= $detention->lastname ?>, <?= $detention->firstname ?></td>
			<td><input type='textfield' value="<?= $detention->total  ?>"/> </td>
		</tr>			
		<?php endforeach; ?>
</table>
<div style="padding-top: 10px; float:right; font-size: 8pt">Active on: <?= date('m/d/y g:i a') ?></div>
</div>
<?php endif; ?>
	</div><!-- /content -->
</div><!-- /page -->

</body>

</html>