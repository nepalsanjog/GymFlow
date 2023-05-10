<?php
/*
 * workout.php
 * Contains variables and functions needed for the workout customizer.
 *
 */

// Borrowing the common functions from plugin.php
require "plugin.php";

// Since the workout data is tied to the users account,
// we need to be able to retrieve their username from session_id
$user = $_SESSION['user'];

function retrieveUserInfo($user)
{
    $mysqli = sqlConnect();
    if ($mysqli) {
        // SQL command to pull all records from workout_preferences under this users account
        $stmt = $mysqli->prepare("SELECT * FROM workout_preferences WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        // Save result
        $result = $stmt->get_result();
        // Save associative array in $output
        $output = $result->fetch_assoc();

        if ($output == null) {
            consoleLog("Workout preferences have never been recorded for this user.");
            return null;
        } else {
            return $output;
        }
    }
}

function saveWorkoutPreferences($user, $gender, $age, $height, $weight, $exerciseLevel, $goals, $exerciseType, $preferredLocation, $exerciseDuration)
{
    $mysqli = sqlConnect();
    if ($mysqli) {

        // Convert the exerciseType array to a string with comma-separated values
        $exerciseType = implode(",", $exerciseType);
        $stmt = $mysqli->prepare("UPDATE workout_preferences SET username = ?, gender = ? , age = ?, height = ?, weight = ?, exercise_level = ?, goals = ?, exercise_type = ?, preferred_location = ?, exercise_duration = ? WHERE username = ?");
        $stmt->bind_param("ssiiissssis", $user, $gender, $age, $height, $weight, $exerciseLevel, $goals, $exerciseType, $preferredLocation, $exerciseDuration, $user);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            $stmt = $mysqli->prepare("INSERT INTO workout_preferences(username, gender, age, height, weight, exercise_level, goals, exercise_type, preferred_location, exercise_duration) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiiissssi", $user, $gender, $age, $height, $weight, $exerciseLevel, $goals, $exerciseType, $preferredLocation, $exerciseDuration);
            $stmt->execute();
            consoleLog("Created");
            $_SESSION['routine'] = true;
            return true;
        } else {
            consoleLog("Updated.");
            $_SESSION['routine'] = true;
            return true;
        }
    }
}

function saveExercises($user, $recommendedExercises) {
    $mysqli = sqlConnect();
    if ($mysqli) {
        $json = json_encode($recommendedExercises);
        $stmt = $mysqli->prepare("UPDATE workout_preferences SET exercise_list = ? WHERE username = ?");
        $stmt->bind_param('ss', $json, $user);
        $stmt->execute();
    }
}

function listExercises($user) {
    $mysqli = sqlConnect();
    if ($mysqli) {
        $stmt = $mysqli->prepare("SELECT exercise_list FROM workout_preferences WHERE username = ?");
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $json = $row['exercise_list'];

        if ($json == null) {
            consoleLog("Workout preferences have never been recorded for this user.");
            return null;
        } else {
            return json_decode($json, true);
        }
    }
}
