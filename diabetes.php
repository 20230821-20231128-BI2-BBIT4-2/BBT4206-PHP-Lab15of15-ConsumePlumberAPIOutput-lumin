<?php
# *****************************************************************************
# Lab 14: Consume data from the Plumber API Output (using PHP) ----
#
# Course Code: BBT4206
# Course Name: Business Intelligence II
# Semester Duration: 21st August 2023 to 28th November 2023
#
# Lecturer: Allan Omondi
# Contact: aomondi [at] strathmore.edu
#
# Note: The lecture contains both theory and practice. This file forms part of
#       the practice. It has required lab work submissions that are graded for
#       coursework marks.
#
# License: GNU GPL-3.0-or-later
# See LICENSE file for licensing information.
# *****************************************************************************

// Full documentation of the client URL (cURL) library: https://www.php.net/manual/en/book.curl.php

// STEP 1: Set the API endpoint URL
$apiUrl = 'http://127.0.0.1:5022/diabetes';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $arg_pregnant = $_POST["arg_pregnant"];
    $arg_glucose = $_POST["arg_glucose"];
    $arg_pressure = $_POST["arg_pressure"];
    $arg_triceps = $_POST["arg_triceps"];
    $arg_insulin = $_POST["arg_insulin"];
    $arg_mass = $_POST["arg_mass"];
    $arg_pedigree = $_POST["arg_pedigree"];
    $arg_age = $_POST["arg_age"];

    // Initiate a new cURL session/resource
    $curl = curl_init();

    // STEP 3: Set the cURL options
    // CURLOPT_RETURNTRANSFER: true to return the transfer as a string of the return value of curl_exec() instead of outputting it directly.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $apiUrl = $apiUrl . '?' . http_build_query($_POST); // Use $_POST directly
    curl_setopt($curl, CURLOPT_URL, $apiUrl);

    // For testing:
    echo "The generated URL sent to the API is:<br>".$apiUrl."<br><br>";

    // Make a GET request
    $response = curl_exec($curl);

    // Check for cURL errors
    if (curl_errno($curl)) {
        $error = curl_error($curl);
        // Handle the error appropriately
        die("cURL Error: $error");
    }

    // Close cURL session/resource
    curl_close($curl);

    // Process the response
    // echo "<br>The predicted output in JSON format is:<br>" . var_dump($response) . "<br><br>";

    // Decode the JSON into normal text
    $data = json_decode($response, true);

    // echo "<br>The predicted output in decoded JSON format is:<br>" . var_dump($data) . "<br><br>";

    // Check if the response was successful
    if (isset($data['0'])) {
        // API request was successful
        // Access the data returned by the API
        echo "The predicted diabetes status is:<br>";

        // Process the data
        foreach ($data as $repository) {
            echo $repository['0'], $repository['1'], $repository['2'], "<br>";
        }
    } else {
        // API request failed or returned an error
        // Handle the error appropriately
        echo "API Error: " . $data['message'];
    }

    // REQUIRED LAB WORK SUBMISSION:
    /*
    Create a form in the web user interface to post the parameter values
    (e.g., $arg_pregnant, $arg_glucose, etc.) instead of setting them manually
    in Line 22-49.
    */
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diabetes Prediction Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form method="post" action="diabetes.php">
        <h2>Diabetes Prediction Form</h2>
        <label for="pregnant">Pregnant:</label>
        <input type="text" name="arg_pregnant" required>

        <label for="glucose">Glucose:</label>
        <input type="text" name="arg_glucose" required>

        <label for="pressure">Pressure:</label>
        <input type="text" name="arg_pressure" required>

        <label for="triceps">Triceps:</label>
        <input type="text" name="arg_triceps" required>

        <label for="insulin">Insulin:</label>
        <input type="text" name="arg_insulin" required>

        <label for="mass">Mass:</label>
        <input type="text" name="arg_mass" required>

        <label for="pedigree">Pedigree:</label>
        <input type="text" name="arg_pedigree" required>

        <label for="age">Age:</label>
        <input type="text" name="arg_age" required>

        <input type="submit" value="Predict">
    </form>
</body>
</html>
