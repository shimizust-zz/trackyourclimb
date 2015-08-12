function writeGymList(data,region,listID) {
//write the list of gyms in 

num_gyms = data.length;
console.log(data);
states = Object.keys(data); //should return a list of states with gyms

var gymListHTML = "<ol id='gym-list' class='list-group'>";
for (var k = 0;k<=states.length-1;k++) {
	//iterate through each state
	gymListHTML += "<ol class='list-group'><div class='list-group-item' id='gymStateLabel'>"+states[k]+"</div>";
	for (var j = 0; j<= data[states[k]].length-1;j++) {
		//iterate through gyms in that state
		gymListHTML += "<a href='gympage.php?gymid="+data[states[k]][j][0]+"' class='list-group-item center'>"+data[states[k]][j][1]+"</a>";
	}
	gymListHTML += "</ol>";
}


gymListHTML += "</ol>";
document.getElementById("countryHeader").innerHTML=region;
document.getElementById(listID).innerHTML = gymListHTML;
console.log(gymListHTML);
}