<?php
/*
 * calories.php
 * Contains variables and functions needed for the calorie counter.
 *
 */

// Borrowing the common functions from plugin.php
require "plugin.php";

// Since the calorie counter data is tied to the users account,
// we need to be able to retrieve their username from session_id
$user = $_SESSION['user'];

function retrieveCalorieSum($date) {
    $output = retrieveCalories($date);
    $sum = 0;
        // Validate the output
        if ($output != null) {
            // $output is n associative array, therefore each value in this foreach loop
            // is an array of the rows that were pulled from the database.
            foreach ($output as $innerArray) {
                // This verifies that $innerArray, which represents a single row, is valid.
                if (is_array($innerArray)) {
                    // This iterates through the provided row.
                    foreach ($innerArray as $value) {
                        // If $value is an int, which only occurs when $value = calories, then
                        // we add them to sum.
                        if (is_int($value)) {
                            // Add the value to the sum
                            $sum += $value;
                        }
                    }
                }
            }
        }
        return $sum;
}

function retrieveCalories($date)
{
    $mysqli = sqlConnect();
    if ($mysqli) {

        // SQL command to pull all records from calories under this users account
        $stmt = $mysqli->prepare("SELECT ITEM,CALORIES FROM MEAL_LIST WHERE USERNAME = ? AND DATE = ?");
        $stmt->bind_param("ss", $GLOBALS['user'], $date);
        $stmt->execute();
        // Save result
        $result = $stmt->get_result();
        // Save associative array in $output
        $output = array();
        while ($row = $result->fetch_assoc()) {
            $output[] = $row;
        }
        // Make sure the array isn't null
        if ($output == null) {
            consoleLog("Calories have never been recorded for this user.");
            return null;
        } else {
            return $output;
        }
    }
}

function addMeal($meal, $calories, $user, $date)
{
    $mysqli = sqlConnect();
    if ($mysqli) {
        $calories = intval($calories);
        $user = strval($user);

        $stmt = $mysqli->prepare("INSERT INTO MEAL_LIST(ITEM, CALORIES, USERNAME, DATE) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("siss", $meal, $calories, $user, $date);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            consoleLog("An error occurred. Please try again :c");
        } else {
            echo "Successfully added to database.";
        }
    }
}
