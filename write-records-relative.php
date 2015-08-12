<?php
//Figure out if any records have been achieved
//First, extract the current records
$stmt = $db->prepare("SELECT * from userrecords WHERE userid=?");
$stmt->execute(array($userid));
$userrecords = $stmt->fetch(PDO::FETCH_NUM);
$newrecord = false; //keep track if any new records
for ($i=1;$i<5;$i++) {
	if ($userrecords[$i] < $maxBoulder[$i-1] && $maxBoulder[$i-1]>0) {
		//a new record
		$userrecords[$i] = $maxBoulder[$i-1];
		$newrecord = true;
	}
}
for ($i=5;$i<9;$i++) {
	if ($userrecords[$i] < $maxTR[$i-5] && $maxTR[$i-5]>0) {
		$userrecords[$i] = $maxTR[$i-5];
		$newrecord = true;
	}
}
for ($i=9;$i<13;$i++) {
	if ($userrecords[$i] < $maxLead[$i-9] && $maxLead[$i-9]>0) {
		$userrecords[$i] = $maxLead[$i-9];
		$newrecord = true;
	}
}
//if any new records were achieved, write to database
$stmt6 = $db->prepare("UPDATE userrecords SET highestBoulderProject=:hBP,
highestBoulderRedpoint=:hBR,highestBoulderFlash=:hBF,highestBoulderOnsight=:hBO,
highestTRProject=:hTP,highestTRRedpoint=:hTR,highestTRFlash=:hTF,highestTROnsight=:hTO,
highestLeadProject=:hLP,highestLeadRedpoint=:hLR,highestLeadFlash=:hLF,
highestLeadOnsight=:hLO WHERE userid = :userid");
$stmt6->execute(array(':hBP'=>$userrecords[1],':hBR'=>$userrecords[2],
':hBF'=>$userrecords[3],':hBO'=>$userrecords[4],':hTP'=>$userrecords[5],
':hTR'=>$userrecords[6],':hTF'=>$userrecords[7],':hTO'=>$userrecords[8],
':hLP'=>$userrecords[9],':hLR'=>$userrecords[10],':hLF'=>$userrecords[11],
':hLO'=>$userrecords[12],':userid'=>$userid));

?>