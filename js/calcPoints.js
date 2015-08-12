function updatePointTotal(currPointTotal,ascentType,climbType,relativeRatingInd,gradingID,decrement) {



var pointChange = 0;
var absGradeIndex = 0;
var ascentFactor = [0.5,1,1.15,1.2];
var climbTypeFactor = [1.0,1.5,2.0];
var boulderToRouteNormalizationRatio = 22.0/30.0;

var ascentInd;

switch (ascentType) {
	case 'Project':
		ascentInd = 0;
		break;
	case 'Redpoint':
		ascentInd = 1;
		break;
	case 'Flash':
		ascentInd = 2;
		break;
	case 'Onsight':
		ascentInd = 3;
		break;
	default:
		ascentInd = 1;
}

if (climbType == 'B') {
	absGradeIndex = boulderGradeMapAbsGradeInd[gradingID][boulderRatings[gradingID][relativeRatingInd]];

	pointChange = climbTypeFactor[0]*(absGradeIndex + 0.5)*100.0*ascentFactor[ascentInd];

} else if (climbType == 'TR') {
	absGradeIndex = routeGradeMapAbsGradeInd[gradingID][routeRatings[gradingID][relativeRatingInd]];
	
	pointChange = climbTypeFactor[1]*(absGradeIndex + 0.5)*boulderToRouteNormalizationRatio*100.0*ascentFactor[ascentInd];
} else if (climbType == 'L') {
	absGradeIndex = routeGradeMapAbsGradeInd[gradingID][routeRatings[gradingID][relativeRatingInd]];
	
	pointChange = climbTypeFactor[2]*(absGradeIndex + 0.5)*boulderToRouteNormalizationRatio*100.0*ascentFactor[ascentInd];
}

if (decrement == true) {
	pointChange = -pointChange
} 

currPointTotal += pointChange;
if (currPointTotal < 0) {
	currPointTotal = 0;
}


return currPointTotal;

}