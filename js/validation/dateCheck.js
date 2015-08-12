function dateCheck() {
				
	var date = document.forms["workout-form"]["workoutdate"].value;

	var dateObj = new Date(date);
	var specYear = dateObj.getFullYear();
	var currYear = new Date().getFullYear();
	var minYear = 1980;
	
	if (isNaN(specYear) || specYear < minYear || specYear > currYear) {
		//arbitrarily set a minimum, valid year for a workout
		//don't allow dates in the future year
		alert("The workout date is invalid. Please choose a new date.");
		return false;
	}
	
}