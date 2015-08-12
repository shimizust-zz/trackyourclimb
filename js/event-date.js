$(document).ready(function() {

	$('.datepicker').datepicker({
		todayBtn : "linked",
		todayHighlight : true,	
		autoclose : true,
		format: "yyyy-mm-dd"
	});
	//sets default date to today
	$('.datepicker').datepicker('update',new Date());
	
	
	//detect when start date is selected and change end date to the start date
	$('#start-date').datepicker().on('changeDate',function(ev) {
		//change end date
		$('#end-date').datepicker('update',$('#start-date').datepicker('getDate'));
	});
});