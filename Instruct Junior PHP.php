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
        <input type="submit" name="submitAll" value="Meets Criteria">
        <input type="submit" name="submitCountry" value="Search Countries">
        <input type="submit" name="submitRef" value="Search References">
        <input type="submit" name="submitCentre" value="Search Centres">
        <input type="submit" name="submitService" value="Search Services">
    </form>

    <?php
        error_reporting(error_reporting() & ~E_WARNING);

        if (isset($_POST['submitALL']))
        {
            $user_input_country = $_POST['user_input_country'];
            echo ''.$user_input_country;
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

        // if ($argc < 2) { //This is for the input
        //     echo "Usage: php program.php <COUNTRY CODE>\n";
        //     exit(1);
        // }

        $countryCode = strtoupper($user_input_country); // strtoupper($argv[1]);



        $data = [];
        $csvFile = 'services.csv'; // Adjust the file path accordingly
   
        $data = readCSV($csvFile);
        $found_indices = array_keys(array_map('strtolower', $data['countries']), strtolower($user_input_country));
        if (!empty($found_indices)) {
            echo "User input found in the 'Country' column at the following indices: " . implode(', ', $found_indices);
        } else {
            echo "User input not found in the 'Country' column.";
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

    ?>
</body>    
</html>
