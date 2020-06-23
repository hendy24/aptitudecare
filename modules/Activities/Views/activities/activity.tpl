<div class="container">
	<h1>{$headerTitle}</h1>

	<form action="{$SITE_URL}" method="post">
		<input type="hidden" name="module" value="Activities">
		<input type="hidden" name="page" value="activities">
		<input type="hidden" name="action" value="save_activity">
		<input type="hidden" name="location" value="{$location->public_id}">
		<input type="hidden" name="activity_id" id="activity-id" value="{$activity->public_id}">
		<input type="hidden" name="current_url" value="{$current_url}">

		<!-- Display message for monthly recurring activities -->
		{if $activity->repeat_week != ""}
		<div class="row text-center my-4">
			<div class="col-sm text-center py-2 mx-2 bg-warning">
				This activity is scheduled to recur on week {$activity->repeat_week} of each month.
			</div>
		</div>	
		<!-- /Display message for monthly recurring activities -->

		<!-- Display message for daily recurring activities -->
		{elseif $activity->daily != 0 AND $activity->daily != ""}
		<div class="row text-center my-4">
			<div class="col-sm text-center py-2 mx-2 bg-warning">
				This is a daily recurring activity{if $activity->time_start != ""} at {$activity->time_start|date_format: "%l:%M %P"}{/if}
			</div>
		</div>
		<!-- /Display message for daily recurring activities -->

		<!-- Display message for weekly recurring activities -->
		{elseif $activity->repeat_weekday !== ""}
		<div class="row text-center my-4">
			<div class="col-sm text-center py-2 mx-2 bg-warning">
				This activity is scheduled to recur every {$activity->date_start|date_format: "%A"}{if $activity->time_start != ""} at {$activity->time_start|date_format: "%l:%M %p"}{/if}
			</div>
		</div>
		{/if}
		<!-- /Display message for weekly recurring activities -->


		<!-- Change all future occurances of an activity -->
		{if $activity->repeat_week != "" OR $activity->repeat_weekday != "" OR $activity->daily}
		<div class="row">
			<div class="col-12 mb-3 form-check">
				<input type="checkbox" name="change_all" value="1" id="change-all" checked> Change all future occurances of this activity</td>
			</div>	
		</div>
		{/if}
		<!-- /Change all future occuances of an activity -->

		<!-- Select start date -->
		<div class="row">
			<div class="col-6">
				<div class="form-group">
					<label for="activity-start">Start Date:</label>
					<div class="input-group date">
						<input type="text" id="activity-start" class="form-control datepicker" name="date_start" value="{$activity->date_start|date_format:'%d %B, %Y'}">
					</div>
				</div>
			</div>
			<div class="col-2">
				<div class="form-group">
					<label for="activity-time">Start Time:</label>
					<div class="input-group time">
						<input type="text" id="activity-time" class="form-control timepicker" name="time_start" value="{$activity->time_start|date_format:'%l:%M %p'}">
					</div>
				</div>
			</div>
			<!-- repeat select box -->
			<div class="col-4">
				<label for="repeat-type">Repeat:</label>
				<select name="repeat_type" class="custom-select" id="repeat-type">
					<option value="">None</option>
					<option value="daily" {if $activity->daily != ""} selected{/if}>Daily</option>
					<option value="weekly" {if $activity->repeat_weekday != ""} selected{/if}>Weekly</option>
					<option value="monthly" {if $activity->repeat_week != ""} selected{/if}>Monthly</option>
				</select>
			</div>
			<!-- /repeat select box -->
		</div>
		<!-- /Select start date -->

		<div class="row">
			<div class="col-12">
				<!-- all day -->
				<div class="col-3 form-check">				
					<input class="form-check-input" type="checkbox" value="1" id="all-day" name="all_day" {if $activity->all_day}checked{/if}>
					<label for="all-day" class="form-check-label">All day</label>
				</div>
				<!-- /all day -->
			</div>
		</div>


		
		<div class="row mt-4 input-group">
			<!-- activity name -->
			<div class="col-12">
				<label for="activity-description">Activity:</label>
				<input type="text" class="form-control"  name="description" value="{$activity->description}" id="activity-description" size="40">
			</div>
			<!-- activity name -->

		</div>


		<div class="row mt-5">
			<div class="col-4">
				<button type="button" id="delete" class="btn btn-danger delete" data-target="#deleteModal" data-toggle="modal">Delete</button>
			</div>
			<div class="col-8 text-right">
				<input type="button" class="btn btn-secondary" value="Cancel" onclick="history.go(-1)">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</form>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">DELETE ACTIVITY</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to do this? Deleting the activity cannot be undone. Please confirm that you want to delete this activity.</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" id="deleteActivity" type="button">Yes, Delete</button>
				<button class="btn btn-secondary" type="button" data-dismiss="modal">No, Do not Delete</button>
			</div>
		</div>
	</div>	
</div>

<script type="text/javascript">	
	$('#deleteActivity').click(function(e) {
		var id = $('#activity-id').val();
		$.ajax({
			type: 'post',
			url: SITE_URL,
			data: {
				module: 'Activities',
				page: 'activities',
				action: 'deleteActivity',
				id: id
			}, success: function(response) {
				window.location.href = SITE_URL + '/?module=Activities&page=activities';
			}
		});
	});

	$('.datepicker').pickadate();
	$('.timepicker').pickatime();

</script>

