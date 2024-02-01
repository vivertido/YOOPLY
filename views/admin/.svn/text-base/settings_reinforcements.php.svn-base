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
<?php $quantitytype = isset($reinforcements->quantitytype) ? $reinforcements->quantitytype : 'fixed'; ?>
<style>
#reinforcements .ui-grid-a:first-child .tools ._moveup, #reinforcements .ui-grid-a:last-child .tools ._movedown 
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
.grippy-block{

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


	$('._addreinforcement').on('click', function()
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

	

	$('#quantitytype').on('change', function()
	{
		$('.quantityoption').hide();
		$('#quantityoption_'+$(this).val()).show();
	});	
});
</script>

<form action="/admin/settings/reinforcements" method="POST" data-ajax="false">
<h2>Edit Positive Reinforcement</h2>
<p>Use this form to change the name of positive reinforcement, or reward, and specify what behaviors can be rewarded.</p><p>These points or 'merits' can be exchanged for your own custom rewards.</p>
<hr>

Name of reinforcement:<br />
<div class="ui-grid-a">
	<div class="ui-block-a">Singular (label for one positive reinforcement):<br />
		<input type="text" name="reinforcementname" value="<?= htmlentities($labels->reinforcement) ?>" />
	</div>
	<div class="ui-block-b">Plural (label for two or more positive reinforcements):<br />
		<input type="text" name="reinforcementsname" value="<?= htmlentities($labels->reinforcements) ?>" />
	</div>
</div>


Award:<br />
<?php $types = array(
		'fixed' => 'Fixed number',
		'range' => 'A range',
		'number' => 'Any quantity'
); ?>
<select name="quantitytype" id="quantitytype">
<?php foreach($types as $k=>$v): ?>
	<option value="<?= $k ?>" <?= $quantitytype == $k ? ' selected="selected"' : ''?>><?= $v ?></option>
<?php endforeach; ?>
</select>

<div class="quantityoption" id="quantityoption_fixed" style="padding-left: 10%<?= $quantitytype == 'fixed' ? '' : ';display:none' ?>">
	Quantity: <input type="text" placeholder="quantity to award" name="awardamount" value="<?= isset($reinforcements->awardamount) ? htmlentities($reinforcements->awardamount) : '' ?>" />
</div>

<div class="quantityoption" id="quantityoption_range" style="padding-left: 10%;<?= $quantitytype == 'range' ? '' : ';display:none' ?>">
	Minimum: <input type="text" placeholder="minimum number" name="awardamountmin" value="<?= isset($reinforcements->awardamountmin) ? htmlentities($reinforcements->awardamountmin) : '' ?>" />
	Maximum: <input type="text" placeholder="maximum number" name="awardamountmax" value="<?= isset($reinforcements->awardamountmax) ? htmlentities($reinforcements->awardamountmax) : ''  ?>" />
</div>


 

<!--Name of award:<br />-->
<input type="hidden" name="awardname" value="<?= htmlentities($reinforcements->awardlabel) ?>" />

<h3>Reinforcements</h3>
<div> <?php setEditTitle("Reinforcements")  ?>  </div>
<div id="reinforcements">
<ul id="sortable">
<?php $i = 1; foreach($reinforcements->options as $option): ?>
	
<li class="ui-state-normal sortable-item">
 <span class="grippy-block handle" >  </span><input class="text-enter" type="text" name="reinforcement[]" value="<?= htmlentities($option) ?>" />
<span class="kill-item ui-li-aside">
  <a class="_remove ui-link ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-inline ui-shadow ui-corner-all" data-role="button" data-icon="delete" data-inline="true" role="button">
  </a>
  </span>  
  </li>
<?php $i++; endforeach; ?>
</ul>
</div>

<input type="button" class="_addreinforcement" value="Add another reinforcement" data-inline="true" />

<input type="submit" name="submit" value="Save Changes" data-theme="c"/>
</form>

<div id="template" style="display:none">
		
<li class="ui-state-normal sortable-item">
 <span class="grippy-block handle" >  </span><input class="text-enter" type="text" name="reinforcement[]" value=" " /></li>

</div>