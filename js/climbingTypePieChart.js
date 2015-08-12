

var options2 = {
	series: { 
		pie: { 
			show:true,
			label: {
				//formatter: labelFormatter
			}
		}
	}, 
	grid: {
		hoverable:true
	}, 
	legend: { 
		show:false
	}

};

function labelFormatter(label,series) {
	return "<div style='font-size:1em; text-align:center; padding:2px;'>"    + label + "<br/>" + "%</div>";
}

$(function() {
	var data = [
			{ label: "Bouldering",  data: totalBoulderPoints, color:"#7FB0D9"},
			{ label: "Top-Roping",  data: totalTRPoints,color:"#98DB5C"},
			{ label: "Leading",  data: totalLeadPoints,color:"#FFD850"}
		];
		
		$.plot("#piechartplaceholder", data, options2);
});
