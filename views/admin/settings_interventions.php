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
 <span class="grippy-block handle" >  </span><input class="text-enter" type="text" name="intervention[]" value="<?= htmlentities($title) ?>" />
 <span class="kill-item ui-li-aside">
  <a class="_remove ui-link ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-inline ui-shadow ui-corner-all" data-role="button" data-icon="delete" data-inline="true" role="button">
  </a>
  </span>  </li>

<?php	
}
?>
<style>
#interventions .ui-grid-a:first-child .tools ._moveup, #interventions .ui-grid-a:last-child .tools ._movedown 
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

	$('._addintervention').on('click', function()
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

<form action="/admin/settings/interventions" method="POST" data-ajax="false">
<h2>Edit Intervention reasons</h2>
<p>Interventions are a way to track behaviors, by both teacher and student, that do not necessarily have consequences attached. These can be used as a first step before other more punitive measures are taken. They can also be used to track custom interactions, 'red flags', or notes about student choices that may be important to keep track of. 
<hr/>
<div> <?php setEditTitle("Interventions")  ?>  </div>
<div id="interventions">
<ul id="sortable">
	<?php foreach($interventions as $option): row($option); endforeach; ?>
	</ul>
</div>

<input type="button" class="_addintervention" value="Add another intervention" data-inline="true" />

<input type="submit" name="submit" value="Save Changes" data-theme="c" />
</form>

<div id="template" style="display:none">
	<?php row(''); ?>
</div>