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

        function generateSummary($data) 
        {
            $summary = [];
            foreach ($data['countries'] as $index => $country) {
                $country = strtolower($country);
                if ($country == 'country') { //ignores country header
                    continue;
                }
                if (!isset($summary[$country])) {
                    $summary[$country] = 0;
                }
                $summary[$country]++;
            }
            return $summary; 
        }
        

        function FilterByValues($data, $user_code, $column_a, $column_b)
        {
            $filteredData = [];
            foreach ($data[$column_a] as $index => $value) {
                if (strtolower($value) === strtolower($user_code)) {
                    $filteredData[] = $data[$column_b][$index];
                }
            }
            return $filteredData;
        }

        $csvFile = 'services.csv';
        $data = [];
        $data = readCSV($csvFile);


        if ($argc == 2) { //This is for the input
            echo "Usage: php Instruct-Junior-PHP.php <COUNTRY CODE>";
            echo "<br>";
            $countryCode = strtoupper($argv[1]); 
            $found_indices = array_keys(array_map('strtolower', $data['countries']), strtolower($countryCode));
            if (empty($found_indices)) {
                echo "User input not found in the 'Country' column.";
                echo "<br>";
            }

            $services = FilterByValues($data, $countryCode, "countries", "services");

            echo "Services provided by $countryCode:";
            echo "<br>";
            foreach ($services as $service) {
                echo "- $service";
                echo "<br>";
            }
    
            $summary = generateSummary($data);
            echo "Summary:";
            echo "<br>";
            foreach ($summary as $country => $count) {
                echo "$country: $count services";
                echo "<br>";
            }


        } else if (isset($_POST['submitAll'])){
                $summary = generateSummary($data); 
                echo "Summary:";
                echo "<br>";
                foreach ($summary as $country => $count) {
                    echo strtoupper($country).": $count services";
                    echo "<br>";
                }
        } else if (isset($_POST['submitCountry'])){
                $user_input_country = $_POST['user_input_country'];
                $found_indices = array_keys(array_map('strtolower', $data['countries']), strtolower($user_input_country));
                if (empty($found_indices)) {
                    echo "User input not found in the 'Country' column.";
                    echo "<br>";
                } else {
                    //list all services from this country
                    $services = FilterByValues($data, strtoupper($user_input_country), "countries", "services");
                    echo strtoupper($user_input_country).' offers the following services:';
                    echo "<br>";
                    foreach ($services as $service) {
                        echo "- $service";
                        echo "<br>";
                    }
                }

        } else if (isset($_POST['submitRef'])){
                $user_input_ref = $_POST['user_input_ref'];
                //list all facilities with this ref
                $centres = FilterByValues($data, $user_input_ref, "refs", "centres");
                echo strtoupper($user_input_ref).'is the following centre:';
                echo "<br>";
                foreach ($centres as $centre) {
                    echo "- $centre";
                    echo "<br>";
                }

        } else if (isset($_POST['submitService'])){
                $user_input_service = $_POST['user_input_service'];
                //list all centres with this service
                $services = FilterByValues($data, $user_input_service, "centres", "services");
                echo 'Centres with'.strtoupper($user_input_service).' service:';
                echo "<br>";
                foreach ($services as $service) {
                    echo "- $service";
                    echo "<br>";
                }

        } else if (isset($_POST['submitCentre'])){
                $user_input_centre = $_POST['user_input_Centre'];
                // list all services at the centre
                $centres = FilterByValues($data, $user_input_centre, "services", "centres");
                echo 'Services available at'.strtoupper($user_input_centre).' centre';
                echo "<br>";
                foreach ($centres as $centre) {
                    echo "- $centre";
                    echo "<br>";
                }
        }
        else {
            echo'';
        }
            
        

    ?>
</body>    
</html>
