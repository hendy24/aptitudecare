<div id="modal" class="container">
	<div class="row mt-5 mb-3">
		<div class="col-12 text-center">
				<h5>Search Existing Contacts</h5>
		</div>
	</div>

	<input type="hidden" id="prospect" value="{$prospect->public_id}">
	<input type="hidden" id="pipeline" value="{$pipeline}">

	<div class="row">

		<div class="col-sm-4">
	        <div class="form-group">
	            <label for="contact-type">Contact Type</label>
            	<select name="contact_type" id="contact-type" class="form-control">
                   	<option value=""></option>
                	{foreach from=$contact_type item="ct"}
                    <option value="{$ct->id}">{$ct->name}</option>
                    {/foreach}
                </select>
            </div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
			    <label for="contact-name">Name</label>
			    <input type="text" class="form-control" id="contact-name" name="contact" value="">
			    <input type="hidden" id="contact-id">
			</div>
		</div>

		<div class="col-sm-2">
			<label for="add-button">&nbsp;</label><br>
			<button type="button" id="link-contact" class="btn btn-primary" data-dismiss="modal">Add</button>
		</div>
	</div>


	<div class="row m-3">
		<div class="col-12 text-center">
			<h3>or</h3>
		</div>
	</div>

	<div class="row mb-5">
		<div class="col-12 text-white text-center">
			<button type="button" id="createContact" class="btn btn-primary" data-toggle="modal" data-remote="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=resident_contact&amp;prospect_id={$prospect->public_id}&amp;pipeline={$pipeline}" data-target="#createNewContact">Create a New Contact</button>
		</div>
	</div>
</div>



<div class="modal fade" id="createNewContact" tabindex="-1" role="dialog" aria-labelledby="addNewContactLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
            	<h3 class="modal-title">Add Contact</h3>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    <img alt="loading" src="resources/img/ajax-loader.gif">
                </p>
            </div>
        </div>
    </div>
</div>


<script>

	$("#contact-name").autocomplete({
		serviceUrl: SITE_URL + "/?module=Admissions&page=admissions&action=fetchContactNames",
		minChars: 4,
		onSelect: function (suggestion) {
			// set the values in the dom
			$(this).val(suggestion.value);
			$("#contact-id").val(suggestion.data);
		}
	});

	$("#link-contact").click(function() {
		var prospect = $("#prospect").val();
		var contact = $("#contact-id").val();
		var contactType = $("#contact-type").val();
		var pipeline = $("#pipeline").val();

		$.post(SITE_URL, {
			module: 'Admissions',
			page: 'admissions',
			action: 'linkContact',
			prospect: prospect,
			contact: contact,
			contact_type: contactType
			}, function (e) {
				location.reload();
			}
		);
	});

	$('#createContact').on('click', function(e){
		$($(this).data("target") + ' .modal-body').load($(this).data("remote"));
	});



</script>