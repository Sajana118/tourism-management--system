<?php
include('includes/config.php');

$sql = "SELECT DISTINCT PackageType FROM tbltourpackages";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_COLUMN);

echo "Package Types: " . implode(', ', $results);

// Also check all packages
echo "\n\nAll Packages:\n";
$sql2 = "SELECT PackageId, PackageName, PackageType, PackageLocation FROM tbltourpackages";
$query2 = $dbh->prepare($sql2);
$query2->execute();
$packages = $query2->fetchAll(PDO::FETCH_OBJ);

foreach ($packages as $package) {
    echo "ID: " . $package->PackageId . " | Name: " . $package->PackageName . " | Type: " . $package->PackageType . " | Location: " . $package->PackageLocation . "\n";
}
?>