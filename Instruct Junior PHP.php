<?php
function filterByCountry($data, $countryCode) //this fuction filters the country code
{
    $filteredData = [];
    foreach ($data as $item) {
        if (strcasecmp($item['country'], $countryCode) === 0) {
            $filteredData[] = $item['service'];
        }
    }
    return $filteredData;
}


function generateSummary($data)//this function to generate summary output
{
    $summary = [];
    foreach ($data as $item) {
        $country = $item['country'];
        if (!isset($summary[$country])) {
            $summary[$country] = 1;
        } else {
            $summary[$country]++;
        }
    }
    return $summary;
}


if ($argc < 2) { //This is for the input
    echo "Usage: php program.php <COUNTRY CODE>\n";
    exit(1);
}

$countryCode = strtoupper($argv[1]);

$data = [];
$csvFile = 'services.csv'; // Adjust the file path accordingly
if (($handle = fopen($csvFile, 'r')) !== false) {
    while (($row = fgetcsv($handle)) !== false) {
        $data[] = ['country' => $row[0], 'service' => $row[1]];
    }
    fclose($handle);
} else {
    echo "Uh no,Unable to read the file\n";
    exit(1);
}

$services = filterByCountry($data, $countryCode);

echo "Services provided by $countryCode:\n";
foreach ($services as $service) {
    echo "- $service\n";
}

$summary = generateSummary($data); //this generates and shows the summary of services by country 
echo "\nSummary:\n";
foreach ($summary as $country => $count) {
    echo "$country: $count services\n";
}
