<div class="container">
	<div class="row">
		<h1>Transfer {$resident->first_name} {$resident->last_name} to a New Room</h1>
	</div>
	
	<div class="row">
		{foreach $availableRooms as $room}
			<div class="col-6">{$room->number}</div>
		{/foreach}
	</div>
</div>