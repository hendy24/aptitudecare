{setTitle title="ADC Report"}
{include file="patient/export_icons.tpl"}

{if $view != 'year'}
	{jQueryReady}		
			
			var graphData = [];
			var graphInfo = [];
			var graphCats = [];
			
			$.getJSON(SITE_URL, { page: 'report', action: 'getAdcData', facility: $("#facility-id").html(), view: $("#page-view").html(), year: $("#page-year").html() }, function(json) {
			
				$.each(json.data, function(i, v) {
					obj = new Object;
					obj = $.parseJSON(v);
					graphData.push(obj);
				});
				
				$.each(json.categories, function(i, v) {
					obj = new Object;
					obj = v;
					graphCats.push(obj);
				});
	
				
				graphInfo['data'] = graphData;
				graphInfo['categories'] = graphCats;
				
							
				$('#container').highcharts({
			        chart: {
			            type: 'line',
			        },
			        title: {
			            text: 'Average Daily Census'
			        },
			        xAxis: {
			            categories: graphInfo['categories']
			        },
			        yAxis: {
			            title: {
			                text: 'Census'
			            }
			        },
			        series: [{
				        name: 'ADC',
				        data: graphInfo['data']
			        }]
			    });
	
			});		
					
	{/jQueryReady}
{/if}

<div id="facility-id" class="hidden">{$facilityId}</div>
<div id="page-view" class="hidden">{$view}</div>
<div id="page-year" class="hidden">{$year}</div>

<h1 class="text-center">Average Daily Census Report<br /><span class="text-16">for {$facility->name}</h1>
{include file="report/index.tpl"}

{if $view != 'year'}
<!-- <div id="container" style="width:100%; height:400px;"></div> -->
{/if}

<table id="report-table" cellpadding="5" cellspacing="0">
	<tr class="report-total">
		<th>{$view|ucfirst}</th>
		<th># of Admissions</th>
		<th># of Discharges</th>
		<th>Average Daily Census</th>
	</tr>
	{foreach $adc_info as $adc}
	<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
		<td style="text-align: left">{if $view == "year"}{$year}{else}{$adc->time_period|date_format:"%B"}{/if}</td>
		<td>{$adc->admission_count}</td>
		<td>{$adc->discharge_count}</td>
		<td>{$adc->census}</td>
	</tr>
	{/foreach}
</table>


