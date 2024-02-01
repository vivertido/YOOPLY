<script>
$().ready(function() {
	$(".selectperson").autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "/api/findconnected/"+request.term+"/all",
				dataType: "json",
				success: function( data ) {
					response($.map( data.names, function( item ) {
						return {
							label: item.name,
							value: item.name,
							userid: item.userid,
							type: item.type
						}
					}));
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			window.location = '/'+ui.item.type+'/view/'+ui.item.userid;
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
});
</script>
<style>

</style>
<input type="search" name="person" class="selectperson" placeholder="Search individual by name..." />
<ul data-role="listview" data-inset="true">
<?php foreach($groups as $group): ?>
<li><a href="/group/view/<?= $group->groupid ?>"><?= $group->title ?></a>  </li>
<?php endforeach; ?>
<?php if(isset($showunassigned)): ?>
<li><a href="/group/view/unassigned">Unassigned Students</a></li>
<?php endif; ?>
</ul>

<ul data-role="listview" data-inset="true">
<li><a href="/student/add">New Student</a></li>
<li><a href="/teacher/add">New Teacher</a></li>
<li><a href="/group/add">New Class</a></li>
</ul>