<?php
function inflate_form($elements, $prefix = 'f', $read_only = false, $responses = false)
{
	foreach($elements as $field):
		$response = false;

		if($responses !== false)
		{
			foreach($responses as $r)
			{
				if($r->id == $field->id)
				{
					$response = $r->value;
					break;
				}
			}
		}

		switch($field->type):
			case 'instruction': ?>
<p style="font-weight:normal"><?= $field->text ?></p>
<?php
			break;
			case 'text': ?>
<label for="basic"><?= $field->label ?>:</label>
<input type="text" name="<?= $prefix ?><?= $field->id ?>" id="basic" value="<?= $response !== false ? htmlentities($response) : '' ?>" style="color: #603813; font-weight:normal; margin:20px" placeholder="<?= $field->placeholder ?>"<?= $read_only ? ' readonly="readonly"' : '' ?> />
<?php
			break;
			case 'date': ?>
<h3><?= $field->label ?></h3>
<input type="date" name="<?= $prefix ?><?= $field->id ?>" value="<?= $response !== false ? htmlentities($response) : date('Y-m-d') ?>" placeholder="<?= $field->placeholder ?>"<?= $read_only ? ' readonly="readonly"' : '' ?> />
<?php
			break;
			case 'time': ?>
<h3><?= $field->label ?></h3>
<input type="time" name="<?= $prefix ?><?= $field->id ?>" value="<?= $response !== false ? htmlentities($response) : date('H:i') ?>" placeholder="<?= $field->placeholder ?>"<?= $read_only ? ' readonly="readonly"' : '' ?> />
<?php
			break;
			case 'datetime': ?>
<h3><?= $field->label ?></h3>
<div class="ui-grid-a">
	<div class="ui-block-a"><input type="date" name="<?= $prefix ?><?= $field->id ?>d" value="<?= $response !== false ? htmlentities(date('Y-m-d', strtotime($response))) : date('Y-m-d') ?>" placeholder="<?= $field->placeholder ?>"<?= $read_only ? ' readonly="readonly"' : '' ?> /></div>
	<div class="ui-block-b"><input type="time" name="<?= $prefix ?><?= $field->id ?>t" value="<?= $response !== false ? htmlentities(date('H:i', strtotime($response))) : date('H:i') ?>" placeholder="<?= $field->placeholder ?>"<?= $read_only ? ' readonly="readonly"' : '' ?> /></div>
</div>
<?php
			break;						
			case 'textarea': ?>
<h3 class="aHeading3"><?= $field->label ?></h3>
<br>
<textarea name="<?= $prefix ?><?= $field->id ?>" style="color: #603813;font-weight:normal" placeholder="<?= isset($field->placeholder) ? $field->placeholder : '' ?>"<?= $read_only ? ' readonly="readonly"' : '' ?>><?= $response !== false ? htmlentities($response) : '' ?></textarea>
<?php
			break;
			case 'multicheckbox': ?>
<?php if(isset($field->label)): ?><h3  class="aHeading3"><?= $field->label ?></h3><?php endif; ?>
<?php $i=0; foreach($field->options as $option): $random_id = md5($option.rand()); ?>
<input type="checkbox"     name="<?= $prefix ?><?= $field->id ?>[]" value="<?= htmlentities($option) ?>" id="c<?= $random_id ?>"  class="custom"<?= $read_only ? ' readonly="readonly"' : '' ?><?= $response !== false && in_array($option, $response) ? ' checked="checked"' : ''?> />
<label for="c<?= $random_id ?>" ><?= $option ?></label>
<?php $i++; endforeach;
if(isset($field->otherlabel))
{
	$random_id = md5($option.rand());
?>
 <input type="checkbox" name="<?= $prefix ?><?= $field->id ?>other"  value="<?= htmlentities($field->otherlabel) ?>" id="c<?= $random_id ?>" class="custom"<?= $read_only ? ' readonly="readonly"' : '' ?> />
<label for="c<?= $random_id ?>"><?= $field->otherlabel ?></label>
<input type="text"  name="<?= $prefix ?><?= $field->id ?>otherlabel"<?= $read_only ? ' readonly="readonly"' : '' ?> />
 
<?php
}
			break;
			case 'select':
?>
				<?php if(isset($field->label)): ?><h3><?= $field->label ?></h3><?php endif; ?>
				<select name="<?= $prefix ?><?= $field->id ?>" id="f<?= $field->id ?>"<?= $read_only ? ' readonly="readonly"' : '' ?>>
<?php foreach($field->options as $option): ?>
<option value="<?= htmlentities($option) ?>"<?= $response !== false && $response == $option ? ' selected="selected"' : ''?>><?= $option ?></option>
<?php endforeach; ?>
				</select>
<?php
			break;
			case 'collapsible': ?>
<div data-role="collapsible" data-content-theme="c">
	<?php if(isset($field->label) && !empty($field->label)): ?>
	<h3><?= $field->label ?></h3>
	<?php endif; ?>

	<?php
		if(isset($field->elements)):
			inflate_form($field->elements, $prefix);
		endif;
	?>
</div>
<?php
			break;
			case 'controlgroup': ?>
<fieldset data-role="controlgroup" data-iconpos="right">
	<legend>Pick all the reasons you think apply to you:</legend>
	<?php
		if(isset($field->elements)):
			inflate_form($field->elements, $prefix);
		endif;
	?>
</fieldset>
<?php
			break;
			case 'personsearch': ?>
<script>
$().ready(function() {
	$(".selectperson").autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "/api/findconnected/"+request.term,
				dataType: "json",
				success: function( data ) {
					response($.map( data.names, function( item ) {
						return {
							label: item.name,
							value: item.name,
						}
					}));
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});

<?php if(isset($field->multiple) && $field->multiple == 'true'): ?>
	$('._addanotherperson').on('click', function()
	{
		$(this).before('Name: <input type="text" name="'+$(this).attr('data-field')+'[]" class="selectperson" id="input2" />').textinput();
		$('.selectperson').textinput();

		$(".selectperson").autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/api/findconnected/"+request.term,
					dataType: "json",
					success: function( data ) {
						response($.map( data.names, function( item ) {
							return {
								label: item.name,
								value: item.name,
							}
						}));
					}
				});
			},
			minLength: 2,
			select: function( event, ui ) {
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});
	});
<?php endif; ?>
});
</script>


<h3><?= $field->label ?></h3>
Name: <input type="text" name="<?= $prefix ?><?= $field->id ?><?= isset($field->multiple) && $field->multiple == 'true' ? '[]' : ''?>" class="selectperson"<?= $read_only ? ' readonly="readonly"' : '' ?> />
<?php if(!$read_only && isset($field->multiple) && $field->multiple == 'true'): ?><a href="#" class="_addanotherperson" data-field="<?= $prefix ?><?= $field->id ?>">Add Another Person</a><?php endif; ?>
<?php break;
		endswitch;
	endforeach;
}

function inflate_edit_form($elements, $prefix = 'f', $init = false, $keys = false)
{
?>
<?php if($init): ?>
<script>
$().ready(function()
{
	$('#page').on('click', '.editible', function()
	{
		$('#'+$(this).attr('data-edit')).show().focus();
		$(this).hide();
	});

	$('#page').on('blur', '.text', function()
	{
		$('#'+$(this).attr('data-display')).text($(this).val());

		if($(this).val().length == 0)
		{
			$('#'+$(this).attr('data-display')).html("<i>"+$(this).attr('data-onempty')+"</i>");
		}

		$('#'+$(this).attr('data-display')).show();

		if(!$(this).attr('data-static'))
		{
			$(this).hide();
		}
	});
});
</script>

<style>
.text
{
display:none;
 
}
</style>
<?php endif; ?>
<?php
$keys_array = array();
if($keys !== false)
{
	if(is_array($keys))
	{
		$keys_array = $keys;
	}
	else
	{
		$keys_array = array();
		foreach($keys as $k=>$v)
		{
			$keys_array[$v] = $k;
		}
	}
}

foreach($elements as $question):
	$eid = md5(time().rand().serialize($question));
	if($keys !== false && array_key_exists($question->id, $keys_array))
	{
?>
<input type="hidden" name="key[<?= $eid ?>]" value="<?= $keys_array[$question->id] ?>" />
<?php
	}

switch($question->type):
case 'instruction':
?>
<div class="removeable"   data-id="<?= $eid ?>">
	<input type="hidden" name="key[<?= $eid ?>]" value="instruction" />
	<p class="editible" id="display<?= $eid ?>" style="background-color:#fff" data-edit="edit<?= $eid ?>"><?= $question->text ?></p>
	<textarea name="<?= $prefix ?>label[instruction_<?= $eid ?>]" class="text" id="edit<?= $eid ?>" data-display="display<?= $eid ?>" style="display:none;" data-onempty="[instruction text]"><?= $question->text ?></textarea>
	<br />
</div>
<?php
break;
case 'personsearch':
?>
<div class="removeable"   data-id="<?= $eid ?>">
	<input type="hidden" name="key[<?= $eid ?>]" value="personsearch" />
	<h3 class="editible" id="display<?= $eid ?>" data-edit="edit<?= $eid ?>"><?= $question->label ?></h3>
	<input name="<?= $prefix ?>label[personsearch_<?= $eid ?>]" class="text" id="edit<?= $eid ?>" data-display="display<?= $eid ?>" style="display:none" value="<?= $question->label ?>" data-onempty="[text]" />
	<input type="text" name="<?= $prefix ?>multiple[<?= $eid ?>]" disabled="disabled" />
	<label><input type="checkbox" name="<?= $prefix ?>multiple[<?= $eid ?>]" <?= isset($question->multiple) && $question->multiple == 'true' ? ' checked="checked"' : '' ?> value="1" /> Allow multiple names</label>
</div>
<?php
break;
case 'textarea':
?>
<div class="removeable" data-id="<?= $eid ?>">
	<input type="hidden" name="key[<?= $eid ?>]" value="textarea" />
	<h3 class="editible" id="display<?= $eid ?>" data-edit="edit<?= $eid ?>"><?= $question->label ?></h3>
	<input name="<?= $prefix ?>label[textarea_<?= $eid ?>]" class="heading text" id="edit<?= $eid ?>" data-display="display<?= $eid ?>" style="display:none" value="<?= $question->label ?>" data-onempty="[text]"/>
	<textarea name="<?= $prefix ?>placeholder[<?= $eid ?>]"><?= isset($question->placeholder) ? $question->placeholder : '' ?></textarea>
</div>
<?php
break;
case 'date':
?>
<div class="removeable" data-id="<?= $eid ?>">
	<input type="hidden" name="key[<?= $eid ?>]" value="date" />
	<h3 class="editible" id="display<?= $eid ?>" data-edit="edit<?= $eid ?>"><?= $question->label ?></h3>
	<input name="<?= $prefix ?>label[date_<?= $eid ?>]" class="timeheading text" id="edit<?= $eid ?>" data-display="display<?= $eid ?>" style="display:none" value="<?= $question->label ?>" data-onempty="[text]"/>
	<input type="date" name="<?= $prefix ?>placeholder[<?= $eid ?>]" />
</div>
<?php
break;
case 'time':
?>
<div class="removeable" data-id="<?= $eid ?>">
	<input type="hidden" name="key[<?= $eid ?>]" value="time" />
	<h3 class="editible" id="display<?= $eid ?>" data-edit="edit<?= $eid ?>"><?= $question->label ?></h3>
	<input name="<?= $prefix ?>label[time_<?= $eid ?>]" class="timeheading text" id="edit<?= $eid ?>" data-display="display<?= $eid ?>" style="display:none" value="<?= $question->label ?>" data-onempty="[text]"/>
	<input type="time" name="<?= $prefix ?>placeholder[<?= $eid ?>]" />
</div>
<?php
break;
case 'datetime':
?>
<div class="removeable" data-id="<?= $eid ?>">
	<input type="hidden" name="key[<?= $eid ?>]" value="datetime" />
	<h3 class="editible" id="display<?= $eid ?>" data-edit="edit<?= $eid ?>"><?= $question->label ?></h3>
	<input name="<?= $prefix ?>label[datetime_<?= $eid ?>]" class="timeheading text" id="edit<?= $eid ?>" data-display="display<?= $eid ?>" style="display:none" value="<?= $question->label ?>" data-onempty="[text]"/>
	
	<div class="ui-grid-a">
		<div class="ui-block-a"><input type="date" name="<?= $prefix ?>placeholder[<?= $eid ?>]" /></div>
		<div class="ui-block-b"><input type="time" name="<?= $prefix ?>placeholder[<?= $eid ?>]" /></div>
	</div>
</div>
<?php
break;
case 'controlgroup':
?>
<input type="hidden" name="key[<?= $eid ?>]" value="controlgroup" />
<!-- --------------------------- start controlgroup <?= $eid ?> --------------------------- -->
<div data-role="controlgroup" data-content-theme="c">
<?php if(isset($question->label)): ?>
<h3><span id="display<?= $eid ?>"><?= $question->label ?></span></h3>
Box title: <input name="<?= $prefix ?>label[controlgroup_<?= $eid ?>]" class="text" id="edit<?= $eid ?>" data-display="display<?= $eid ?>" value="<?= $question->label ?>" data-static="true" />
<?php endif; ?>
<?php inflate_edit_form($question->elements, $eid, false, $keys_array); ?>
</div>
<!-- --------------------------- end controlgroup <?= $eid ?> --------------------------- -->
<?php
break;

case 'collapsible':
?>
<input type="hidden" name="key[<?= $eid ?>]" value="collapsible" />
<?php if(isset($question->label)): ?>
<!-- --------------------------- start collapsible <?= $eid ?> --------------------------- -->
<div data-role="collapsible" data-content-theme="c">
<h3><span id="display<?= $eid ?>"><?= $question->label ?></span></h3>
Box title: <input name="<?= $prefix ?>label[collapsible_<?= $eid ?>]" class="text" id="edit<?= $eid ?>" data-display="display<?= $eid ?>" value="<?= $question->label ?>" data-static="true" />
<?php endif; ?>
<?php inflate_edit_form($question->elements, $eid, false, $keys_array); ?>
<!-- --------------------------- end <?= $eid ?> --------------------------- -->
</div>
<?php
break;

case 'multicheckbox':
?>
<div class="removeable subitem" data-type="multicheckbox" data-id="<?= $eid ?>">
	<input type="hidden" name="key[<?= $eid ?>]" value="multicheckbox" />
	<h3 class="editible" id="display<?= $eid ?>" data-edit="edit<?= $eid ?>"><?= isset($question->label) && !empty($question->label) ? $question->label : '<i>[enter text]</i>' ?></h3>
	<input name="<?= $prefix ?>label[list_<?= $eid ?>]" class="text heading" id="edit<?= $eid ?>" data-display="display<?= $eid ?>" style="display:none" value="<?= isset($question->label) ? htmlentities($question->label) : '' ?>" data-onempty="[enter text]" />
	<ul data-role="listview" data-inset="true" class="sortable">
	<?php $i = 0; foreach($question->options as $option): ?>
	<li>
		<input type="checkbox" data-role="none" /> <span class="editible" id="display<?= $eid ?><?= $i ?>" data-edit="edit<?= $eid ?><?= $i ?>"><?= $option ?></span>
		<input name="<?= $eid ?>option[<?= $i ?>]" class="text" id="edit<?= $eid ?><?= $i ?>" data-display="display<?= $eid ?><?= $i ?>" style="display:none" value="<?= $option ?>" data-onempty="[text]" />
	</li>
	<?php $i++; endforeach; ?>
	<?php if(false && isset($question->otherlabel)): ?>
	<li>
		<div class="editible" id="display<?= $eid ?>o" data-edit="edit<?= $eid ?>o"><input type="checkbox" data-role="none" /><?= $question->otherlabel ?></div>
		<input name="<?= $eid ?>optionother" class="text" id="edit<?= $eid ?>o" data-display="display<?= $eid ?>o" style="display:none" value="<?= $question->otherlabel ?>" data-onempty="[text]" />

		<input type="text" name="<?= $eid ?>optionplaceholder" value="<?= isset($question->otherplaceholder) ? htmlentities($question->otherplaceholder) : '' ?>" />
	</li>
	<?php endif; ?>
	</ul>
</div>
<?php
break;

case 'select':
?>
<div class="removeable subitem" data-type="select" data-id="<?= $eid ?>">
	<input type="hidden" name="key[<?= $eid ?>]" value="select" />
	<h3 class="editible" id="display<?= $eid ?>" data-edit="edit<?= $eid ?>"><?= $question->label ?></h3>
	<input name="<?= $prefix ?>label[select_<?= $eid ?>]" class="text heading" id="edit<?= $eid ?>" data-display="display<?= $eid ?>" style="display:none" value="<?= htmlentities($question->label) ?>" data-onempty="[text]"/>
	<ul data-inset="true" data-role="listview">
	<?php $i=0; foreach($question->options as $option): ?>
	<li><div class="editible" id="display<?= $eid ?><?= $i ?>" data-edit="edit<?= $eid ?><?= $i ?>"><?= $option ?></div>
	<input name="<?= $eid ?>option[<?= $i ?>]" class="text" id="edit<?= $eid ?><?= $i ?>" data-display="display<?= $eid ?><?= $i ?>" style="display:none" value="<?= $option ?>" data-onempty="[text]" /></li>
	<?php
	$i++; endforeach; ?>
	</ul><br />
</div>
<?php
break;
endswitch;
endforeach;
}

	function process_form($prefix = 'f', $questions)
	{
		$form = array();

		foreach($questions as $element)
		{
			switch($element->type)
			{
				case 'textarea':
				case 'text':
					$value = $_REQUEST[$prefix.$element->id];
					if(!isset($element->label)): continue; endif;
					array_push($form, array('label' => $element->label, 'id' => $element->id, 'value' => $value));
				break;				
				case 'date':				
					$value = $_REQUEST[$prefix.$element->id];
					if(!isset($element->label)): continue; endif;
					$value = !empty($value) && strtotime($value) !== false ? date('m/d/Y', strtotime($value)) : '';
					array_push($form, array('label' => $element->label, 'id' => $element->id, 'value' => $value));
				break;
				case 'time':				
					$value = $_REQUEST[$prefix.$element->id];
					if(!isset($element->label)): continue; endif;
					$value = !empty($value) && strtotime($value) !== false ? date('g:i a', strtotime($value)) : '';
					array_push($form, array('label' => $element->label, 'id' => $element->id, 'value' => $value));
				break;
				case 'datetime':				
					$date_value = $_REQUEST[$prefix.$element->id.'d'];
					$time_value = $_REQUEST[$prefix.$element->id.'t'];
					$t = $date_value.' '.$time_value;
					if(!isset($element->label)): continue; endif;
					$value = !empty($t) && strtotime($t) !== false ? date('m/d/Y g:i a', strtotime($t)) : '';
					array_push($form, array('label' => $element->label, 'id' => $element->id, 'value' => $value));
				break;				
				case 'multicheckbox':
				case 'personsearch':
				case 'select':
					$value = isset($_REQUEST[$prefix.$element->id]) ? $_REQUEST[$prefix.$element->id] : array();
					if(!isset($element->label)): continue; endif;
					array_push($form, array('label' => $element->label, 'id' => $element->id, 'value' => $value));
				break;
				case 'collapsible':
				case 'controlgroup':
					$form = array_merge($form, process_form($prefix, $element->elements));
				break;
			}
		}

		return $form;
	}

	function form_wizard($prefix = '')
	{
?>
<style>
._template
{
	display:none;
}

.ui-input-text
{
	border-style: none;
}

.removeable
{
	position:relative;
	border-style:solid;
	border-width:1px;
	border-radius:5px;
	padding:10px;
	background-color: #E5E9EA;
}

.tools
{
	position:absolute; 
	right: 0px; 
	top: 0px;
}

.removeable:first-of-type ._moveup
{
	display:none;
}

.removeable:last-of-type ._movedown
{
	display:none;
}

._addsubitem
{
	visibility:hidden;
}

.subitem .tools ._addsubitem
{
	visibility:visible;
}
  


</style>
<script>
$(function()
{
	var elementcount = 500;

	$('#page').on('click', '._additem', function()
	{
		$('#addItemMenu').popup('close');

		var elementtype = $(this).attr('data-type');
		elementcount++;
		var before = '#'+$(this).attr('data-before');
		var id = $(this).attr('data-id') ? $(this).attr('data-id') : 'newelement'+elementcount;
		$(before).before($('._template[data-templatetype='+elementtype+']').html().replace(/__ID__/g, id).replace(/__I__/g, elementcount));
		$('#page').trigger("create");
	});

	$('#page').on('click', '._removep', function()
	{
		$(this).parents('.removeable').detach();
		loadHeadingValues();
	});

	$('#page').on('click', '._moveup', function()
	{
		$(this).parents('.removeable').insertBefore($(this).parents('.removeable').prev());
	});

	$('#page').on('click', '._movedown', function()
	{
		$(this).parents('.removeable').insertAfter($(this).parents('.removeable').next());
	});

	$('#page').on('click', '._addsubitem', function()
	{
		var elementtype = $(this).parents('.subitem').attr('data-type');
		elementcount++;
		var before = '#'+$(this).attr('data-before');
		var id = $(this).parents('.removeable').attr('data-id') ? $(this).parents('.removeable').attr('data-id') : 'newelement'+elementcount;
		var h = $('._template[data-templatetype='+elementtype+'_item]').html().replace(/__ID__/g, id).replace(/__I__/g, elementcount);
		$(this).parents('.subitem').children('ul').append(h).listview('refresh');
		$('#page').trigger("create");
	});

	function loadHeadingValues()
	{	
		var selectedValue = $('#displayanswer')[0].selectedIndex;
		$('#displayanswer').empty();
		
		$('#elements').find('.heading').each(function(index)
		{
			$('#displayanswer').append("<option value='"+$(this).val()+"'"+(selectedValue == index ? " selected='selected'" : "")+">"+$(this).val()+"</option>");
		});

		$('#displayanswer').selectmenu('refresh');

		selectedValue = $('#displaytime')[0].selectedIndex;
		$('#displaytime').empty();
		$('#displaytime').append("<option value=''"+(selectedValue == 0 ? " selected='selected'" : "")+">Time submitted</option>");
		$('#elements').find('.timeheading').each(function(index)
		{
			$('#displaytime').append("<option value='"+$(this).val()+"'"+(selectedValue == index+1 ? " selected='selected'" : "")+">"+$(this).val()+"</option>");
		});

		$('#displaytime').selectmenu('refresh');

		if($('#displaytime').attr('data-selected'))
		{
			$('#displaytime').val($('#displaytime').attr('data-selected')).attr('selected', true).siblings('option').removeAttr('selected');
			$('#displaytime').removeAttr('data-selected');
			$('#displaytime').selectmenu('refresh');
		}		
	}

	$('#page').on('change', '.heading, .timeheading', function()
	{
		loadHeadingValues();
	});

	$('.subitem ._addsubitem').show();
	$('.removeable').prepend('<span class="tools"><a class="_addsubitem" data-role="button" data-icon="plus" data-iconpos="notext" data-inline="true" data-mini="true">X</a><a class="_moveup" data-role="button" data-icon="arrow-u" data-iconpos="notext" data-inline="true" data-mini="true">X</a><a class="_movedown" data-role="button" data-icon="arrow-d" data-iconpos="notext" data-inline="true" data-mini="true">X</a><a class="_removep" data-role="button" data-icon="delete" data-iconpos="notext" data-inline="true" data-mini="true">X</a></span>');
	loadHeadingValues();
	$('#page').trigger('create');
});
</script>

<div class="_template" data-templatetype="instruction">
	<div class="removeable" data-id="__ID__">		
		<input type="hidden" name="key[__ID__]" value="instruction" />
		<p class="editible" id="display__ID__" data-edit="edit__ID__">[Click to Enter Title]</p>
		<textarea name="<?= $prefix ?>label[instruction___ID__]" class="text" id="edit__ID__" data-display="display__ID__" style="display:none;" data-onempty="[instruction text]"></textarea>
		<br />
	</div>
</div>
<div class="_template" data-templatetype="date">
	<div class="removeable" data-id="__ID__">
		<input type="hidden" name="key[__ID__]" value="date" />
		<h3 class="editible" id="display__ID__" data-edit="edit__ID__">[Click to Enter Title]</h3>
		<input name="<?= $prefix ?>label[date___ID__]" class="text timeheading" id="edit__ID__" data-display="display__ID__" style="display:none" value="" data-onempty="[text]"/>
		<input type="date" name="<?= $prefix ?>placeholder[__ID__]" />
	</div>
</div>
<div class="_template" data-templatetype="time">
	<div class="removeable" data-id="__ID__">
		<input type="hidden" name="key[__ID__]" value="time" />
		<h3 class="editible" id="display__ID__" data-edit="edit__ID__">[Click to Enter Title]</h3>
		<input name="<?= $prefix ?>label[time___ID__]" class="text timeheading" id="edit__ID__" data-display="display__ID__" style="display:none" value="" data-onempty="[text]"/>
		<input type="time" name="<?= $prefix ?>placeholder[__ID__]" />
	</div>
</div>
<div class="_template" data-templatetype="datetime">
	<div class="removeable" data-id="__ID__">
		<input type="hidden" name="key[__ID__]" value="datetime" />
		<h3 class="editible" id="display__ID__" data-edit="edit__ID__">[Click to Enter Title]</h3>
		<input name="<?= $prefix ?>label[datetime___ID__]" class="text timeheading" id="edit__ID__" data-display="display__ID__" style="display:none" value="" data-onempty="[text]"/>
		<div class="ui-grid-a">
			<div class="ui-block-a"><input type="date" name="<?= $prefix ?>placeholder[__ID__d]" /></div>
			<div class="ui-block-b"><input type="time" name="<?= $prefix ?>placeholder[__ID__t]" /></div>
		</div>
	</div>
</div>
<div class="_template" data-templatetype="personsearch">
	<input type="hidden" name="key[__ID__]" value="personsearch" />
	<h3 class="editible" id="display__ID__" data-edit="edit__ID__" >[Click to Enter Title]</h3>
	<input name="<?= $prefix ?>label[personsearch___ID__]" class="text" id="edit__ID__" data-display="display__ID__" style="display:none" value="" data-onempty="[text]" />
	<input type="text" name="<?= $prefix ?>multiple[__ID__]" disabled="disabled" />
	<label><input type="checkbox" name="<?= $prefix ?>multiple[__ID__]" value="1" /> Allow multiple names</label>
</div>
<div class="_template" data-templatetype="textarea">
	<div class="removeable" data-id="__ID__">
		<input type="hidden" name="key[__ID__]" value="textarea" />
		<h3 class="editible" id="display__ID__" data-edit="edit__ID__">[Click to Enter Title]</h3>
		<input name="<?= $prefix ?>label[textarea___ID__]" class="text heading" id="edit__ID__" data-display="display__ID__" style="display:none" value="" data-onempty="[text]"/>
		<textarea name="<?= $prefix ?>placeholder[__ID__]"></textarea>
	</div>
</div>
<div class="_template" data-templatetype="controlgroup">
	<input type="hidden" name="key[__ID__]" value="controlgroup" />
	<!-- --------------------------- start controlgroup __ID__ --------------------------- -->
	<div data-role="controlgroup" data-content-theme="c">
	<h3><span id="display__ID__">[title]</span></h3>
	Box title: <input name="<?= $prefix ?>label[controlgroup___ID__]" class="text" id="edit__ID__" data-display="display__ID__" value="" data-static="true" />
	<?php // inflate_edit_form($question->elements, $eid, false, $keys_array); ?>
	</div>
	<!-- --------------------------- end controlgroup __ID__ --------------------------- -->
</div>
<div class="_template" data-templatetype="collapsible">
	<!-- --------------------------- start collapsible __ID__ --------------------------- -->
	<input type="hidden" name="key[__ID__]" value="collapsible" />
	<div data-role="collapsible" data-content-theme="c">
		<h3><span id="display__ID__">[Click to Enter Title]</span></h3>
		Box title: <input name="<?= $prefix ?>label[collapsible___ID__]" class="text" id="edit__ID__" data-display="display__ID__" value="" data-static="true" />
	</div>
	<?php // inflate_edit_form($question->elements, $eid, false, $keys_array); ?>
	<!-- --------------------------- end __ID__ --------------------------- -->
</div>
<div class="_template" data-templatetype="multicheckbox_item">
	<li>
		<input type="checkbox" data-role="none" /> <span class="editible" id="display__ID____I__" data-edit="edit__ID____I__">[option]</span>
		<input name="__ID__option[__I__]" class="text" id="edit__ID____I__" data-display="display__ID____I__" style="display:none"  data-onempty="[option]" />
	</li>
</div>
<div class="_template" data-templatetype="multicheckbox_other">
	<li>
		<div class="editible" id="display__ID__o" data-edit="edit__ID__o"><input type="checkbox" data-role="none" />[Click to Enter Title]</div>
		<input name="__ID__optionother" class="text" id="edit__ID__o" data-display="display__ID__o" style="display:none" value="[text]" data-onempty="[text]" />

		<input type="text" name="__ID__optionplaceholder" value="" />
	</li>
</div>
<div class="_template" data-templatetype="multicheckbox">
	<div class="removeable subitem" data-type="multicheckbox" data-id="__ID__">
		<input type="hidden" name="key[__ID__]" value="multicheckbox" />
		<h3 class="editible" id="display__ID__" data-edit="edit__ID__">[Click to Enter Title]</h3>
		<input name="<?= $prefix ?>label[list___ID__]" class="text heading" id="edit__ID__" data-display="display__ID__" style="display:none" value="" data-onempty="[enter text]" />
		<ul data-role="listview" data-inset="true" class="sortable">
			<li>
				<input type="checkbox" data-role="none" /> <span class="editible" id="display__ID____I__" data-edit="edit__ID____I__">[Enter Text]</span>
				<input name="__ID__option[__I__]" class="text" id="edit__ID____I__" data-display="display__ID____I__" style="display:none" value="" data-onempty="[option]" />
			</li>

<!--			<li id="addCheckboxElement__ID__"><a href="#" class="_additem" data-id="__ID__" data-before="addCheckboxElement__ID__" data-type="multicheckbox_item">Add another option</a></li>
			<li><a href="#" class="_additem" data-type="multicheckbox_other">Add Other option</a></li>-->
		</ul>
	</div>
</div>
<div class="_template" data-templatetype="select_item">
	<li><div class="editible" id="display__ID____I__" data-edit="edit__ID____I__">[option]</div>
	<input name="__ID__option[__I__]" class="text" id="edit__ID____I__" data-display="display__ID____I__" style="display:none" value="" data-onempty="[option]" /></li>
</div>
<div class="_template" data-templatetype="select">
	<div class="removeable subitem" data-type="select" data-id="__ID__">
		<input type="hidden" name="key[__ID__]" value="select" />
		<h3 class="editible" id="display__ID__" data-edit="edit__ID__">[Click to Enter Title]</h3>
		<input name="<?= $prefix ?>label[select___ID__]" class="text heading" id="edit__ID__" data-display="display__ID__" style="display:none" value="" data-onempty="[text]"/>
		<ul data-inset="true" data-role="listview">
			<li><div class="editible" id="display__ID____I__" data-edit="edit__ID____I__">[option]</div>
				<input name="__ID__option[__I__]" class="text" id="edit__ID____I__" data-display="display__ID____I__" style="display:none" value="" data-onempty="[option]" /></li>
		</ul><br />
	</div>
</div>


<div data-role="popup" id="addItemMenu" data-overlay-theme="b">
	<ul data-role="listview" data-inset="true" style="width:180px;" data-theme="b">
		<li><a class="_additem" data-before="addElement" data-type="instruction">Instruction</a></li>
		<li><a class="_additem" data-before="addElement" data-type="textarea">Text Box</a></li>
		<li><a class="_additem" data-before="addElement" data-type="multicheckbox">Checkbox List</a></li>
		<li><a class="_additem" data-before="addElement" data-type="select">Dropdown List</a></li>		
		<li><a class="_additem" data-before="addElement" data-type="date">Date</a></li>		
		<li><a class="_additem" data-before="addElement" data-type="time">Time</a></li>
		<li><a class="_additem" data-before="addElement" data-type="datetime">Date/Time</a></li>		
	</ul>
</div>
<?php
	}

	function process_edit_form($prefix = '', &$keys = array())
	{
		$labels = isset($_REQUEST[$prefix.'label']) ? $_REQUEST[$prefix.'label'] : array();
		$placeholders = isset($_REQUEST[$prefix.'placeholder']) ? $_REQUEST[$prefix.'placeholder'] : array();
		$required = isset($_REQUEST[$prefix.'required']) ? $_REQUEST[$prefix.'required'] : array();
		$multiple = isset($_REQUEST[$prefix.'multiple']) ? $_REQUEST[$prefix.'multiple'] : array();


		$form_key = $_REQUEST['key'];
		if($form_key === false)
		{
			$form_key = array();
		}

//		header('Content-type: text/plain');
//		print_r($placeholders);

		$elements = array();
		foreach($labels as $key=>$value)
		{
			list($element, $id) = preg_split('/_/', $key);

			$is_required = !empty($required) && array_key_exists($id, $required) ? 1 : 0;
			$new_id = md5($value);

			if(array_key_exists($id, $form_key))
			{
				$keys[$form_key[$id]] = $new_id;
			}

			switch($element)
			{
				case 'instruction':
					array_push($elements, array(
						'type' => 'instruction',
						'id' => md5($value),
						'text' => $value
					));
				break;
				case 'textarea':
					$placeholder = isset($placeholders[$id]) ? $placeholders[$id] : '';
					array_push($elements, array(
						'type' => 'textarea',
						'id' => md5($value),
						'label' => $value,
						'placeholder' => $placeholder,
						//'required' => $is_required,
					));
				break;
				case 'date':
					$placeholder = isset($placeholders[$id]) ? $placeholders[$id] : '';
					array_push($elements, array(
						'type' => 'date',
						'id' => md5($value),
						'label' => $value,
						'placeholder' => $placeholder,
						//'required' => $is_required,
					));
				break;
				case 'time':
					$placeholder = isset($placeholders[$id]) ? $placeholders[$id] : '';
					array_push($elements, array(
						'type' => 'time',
						'id' => md5($value),
						'label' => $value,
						'placeholder' => $placeholder,
						//'required' => $is_required,
					));
				break;
				case 'datetime':
					$placeholder = isset($placeholders[$id]) ? $placeholders[$id] : '';
					array_push($elements, array(
						'type' => 'datetime',
						'id' => md5($value),
						'label' => $value,
						'placeholder' => $placeholder,
						//'required' => $is_required,
					));
				break;									
				case 'list':
					$opt = isset($_REQUEST[$id.'option']) ? $_REQUEST[$id.'option'] : array();

					$options = array();

					foreach($opt as $o)
					{
						if(empty($o))
						{
							continue;
						}

						array_push($options, $o);
					}

					array_push($elements, array(
						'type' => 'multicheckbox',
						'id' => md5($value),
						'label' => $value,
						//'required' => $is_required,
						'options' => $options
					));
				break;
				case 'select':
					$opt = isset($_REQUEST[$id.'option']) ? $_REQUEST[$id.'option'] : array();

					$options = array();

					foreach($opt as $o)
					{
						if(empty($o))
						{
							continue;
						}

						array_push($options, $o);
					}

					array_push($elements, array(
						'type' => 'select',
						'id' => md5($value),
						'label' => $value,
						//'required' => $is_required,
						'options' => $options
					));
				break;
				case 'personsearch':
					$allow_multiple = !empty($multiple) && $multiple[$id] == '1' ? 'true' : 'false';

					array_push($elements, array(
						'type' => 'personsearch',
						'label' => $value,
						'id' => md5($value),
						'multiple' => $allow_multiple,
						//'required' => $is_required,
					));
				break;
				case 'controlgroup':
					array_push($elements, array(
						'type' => 'controlgroup',
						'id' => md5($value),
						'label' => $value,
						'elements' => process_edit_form($id)
					));
				break;
				case 'collapsible':
					array_push($elements, array(
						'type' => 'collapsible',
						'id' => md5($value),
						'label' => $value,
						'elements' => process_edit_form($id)
					));
				break;
			}
		}

		return $elements;
	}
?>