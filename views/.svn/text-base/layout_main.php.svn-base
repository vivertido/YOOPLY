<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Yoop.ly</title>
	
	<link href='https://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
	 
	<link href='https://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css'>
	
		 
	
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="//code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css" />

	<link rel="stylesheet" type="text/css" href="/js/jquery.mobile.simpledialog.min.css" />
	<link rel="stylesheet" href="/themes/Yoopl1.css" />
	<link rel="stylesheet" href="/themes/custom.css" />
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="//code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
	<script type="text/javascript" src="/js/jquery.mobile.simpledialog2.min.js"></script>
	<script src="/js/jQuery.succinct.js"></script>

	<script>
	$(function() {
		$.mobile.ajaxEnabled = false;
	});
	</script>
	<style type="text/css">
	
.error
{
	font-weight: bold; border: 2px solid red; background-color: #FFB0B0; padding: 5px; text-align:center;
}

.success
{
	font-weight: bold; border: 2px solid green; background-color: #B0FFB0; padding: 5px; text-align:center;
}	

   /*admin page styles*/
#listAnchorText{
font-family: 'Dosis';

font-weight:normal;

}
p.listViewSubtext{
	font-family: 'Dosis';

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
#mainHeader{

height: 50px; 

}
@media only screen and (max-device-width: 480px) {

	#mainHeader{

	height: 50px; 
	margin-bottom:-20px;

	}

}



#notificationContainer 
{
background-color: #fff;
border: 1px solid rgba(100, 100, 100, .4);
-webkit-box-shadow: 0 3px 8px rgba(0, 0, 0, .25);
overflow: visible;
position: absolute;
top: 40px;
right:0px;
margin-left: -170px;
width: 400px;
z-index: 30;
display: none; // Enable this after jquery implementation 
}
// Popup Arrow
#notificationContainer:before {
content: '';
display: block;
position: absolute;
width: 0;
height: 0;
color: transparent;
border: 10px solid black;
border-color: transparent transparent white;
margin-top: -20px;
margin-left: 188px;
}
#notificationTitle
{
font-weight: bold;
padding: 8px;
font-size: 13px;
background-color: #ffffff;
position: relative;
z-index: 1000;
width: 384px;
border-bottom: 1px solid #dddddd;
}
#notificationsBody
{
padding: 0px 0px 0px 0px !important;
min-height:300px;
}
#notificationFooter
{
background-color: #e9eaed;
text-align: center;
font-weight: bold;
padding: 8px;
font-size: 12px;
border-top: 1px solid #dddddd;
}
.notification_time{

	position: absolute;
  	top: 2.9em;
}

	</style>

	
</head>
<body >


<div data-role="page" id="page">

		<div id="notificationContainer">
<script type="text/javascript">
$(function() {

	$(document).on('click', "#notificationLink", function()
	{
		$("#notificationsBody ul").load('/api/notifications', function() { $("#notificationsBody ul").listview('refresh'); });
		//$("#notificationsBody ul").listview('refresh');
		$('.notificationCount').text('0');
		$('#notificationLink').css('background-color', '#ffffff');
		$('#notificationLink').css('color', '#2f3e46');
		$("#notificationContainer").fadeToggle(300);
		$("#notification_count").fadeOut("slow");
		return false;
	});

	//Document Click hiding the popup 
	$(document).click(function()
	{
		$("#notificationContainer").hide();
	});

	$(document).on('click', '._removenotification', function(event)
	{
		var group = $(this).parents('li').attr('data-group');
		var id = $(this).parents('li').attr('data-id');

		$.ajax('/api/removenotification/'+id);

		if($('li[data-group="'+group+'"').size() == 2)
		{
			$('li[data-group="'+group+'"').fadeOut('slow', function() 
			{ 
				$(this).remove(); 

				$("#notificationsBody ul").listview('refresh');
				
				if($('li[data-group]').size() == 0)
				{
					$('#nonotification').show();
				}
			});
		}
		else
		{
			$(this).parents('li').fadeOut('slow', function() 
			{ 	
				$(this).remove(); 

				$("#notificationsBody ul").listview('refresh');
			});
		}

		event.stopPropagation();

	});

});
</script>			
			<div id="notificationTitle">Notifications</div>
			<div id="notificationsBody" class="notifications"><div id="nonotification" style="display:none; padding:10px; text-align:center">
You have no notifications right now.
</div><ul data-role="listview" data-split-icon="delete" data-split-theme="d"></ul></div>
			<div id="notificationFooter"><a href="/notifications" data-ajax="false">See All</a></div>
		</div>
	<?= isset($layout_before_header) ? $layout_before_header : '' ?>
	<div id="mainHeader" data-role="header"  >
		<a data-role="none" class="ui-btn-left" data-ajax="false" style="float:left;display:inline;" href="/<?php switch($this->session->userdata('role')):
case 's': ?>student<?php break;
case 't': ?>teacher<?php break;
case 'a': ?>admin<?php break;
endswitch; ?>"><img src="/images/Y_logo2.png" style="width:35px;height:auto; border-color:#f0f0f0; border-style:solid; border-width:2px; border-radius:5px;"/></a>
		<h1><?= isset($title_for_layout) ? $title_for_layout : 'Yooply' ?></h1>

		<?php if($this->session->userdata('userid')): ?>
		<div data-role="controlgroup" data-type="horizontal" class="ui-btn-right">
			<a href="#" id="notificationLink" data-role="button"><span class="notificationCount"><?= $notificationcount ?></span></a>
			<a href="#popupMenu" data-rel="popup" data-role="button" data-inline="true"  data-transition="fade">Menu</a>
		</div>
		<div data-role="popup" id="popupMenu" data-overlay-theme="b">
			<ul data-role="listview" data-inset="true" style="width:180px;" data-theme="b">
				<?php if($this->session->userdata('isteacher') || $this->session->userdata('isadmin')): ?>
				<li><a href="/switchrole" data-ajax="false">Switch to <?php switch($this->session->userdata('role')):
					case 't': ?>Admin<?php break;
					case 'a': ?>Teacher<?php break;
				endswitch; ?></a></li>
				<?php endif; ?>
				<?php if($this->session->userdata('role') == 'a'): ?>
				<li><a href="/admin/settings" data-ajax="false">School Settings</a></li>
				<?php endif; ?>
				<li><a href="/account" data-ajax="false">Account Settings</a></li>
				<li><a href="/logout" data-ajax="false">Logout</a></li>
			</ul>
		</div>
		<?php endif; ?>



<div data-role="popup" id="popupMenu" data-overlay-theme="b">
	<ul data-role="listview" data-inset="true" style="width:180px;" data-theme="b">
		<?php if($this->session->userdata('isteacher') || $this->session->userdata('isadmin')): ?>
		<li><a href="/switchrole" data-icon="gear" class="ui-btn-right" data-ajax="false"><?php switch($this->session->userdata('role')):
			case 't': ?>Admin<?php break;
			case 'a': ?>Teacher<?php break;
		endswitch; ?></a></li>
		<?php endif; ?>
		<?php if($this->session->userdata('role') == 'a' || $this->session->userdata('role') == 's'): ?>
		<li><a href="/admin/settings" data-icon="gear" data-ajax="false">Settings</a></li>
		<?php endif; ?>
		<li><a href="/logout">Logout</a></li>
	</ul>
</div>
	</div><!-- /header -->

	<div data-role="content">
  <?= $content_for_layout ?>
	</div><!-- /content -->

<?php if($this->session->userdata('role')): ?>
<div data-role="footer" data-id="foo1" data-position="fixed" id="footer">
	<div data-role="navbar">
		<ul>
<?php switch($this->session->userdata('role')):
case 's': // Student
?>
			<li><a href="/student/shoutouts" data-icon="grid" data-ajax="false">Shoutouts</a></li>
			<?php if(strpos($school_features->detentions, $this->session->userdata('role')) !== false): ?>
				<li><a href="/student/mydetentions" data-icon="alert" data-ajax="false"><?= htmlentities($labels->detentions) ?></a></li>
			<?php else: ?>
				<li><a href="/student/interventions" data-icon="alert" data-ajax="false">Interventions</a></li>
			<?php endif; ?>
<?php
break;
case 't': // Teacher
?>
			<li><a style="font-family: 'Dosis'" href="/teacher/students" data-icon="grid" data-ajax="false">Students</a></li>
			<li><a style="font-family: 'Dosis'" href="/report" data-icon="alert" data-ajax="false">Reports</a></li>
			<li><a style="font-family: 'Dosis'" href="/teacher/quickentry" data-icon="grid" data-ajax="false">Quick</a></li>
			<!--<li><a href="#" data-icon="gear" data-ajax="false">Settings</a></li>-->
<?php
break;
case 'a': // Admin
?>
			<li><a href="/admin/students" data-icon="grid" data-ajax="false">Students</a></li>
			<?php if(strpos($school_features->detentions, $this->session->userdata('role')) !== false): ?>
				<li><a href="/detention/mystudents" data-icon="alert" data-ajax="false"><?= htmlentities($labels->detentions) ?></a></li>
			<?php else: ?>
				<li><a href="/intervention" data-icon="alert" data-ajax="false">Interventions</a></li>
			<?php endif; ?>
			<li><a style="font-family: 'Dosis'" href="/teacher/quickentry" data-icon="grid" data-ajax="false">Quick</a></li>
<?php
break;
endswitch;
?>
		</ul>
	</div><!-- /navbar -->
</div><!-- /footer -->
<?php endif; ?>
</div><!-- /page -->

</body>

<script>

$(document).ready(function(){

var notifCount = $("#notificationLink").text();

if (notifCount == "0") {

 console.log(notifCount);


} else {

$('#notificationLink').css("background-color" , "orange");
$('#notificationLink').css("color" , "#ffffff");


};

      
});

</script>

</html>