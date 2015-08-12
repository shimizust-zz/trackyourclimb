$(document).ready(function() {
	$('body').on('click', '.climbingarea-btn', function (e) {
	//'div.btn-group a.btn'
		$(this).addClass('active');
		$(this).siblings().removeClass('active');

		if ($(this).attr('name') == "indoor") {
			$(document.getElementById('climbingAreaType')).val(1);
			$('#labelforgymid').text("Existing Gyms:");
			$('#labelforgymname').text("Gym Name (Required):");
			$('#addclimbingarea-inst').text("Can't find your gym? Enter its details below:");
			$('#labelforclimbingarea').text("Gym: ");
			$('#defaultclimbingarea').text("Set as default gym");
			$('#main_climbingid').val($('#main_gymid').val());
		}
		else if ($(this).attr('name') == "outdoor") {
			$(document.getElementById('climbingAreaType')).val(0);
			$('#labelforgymid').text("Existing Crags:");
			$('#labelforgymname').text("Crag Name (Required):");
			$('#addclimbingarea-inst').text("Can't find your crag? Enter its details below:");
			$('#labelforclimbingarea').text("Crag: ");
			$('#defaultclimbingarea').text("Set as default crag");
			$('#main_climbingid').val($('#main_cragid').val());
		}
		
		//update list of gyms or crags
		updateCountryGyms();

	});
});