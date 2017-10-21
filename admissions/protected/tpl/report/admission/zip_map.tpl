{setTitle title="Zip Code Map | Admission Report"}
{jQueryReady}
	var map = L.map('map').setView([51.505, -0.09], 13);
	
	L.tileLayer('https://a.tiles.mapbox.com/v3/hendyshot.ia1edf6g/page.html?secure=1#10/33.4366/-111.7632', {
	    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
	    maxZoom: 18
	}).addTo(map);
	
	var marker = L.marker([51.5, -0.09]).addTo(map);
	marker.bindPopup("<b>83619</b><br>53 Admissions").openPopup();

{/jQueryReady}
<div class="right"><a href="{$returnUrl}" class="button">Return to Detail View</a></div>
<br />
<br />
<h1 class="text-center">Admissions by Zip Code Map</h1>
<h2 class="text-center"><span class="text-16">for</span> {$facility->name}</h2>
<br />
<div id="map" class="clear"></div>