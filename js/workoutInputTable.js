function workoutInputTable(climbType,climbGradeSystems,gradeMapAbsGrade,gradingID,minGradeInd,maxGradeInd) {
//climbType = {'B','TR','L'} for boulder, toprope, and lead, resp.
//climbGradeSystems is either routeRatings or boulderRatings (defined in previous js file)
//gradeMapAbsGrade is an object that has keys of text values of grade and corresponding value of absolute grade index
//gradingID is an integer indicating which grading system to use in climbGradeSystems
//minGradeInd is minimum grade index to display
//maxGradeInd is the maximum grade index to display

//This function generates the workout input tables. The IDs of each button are of the form: "btn-{ascentType}{climbType}{relativeGradeInd}


var gradeList = climbGradeSystems[gradingID]; //an array of strings containing distinct grading names, e.g. ['V0','V1','V2',...]

var climbTable = tableHeader; //tableHeader defined in inputTableShared.js

//find max grade index
var maxGradeIndAll = gradeList.length - 1; //highest possible grade

for (var i = 0; i <= maxGradeIndAll; i++) {

	if (i>=minGradeInd && i<=maxGradeInd) {
			var hidebtn = "";
		}
		else {
			var hidebtn = ' style="display:none"';
		}	
	
	var heading = "<tr"+hidebtn+"><td><h4>"+gradeList[i]+"</td>";
	var btnprimary = '<button type=\"button\" class=\"btn btn-primary\"';
	var btndec = '<button type=\"button\" class=\"btn btn-danger\"';


	var projectbtngroup = '<td '+hideProject+'><div class=\"btn-group btn-group-lg btn-grades\">';
	var projectbtn = projectbtngroup + btnprimary + 
					' id=\"btn-Project'+climbType+i+'\">+</button>';
	var hiddenprojectinput = '<input type=\"hidden\" name=\"numProject'+climbType+i+'\" value=\"0\" id=\"numProject'+climbType+i+'\">';
	var projectdecbtn = btndec + 
					' id=\"btn-Project'+climbType+i+'-dec\">-</button></div></td>';
	
	var redpointbtngroup = '<td '+hideRedpoint+'><div class=\"btn-group btn-group-lg btn-grades\">';
	var redpointbtn = redpointbtngroup + btnprimary+
					' id=\"btn-Redpoint'+climbType+i+'\">+</button>';
	var hiddenredpointinput = '<input type=\"hidden\" name=\"numRedpoint'+climbType+i+'\" value=\"0\" id=\"numRedpoint'+climbType+i+'\">';
	var redpointdecbtn = btndec+
					' id=\"btn-Redpoint'+climbType+i+'-dec\">-</button></div></td>';
					
	var flashbtngroup = '<td '+hideFlash+'><div class=\"btn-group btn-group-lg btn-grades\">';				
	var flashbtn = flashbtngroup + btnprimary+
					' id=\"btn-Flash'+climbType+i+'\">+</button>';
	var hiddenflashinput = '<input type=\"hidden\" name=\"numFlash'+climbType+i+'\" value=\"0\" id=\"numFlash'+climbType+i+'\">';
	var flashdecbtn = btndec+
					' id=\"btn-Flash'+climbType+i+'-dec\">-</button></div></td>';			
	
	var onsightbtngroup = '<td '+hideOnsight+'><div class=\"btn-group btn-group-lg btn-grades\">';			
	var onsightbtn = onsightbtngroup + btnprimary+
					' id=\"btn-Onsight'+climbType+i+'\">+</button>';
	var hiddenonsightinput = '<input type=\"hidden\" name=\"numOnsight'+climbType+i+'\" value=\"0\" id=\"numOnsight'+climbType+i+'\">';
	var onsightdecbtn = btndec+
					' id=\"btn-Onsight'+climbType+i+'-dec\">-</button></div></td>';			
		
	climbTable += heading+projectbtn+hiddenprojectinput+projectdecbtn+
					redpointbtn+hiddenredpointinput+redpointdecbtn+
					flashbtn+hiddenflashinput+flashdecbtn+
					onsightbtn+hiddenonsightinput+onsightdecbtn;
			
	climbTable += "</tr>";
}
climbTable += "</table>";
document.write(climbTable);

}