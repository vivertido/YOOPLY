<script>
$(function()
{
	$('#sortable').on('click', '._remove', function()
	{
		$(this).parents('li').find('*').remove();
	});
});
</script>
<script type="text/javascript" src="/js/jquery.ui.touch-punch.js"></script>
<?php 
function setEditTitle($title){
?>
	<div id="show-if-mobile" class="ui-field-contain">
        <fieldset id="switch-mode" data-role="controlgroup" data-type="horizontal" data-mini="true">
            <legend><?php echo $title ?></legend>
            <input type="radio" name="radio-orientation" id="isVertical" value="isVertical" checked="checked">
            <label for="isVertical">Edit</label>
            <input type="radio" name="radio-orientation" id="isHorizontal" value="isHorizontal">
            <label for="isHorizontal">Organize</label>
        </fieldset>
    </div>
<?php
}
?>
<?php
function row($title)
{
?>
<li class="ui-state-normal sortable-item">
  <span class="handle"> 
  </span><input class="text-enter" type="text" name="detention[]" value="<?= htmlentities($title) ?>" /> 
  <span class="kill-item ui-li-aside">
  <a class="_remove ui-link ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-inline ui-shadow ui-corner-all" data-role="button" data-icon="delete" data-inline="true" role="button">
  </a>
  </span>   
</li>
<?php	
}
?>
<style>
#detentions .ui-grid-a:first-child .tools ._moveup, #detentions .ui-grid-a:last-child .tools ._movedown 
{
	visibility:hidden;
}
.draggable{

	background-color:red;
	width:80%;
}
.ui-input-text{

width:90%;
margin-left:5px;

}

.sortable-item{

	cursor:move;
	border-radius:5px;
	position:relative;
	 list-style-type: none;

}

.handle{

	background: #f6f6f4;
	width:18px;
	height:35px ;
	position: absolute;
	top:0;
	left: -10px;
	border-style: solid;
	border-color:#33ccff;
	border-width:1px;
	background-image:url('/images/handle.svg');
}
.kill-item{

   
  position: absolute;
  margin-left: 92%;
  margin-top: -50px;
}
</style>
<script>
$().ready(function(){

	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
  
		 $('.handle').hide();
		 $('#sortable').sortable({disabled: true});
    	 $( "#sortable" ).disableSelection();



	} else {
 
		 $('#show-if-mobile').hide();
		 $('.handle').show();
		 $('#sortable').sortable({disabled: false});
         $( "#sortable" ).disableSelection();
	  }

	$('._adddetention').on('click', function()
	{
		$('#sortable').append($('#template').html());
		$('#page').trigger("refresh");
	});

	$('#switch-mode').on('change', function(){

		if ($('#switch-mode input[type="radio"]').first().is(':checked')    ){

			  
			 $('#sortable').sortable("disable");
			 $('.handle').hide();


		} else {
			  $('#sortable').sortable("option", "disabled", false );
			  $('.handle').show();

			   
		}

	});

	 
});
</script>

<form action="/admin/settings/detentions" method="POST" data-ajax="false">
<h2>Edit detention settings</h2>
<p>Set reasons for detentions. These reasons will only appear if you have detentions enabled. They do not have to be the same as the referral or negative behavior reasons.</p>
<hr/>
Name of detention:<br />
<div class="ui-grid-a">
	<div class="ui-block-a">Singular (label for one detention):<br />
		<input type="text" name="detentionname" value="<?= htmlentities($labels->detention) ?>" />
	</div>
	<div class="ui-block-b">Plural (label for two or more detentions):<br />
		<input type="text" name="detentionsname" value="<?= htmlentities($labels->detentions) ?>" />
	</div>
</div>
<hr />
Name of unit:<br />
<div class="ui-grid-a">
	<div class="ui-block-a">Singular (label for one detention unit):<br />
		<input type="text" name="detentionunitname" value="<?= htmlentities($labels->detentionunit) ?>" />
	</div>
	<div class="ui-block-b">Plural (label for two or more detention units):<br />
		<input type="text" name="detentionunitsname" value="<?= htmlentities($labels->detentionunits) ?>" />
	</div>
</div>
<hr />

<div> <?php setEditTitle("Detentions")  ?>  </div>
<div id="detentions">
<ul id="sortable">
<?php foreach($detentions as $option): row($option); endforeach; ?>
</ul>
</div>

<input type="button" class="_adddetention" value="Add another reason" data-inline="true" />

<input type="submit" name="submit" value="Save Changes" data-theme="c" />
</form>

<div id="template" style="display:none">
	<?php row(''); ?>
</div>