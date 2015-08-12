<?php
$stmt = $db->prepare("SELECT COUNT(*) as num_gyms, COUNT(DISTINCT countryCode) as num_countries FROM gyms");
$stmt->execute();
$numGymResult = $stmt->fetch(PDO::FETCH_ASSOC);
$numGyms = $numGymResult['num_gyms'];
$numCountries = $numGymResult['num_countries'];
?>