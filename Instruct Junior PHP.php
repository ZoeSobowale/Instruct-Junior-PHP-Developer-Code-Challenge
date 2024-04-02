<!DOCTYPE html>
<html>
    <head>
        <title>Zoe PHP</title>
    </head>
<body>
    <form method="post">
        Enter Country:
        <input type="text" name="user_input_country"/><br><br>
        Enter Ref:
        <input type="text" name="user_input_ref"/><br><br>
        Enter Centre:
        <input type="text" name="user_input_centre"/><br><br>
        Enter Service:
        <input type="text" name="user_input_service"/><br><br>
        <input type="submit" name="submitAll" value="Summary">
        <input type="submit" name="submitCountry" value="Search Countries">
        <input type="submit" name="submitRef" value="Search References">
        <input type="submit" name="submitCentre" value="Search Centres">
        <input type="submit" name="submitService" value="Search Services">
    </form>

    <?php
        error_reporting(error_reporting() & ~E_WARNING);
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
        
        function readCSV($csvFile) {
            $refs = $centres = $services = $countries = array();
            if (($handle = fopen($csvFile, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $refs[] = $data[0];
                    $centres[] = $data[1];
                    $services[] = $data[2];
                    $countries[] = strtolower($data[3]); 
                }
                fclose($handle);
            } else {
                //     echo "Uh no,Unable to read the file\n";
            }
            return array(
                'refs' => $refs,
                'centres' => $centres,
                'services' => $services,
                'countries' => $countries
            );
        }

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

        $data = [];
        $csvFile = 'services.csv'; // Adjust the file path accordingly
   
        $data = readCSV($csvFile);
        $found_indices = array_keys(array_map('strtolower', $data['countries']), strtolower($user_input_country));
        if (!empty($found_indices)) {
            echo "User input found in the 'Country' column at the following indices: " . implode(', ', $found_indices);
        } else {
            echo "User input not found in the 'Country' column.";
        }


        if ($argc < 2) { //This is for the input
            echo "Usage: php program.php <COUNTRY CODE>\n";
            $countryCode = strtoupper($argv[1]);
            $services = filterByCountry($data, $countryCode);

            echo "Services provided by $countryCode:\n";
            echo "<br>";
            foreach ($services as $service) {
                echo "- $service";
            }
    
            $summary = generateSummary($data);
            echo "\nSummary:\n";
            foreach ($summary as $country => $count) {
                echo "$country: $count services\n";
                echo "<br>";
            }

        } else {
            if (isset($_POST['submitALL']))
            {
                $summary = generateSummary($data); 
                echo "\nSummary:";
                foreach ($summary as $country => $count) {
                    echo "$country: $count services\n";
                    echo "<br>";
                }

            } else if (isset($_POST['submitCountry'])){
                $user_input_country = $_POST['user_input_country'];
                echo ''.$user_input_country;

            } else if (isset($_POST['submitRef'])){
                $user_input_ref = $_POST['user_input_ref'];
                echo ''.$user_input_country;

            } else if (isset($_POST['submitService'])){
                $user_input_service = $_POST['user_input_service'];
                echo ''.$user_input_country;

            } else if (isset($_POST['submitCentre'])){
                $user_input_Centre = $_POST['user_input_Centre'];
                echo ''.$user_input_country;
            }
            
        }

    ?>
</body>    
</html>
