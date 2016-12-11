// Names of route grading systems
var routeGradingSystems = ['YDS','French','British Tech','Ewbank AUS','Ewbank South Africa','UIAA','Saxon','Norwegian','Finnish','Brazilian'],
	boulderGradingSystems = ['Hueco','Fontainebleau','Brazilian'];

//routeRatings and boulderRatings contain unique grades for each grading system
var routeRatings = Array(10);
routeRatings[0] = ["<=5.5","5.6","5.7","5.8","5.9","5.10a","5.10b","5.10c","5.10d", "5.11a","5.11b","5.11c","5.11d","5.12a","5.12b","5.12c","5.12d","5.13a", "5.13b","5.13c","5.13d","5.14a","5.14b","5.14c","5.14d","5.15a","5.15b", "5.15c","5.15d"]; //YDS
routeRatings[1] = ["<=4b","4c","5a","5b","5c","6a","6a+","6b","6b+","6c","6c+","7a","7a+","7b","7b+","7c","7c+","8a","8a+","8b","8b+","8c","8c+","9a","9a+","9b","9b+","9c"]; //French
routeRatings[2] = ["<=4a","4b","4c","5a","5b","5c","6a","6b","6c","7a","7b","7c","8a"]; //British Tech
routeRatings[3] = ["<=12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39"]; //Ewbank AUS
routeRatings[4] = ["<=12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41"]; //Ewbank South Africa
routeRatings[5] = ["<=IV+","V","V+","VI-","VI","VI+","VII-","VII","VII+","VIII-","VIII","VIII+","IX-","IX","IX+","X-","X","X+","XI-","XI","XI+","XII-","XII"];   //UIAA
routeRatings[6] = ["<=V","VI","VIIa","VIIb","VIIc","VIIIa","VIIIb","VIIIc","IXa","IXb","IXc","Xa","Xb","Xc","XIa","XIb","XIc","XIIa","XIIb","XIIc","XIIIa","XIIIb"]; //Saxon
routeRatings[7] = ["<=5-","5","5+","6-","6","6+","7-","7","7+","8-","8","8+","9-","9","9+","10-","10"]; //Norwegian
routeRatings[8] = ["<=5-","5","5+","6-","6","6+","7-","7","7+","8-","8","8+","9-","9","9+","10-","10","10+","11-","11","11+","12-","12","12+"]; //Finnish
routeRatings[9] = ["<=III sup","IV","IV sup","V","VI","VI sup","7a","7b","7c","8a","8b","8c","9a","9b","9c","10a","10b","10c","11a","11b","11c","12a","12b","12c","13a"]; //Brazilian


var boulderRatings = Array(3);
boulderRatings[0] = ["V0","V1","V2","V3","V4","V5","V6","V7","V8","V9","V10","V11","V12","V13","V14","V15","V16"]; //Hueco
boulderRatings[1] = ["4-","4","4+","5","5+","6A","6A+","6B","6B+","6C","6C+","7A","7A+","7B","7B+","7C","7C+","8A","8A+","8B","8B+","8C","8C+"]; //Fontainebleau
boulderRatings[2] = ["II","III","IV","IV sup","V","VI","VI sup","7a","7b","7c","8a","8b","8c","9a","9b","9c","10a","10b","10c","11a","11b"]; //Brazilian


//To use, routeConversionTable[routeGradingId][absGradeInd] = the grade text for specific routeGradingID
var routeConversionTable = Array(10);
routeConversionTable[0] = ["<=5.5","5.6","5.7","5.7","5.8","5.9","5.9","5.10a","5.10b","5.10c","5.10d", "5.11a","5.11b","5.11c","5.11d","5.12a","5.12b","5.12c","5.12d","5.13a", "5.13b","5.13c","5.13d","5.14a","5.14b","5.14c","5.14d","5.15a","5.15b", "5.15c","5.15d"]; //YDS
routeConversionTable[1] = ["<=4b","4c","5a","5a","5b","5c","5c","6a","6a+","6b","6b+","6c","6c","6c+","7a","7a+","7b","7b+","7c","7c+","8a","8a+","8b","8b+","8c","8c+","9a","9a+","9b","9b+","9c"]; //French
routeConversionTable[2] = ["<=4a","4b","4c","4c","5a","5a","5a","5b","5b","5c","5c","5c","6a","6a","6a","6b","6b","6b","6b","6c","6c","6c","6c","7a","7a","7b","7b","7c","7c","8a","8a"]; //British Tech
routeConversionTable[3] = ["<=12","13","14","15","16","17","17","18","19","20","20","21","22","23","24","25","26","27","28","29","29","30","31","32","33","34","35","36","37","38","39"]; //Ewbank AUS
routeConversionTable[4] = ["<=12","13","14","15","16","17","18","19","20","21","22","23","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41"]; //Ewbank South Africa
routeConversionTable[5] = ["<=IV+","V","V+","V+","VI-","VI","VI","VI+","VII-","VII","VII+","VIII-","VIII-","VIII","VIII","VIII+","VIII+","IX-","IX","IX+","IX+","X-","X","X+","X+","XI-","XI","XI+","XII-","XII-","XII"];   //UIAA
routeConversionTable[6] = ["<=V","VI","VI","VI","VIIa","VIIb","VIIb","VIIc","VIIc","VIIIa","VIIIb","VIIIc","VIIIc","IXa","IXb","IXc","IXc","Xa","Xb","Xc","Xc","XIa","XIb","XIc","XIc","XIIa","XIIb","XIIc","XIIc","XIIIa","XIIIb"]; //Saxon
routeConversionTable[7] = ["<=5-","5","5","5","5+","6-","6-","6","6","6+","7-","7","7","7+","7+","8-","8-","8","8","8+","9-","9-","9","9","9+","9+","9+","10-","10-","10","10"]; //Norwegian
routeConversionTable[8] = ["<=5-","5","5","5","5+","5+","5+","6-","6-","6","6","6+","6+","7-","7","7+","8-","8","8+","9-","9","9+","10-","10","10+","11-","11","11+","12-","12","12+"]; //Finnish
routeConversionTable[9] = ["<=III sup","IV","IV","IV","IV sup","V","V","VI","VI","VI sup","VI sup","7a","7b","7c","7c","8a","8b","8c","9a","9b","9c","10a","10b","10c","11a","11b","11c","12a","12b","12c","13a"]; //Brazilian


var boulderConversionTable = Array(3);
boulderConversionTable[0] = ["V0","V0","V0","V1","V2","V3","V3","V4","V5","V5","V5","V6","V7","V8","V8","V9","V10","V11","V12","V13","V14","V15","V16"]; //Hueco
boulderConversionTable[1] = ["4-","4","4+","5","5+","6A","6A+","6B","6B+","6C","6C+","7A","7A+","7B","7B+","7C","7C+","8A","8A+","8B","8B+","8C","8C+"]; //Fontainebleau
boulderConversionTable[2] = ["II","III","IV","IV sup","V","VI","VI","VI sup","VI sup","7a","7b","7c","8a","8b","8c","9a","9b","9c","10a","10b","10c","11a","11b"]; //Brazilian




//routeGradeMapAbsGrade maps the grade text (e.g. "5.12a") to an absolute grade index.
//For example, routeGradeMapAbsGradeInd[routeGradingID][routeRatings[i]]
routeGradeMapAbsGradeInd = Array(10);
routeGradeMapAbsGradeInd[0] = {"<=5.5":0,"5.6":1,"5.7":3,"5.8":4,"5.9":6,"5.10a":7,"5.10b":8,"5.10c":9,"5.10d":10, "5.11a":11,"5.11b":12,"5.11c":13,"5.11d":14,"5.12a":15,"5.12b":16,"5.12c":17,"5.12d":18,"5.13a":19, "5.13b":20,"5.13c":21,"5.13d":22,"5.14a":23,"5.14b":24,"5.14c":25,"5.14d":26,"5.15a":27,"5.15b":28, "5.15c":29,"5.15d":30}; //YDS
routeGradeMapAbsGradeInd[1] = {"<=4b":0,"4c":1,"5a":3,"5b":4,"5c":6,"6a":7,"6a+":8,"6b":9,"6b+":10,"6c":11,"6c+":13,"7a":14,"7a+":15,"7b":16,"7b+":17,"7c":18,"7c+":19,"8a":20,"8a+":21,"8b":22,"8b+":23,"8c":24,"8c+":25,"9a":26,"9a+":27,"9b":28,"9b+":29,"9c":30}; //French
routeGradeMapAbsGradeInd[2] = {"<=4a":0,"4b":1,"4c":3,"5a":5,"5b":8,"5c":10,"6a":13,"6b":16,"6c":20,"7a":23,"7b":25,"7c":27,"8a":29}; //British Tech
routeGradeMapAbsGradeInd[3] = {"<=12":0,"13":1,"14":2,"15":3,"16":4,"17":5,"18":7,"19":8,"20":9,"21":11,"22":12,"23":13,"24":14,"25":15,"26":16,"27":17,"28":18,"29":19,"30":21,"31":22,"32":23,"33":24,"34":25,"35":26,"36":27,"37":28,"38":29,"39":30}; //Ewbank AUS
routeGradeMapAbsGradeInd[4] = {"<=12":0,"13":1,"14":2,"15":3,"16":4,"17":5,"18":6,"19":7,"20":8,"21":9,"22":10,"23":11,"24":13,"25":14,"26":15,"27":16,"28":17,"29":18,"30":19,"31":20,"32":21,"33":22,"34":23,"35":24,"36":25,"37":26,"38":27,"39":28,"40":29,"41":30}; //Ewbank South Africa
routeGradeMapAbsGradeInd[5] = {"<=IV+":0,"V":1,"V+":3,"VI-":4,"VI":5,"VI+":7,"VII-":8,"VII":9,"VII+":10,"VIII-":11,"VIII":13,"VIII+":15,"IX-":17,"IX":18,"IX+":19,"X-":21,"X":22,"X+":23,"XI-":25,"XI":26,"XI+":27,"XII-":28,"XII":30};   //UIAA
routeGradeMapAbsGradeInd[6] = {"<=V":0,"VI":2,"VIIa":4,"VIIb":5,"VIIc":7,"VIIIa":9,"VIIIb":10,"VIIIc":11,"IXa":13,"IXb":14,"IXc":15,"Xa":17,"Xb":18,"Xc":19,"XIa":21,"XIb":22,"XIc":23,"XIIa":25,"XIIb":26,"XIIc":27,"XIIIa":29,"XIIIb":30}; //Saxon
routeGradeMapAbsGradeInd[7] = {"<=5-":0,"5":2,"5+":4,"6-":5,"6":7,"6+":9,"7-":10,"7":11,"7+":13,"8-":15,"8":17,"8+":19,"9-":20,"9":22,"9+":25,"10-":27,"10":29}; //Norwegian
routeGradeMapAbsGradeInd[8] = {"<=5-":0,"5":2,"5+":5,"6-":7,"6":9,"6+":11,"7-":13,"7":14,"7+":15,"8-":16,"8":17,"8+":18,"9-":19,"9":20,"9+":21,"10-":22,"10":23,"10+":24,"11-":25,"11":26,"11+":27,"12-":28,"12":29,"12+":30}; //Finnish
routeGradeMapAbsGradeInd[9] = {"<=III sup":0,"IV":2,"IV sup":4,"V":5,"VI":7,"VI sup":9,"7a":11,"7b":12,"7c":13,"8a":15,"8b":16,"8c":17,"9a":18,"9b":19,"9c":20,"10a":21,"10b":22,"10c":23,"11a":24,"11b":25,"11c":26,"12a":27,"12b":28,"12c":29,"13a":30}; //Brazilian

boulderGradeMapAbsGradeInd = Array(3);
boulderGradeMapAbsGradeInd[0] = {"V0":1,"V1":3,"V2":4,"V3":5,"V4":7,"V5":9,"V6":11,"V7":12,"V8":13,"V9":15,"V10":16,"V11":17,"V12":18,"V13":19,"V14":20,"V15":21,"V16":22}; //Hueco
boulderGradeMapAbsGradeInd[1] = {"4-":0,"4":1,"4+":2,"5":3,"5+":4,"6A":5,"6A+":6,"6B":7,"6B+":8,"6C":9,"6C+":10,"7A":11,"7A+":12,"7B":13,"7B+":14,"7C":15,"7C+":16,"8A":17,"8A+":18,"8B":19,"8B+":20,"8C":21,"8C+":22}; //Fontainebleau
boulderGradeMapAbsGradeInd[2] = {"II":0,"III":1,"IV":2,"IV sup":3,"V":4,"VI":5,"VI sup":7,"7a":9,"7b":10,"7c":11,"8a":12,"8b":13,"8c":14,"9a":15,"9b":16,"9c":17,"10a":18,"10b":19,"10c":20,"11a":21,"11b":22}; //Brazilian









