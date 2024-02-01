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
 <span class="grippy-block handle" >  </span><input class="text-enter" type="text" name="consequence[]" value="<?= htmlentities($title) ?>" />
<span class="kill-item ui-li-aside">
  <a class="_remove ui-link ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-inline ui-shadow ui-corner-all" data-role="button" data-icon="delete" data-inline="true" role="button">
  </a>
  </span>  
 <!--
<li class="ui-state-default">
	
	<div class="draggable" style="padding:0px">
		<input class="text-enter" type="text" name="consequence[]" value="<?= htmlentities($title) ?>" />
		<div class="handle"></div>
	</div>
	
-->	

</li>
 
<!--
<div class="ui-grid-a">
	<div class="ui-block-a draggable" style="padding:0px"><input class="text-enter" type="text" name="consequence[]" value="<?= htmlentities($title) ?>" /><div class="handle"> OOO</div></div>
	
	<div class="ui-block-b tools" style="padding:0px"></div>-->

<?php	
}
?>

<style>
#consequences .ui-grid-a:first-child .tools ._moveup, #consequences .ui-grid-a:last-child .tools ._movedown 
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

</script>

<script>
$().ready(function(){


  //check to see if mobile device to hide the UI for switch
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



	$('#switch-mode').on('change', function(){

		if ($('#switch-mode input[type="radio"]').first().is(':checked')    ){

			  
			 $('#sortable').sortable("disable");
			 $('.handle').hide();


		} else {
			  $('#sortable').sortable("option", "disabled", false );
			  $('.handle').show();

			   
		}

	});

	//check to see which is checked...

	

	$('._addconsequence').on('click', function()
	{
		$('#sortable').append($('#template').html());
		 $('#page').trigger("refresh");
	});

	 


    /*lets add the jquerymobile touch drag stuff here */

    

});
</script>

<form action="/admin/settings/consequences" method="POST" data-ajax="false">
<h2>Edit Consequences</h2>
<p>Use this form to edit what consequences your school wants to assign and track, irrespective of the trigger behavior. Teachers and administrators will have the option of assigning these at different points, depending on your settings. Sort and arrange by dragging.</p>
<hr/>
<div> <?php setEditTitle("Consequence")  ?>  </div>
<div id="consequences">
<ul id="sortable">

	<?php $i = 1; foreach($consequences as $consequence): row($consequence); endforeach; ?>

</ul>
</div>

<input type="button" class="_addconsequence" value="Add another consequence" data-inline="true" />

<input type="submit" name="submit" value="Save Changes" data-theme="c"/>
</form>

<div id="template" style="display:none">
	<?php row(' '); ?>
</div>