<script>
	//window.onload = function () { window.print(); }
</script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc= sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

<style>
	.danger{
		color:red;
		font-weight: bold;
	}
	.meal{
    padding: 18px;
    height: 870px;
    background-image: url("http://content.mycutegraphics.com/borders/heart-on-a-line-border.png");
    background-size: 349px 866px;
    background-repeat: no-repeat;
	}
	.brdr-bot{
		border-bottom:#ddd 1px solid;
	}
	.fRight{
		float:right;
	}
	.mb-50{
		margin-bottom: 50px;
	}
	.birthday{
		color:green;
	}
</style>


<h1>{$patient->fullName()}</h1>

<div class="container">
	{foreach from=$menuItems item=item}
		<div class="col-md-4">
			<div class="meal">
				<h2>
					{$patient->fullName()}
				</h2>
				{if ($birthday)}
					<small class="birthday">
						Birthday!
					</small>
				{/if}

				<h3>
					{$item->meal}
					<span class="fRight">{$smarty.now|date_format}</span>
				</h3>
				<label>Textures:</label>
				<div>{$diet->texture}</div>

				<label>Orders:</label>
				<div>{$diet->orders}</div>

				<label>Portion Size:</label>
				<div>{$diet->portion_size}</div>

				<label class="danger">Allergies:</label>
				<div class="danger">{$diet->portion_size}</div>

				<div class="brdr-bot">
					<label>Do Not Serve</label>
				</div>
				<div class="mb-50">dislikes</div>

				<div class="brdr-bot">
					<label>Meals</label>
				</div>
				<div>
					{$item->content|unescape:"html"}
				</div>
			</div>
		</div>
	{/foreach}
</div>