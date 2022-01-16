<div id="assign-room" class="container">
	<h1>Assign a Room <span class="text-16">for</span> {$prospect->first_name} {$prospect->last_name}</h1>

	<form action="{$SITE_URL}" method="post">
		<input type="hidden" name="module" value="Admissions">
		<input type="hidden" name="page" value="admissions">
		<input type="hidden" name="action" value="save_room_assignment">
		<input type="hidden" name="prospect" value="{$prospect->public_id}">

		<div class="row mt-5">
			{foreach from=$rooms key=k item="room"}
			<div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mt-3">
				<div class="form-check">
				    <input type="radio" id="room{$k}" name="room" value="{$room->id}" class="form-check-input">
				    <label for="room{$k}" class="form-check-label">{if !isset($room->first_name)}{$room->number}{/if}</label>
				</div>
			</div>
			{/foreach}		
		</div>
		<div class="col-12 mt-5 text-right">
			<button type="submit" class="btn btn-primary">Assign Room</button>
		</div>
	</form>


</div>