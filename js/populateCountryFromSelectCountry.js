//javascript file to populate country input with selected country name

$(document).ready(function() {
	var countrySelect = document.getElementById("country-select");
	var countryName = countrySelect.options[countrySelect.selectedIndex].text;
	var countryCode = countrySelect.options[countrySelect.selectedIndex].value;
	
	var countryHiddenInput = document.getElementById("gym-countryCode");
	if (countryHiddenInput != null) {
		countryHiddenInput.value = countryCode;
		document.getElementById("gym-countryCodeDisplay").value = countryName;
	}
});

function updateCountry() {
	var countrySelect = document.getElementById("country-select");
	var countryName = countrySelect.options[countrySelect.selectedIndex].text;
	var countryCode = countrySelect.options[countrySelect.selectedIndex].value;
	
	var countryHiddenInput = document.getElementById("gym-countryCode");
	countryHiddenInput.value = countryCode;
	document.getElementById("gym-countryCodeDisplay").value = countryName;

	//update the state input
	if (countryCode=='US') {
		document.getElementById("state-select").innerHTML = '<select id="states" class="form-control" name="gym-state" required><option value="">Select a State...</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option></select>';
	}
	else {
		document.getElementById("state-select").innerHTML = '<input class="form-control" type="text" name="gym-state" id = "state-input" required>';
	}
	
	updateCountryGyms();

}


function updateCountryGyms() {
	var countrySelect = document.getElementById("country-select");
	var countryCode = countrySelect.options[countrySelect.selectedIndex].value;

	var indoor = $(document.getElementById('climbingAreaType')).val();
	
	var climbingarea_id = $(document.getElementById('main_climbingid')).val();
	
	//update existing gym list in that country through an ajax call
	$.ajax({
		url: sitePath()+"/php_dataquery/genGymOptionsAJAX.php?countryCode="+countryCode+"&indoor="+indoor+"&climbingarea_id="+climbingarea_id,
		dataType: 'text',
		success: function(data) {
			//append gym_options to the gym-select input
			
			document.getElementById("select-gym-div").innerHTML = data;

			if ($('#existing-events').length) {
				//if this is the add-event page and you want to show updated, existing events upon changing the gyms
				
				document.getElementById("select-gym").setAttribute("onchange","updateGymEvents()");
			}

		},
		error: function(){
			alert('Error in AJAX call');
		}
	});
}