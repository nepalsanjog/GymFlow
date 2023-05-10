<?php
require "workout.php";

$recommendedExercises = [];
$result = false;
$bmi = "";

if (!isset($_SESSION["user"])) {
    header("location: index.php");
    exit();
}

// ...
if (isset($_POST["submit"])) {
    // responses for weight lifting at gym
    if (
        $_POST["exerciseLevel"] == "beginner" &&
        in_array("gym", $_POST["exerciseType"]) &&
        $_POST["preferredLocation"] == "gym"
    ) {
        $recommendedExercises = [
            "Goblet Squat",
            "Dumbbell Bench Press",
            "Bent Over Dumbbell Row",
            "Standing Dumbbell Shoulder Press",
            "Dumbbell Romanian Deadlift",
        ];
    } elseif (
        $_POST["exerciseLevel"] == "intermediate" &&
        in_array("gym", $_POST["exerciseType"]) &&
        $_POST["preferredLocation"] == "gym"
    ) {
        $recommendedExercises = [
            "Barbell Squat",
            "Barbell Bench Press",
            "Deadlift",
            "Barbell Bent Over Row",
            "Barbell Overhead Press",
        ];
    } elseif (
        $_POST["exerciseLevel"] == "advanced" &&
        in_array("gym", $_POST["exerciseType"]) &&
        $_POST["preferredLocation"] == "gym"
    ) {
        $recommendedExercises = [
            "Front Squat",
            "Weighted Pull-ups",
            "Deadlift",
            "Clean and Jerk",
            "Snatch",
            "Barbell Romanian Deadlift",
        ];
    }

    //responses for Yoga at home
    elseif (
        ($_POST["exerciseLevel"] == "beginner" &&
            in_array("yoga", $_POST["exerciseType"]) &&
            $_POST["preferredLocation"] == "home") ||
        $_POST["preferredLocation"] == "gym"
    ) {
        $recommendedExercises = [
            "Cat-Cow Pose",
            "Downward-Facing Dog",
            "Warrior",
            "Tree Pose",
            "Child Pose",
        ];
    } elseif (
        ($_POST["exerciseLevel"] == "intermediate" &&
            in_array("yoga", $_POST["exerciseType"]) &&
            $_POST["preferredLocation"] == "home") ||
        $_POST["preferredLocation"] == "gym"
    ) {
        $recommendedExercises = [
            "Crow Pose",
            "Extended Triangle Pose",
            "Camel Pose",
            "Pigeon Pose",
            "Bridge Pose",
        ];
    } elseif (
        ($_POST["exerciseLevel"] == "advanced" &&
            in_array("yoga", $_POST["exerciseType"]) &&
            $_POST["preferredLocation"] == "home") ||
        $_POST["preferredLocation"] == "gym"
    ) {
        $recommendedExercises = [
            "Headstand",
            "Handstand",
            "Scorpion Pose",
            "Wheel Pose",
            "King Pigeon Pose",
        ];
    }

    //responses for dance at home and outdoors
    elseif (
        ($_POST["exerciseLevel"] == "beginner" &&
            in_array("dance", $_POST["exerciseType"]) &&
            $_POST["preferredLocation"] == "home") ||
        $_POST["preferredLocation"] == "outdoors"
    ) {
        $recommendedExercises = [
            "Step Touch",
            "Grapevine",
            "Box Step",
            "Hip Circle",
            "Basic Salsa Steps",
        ];
    } elseif (
        ($_POST["exerciseLevel"] == "intermediate" &&
            in_array("dance", $_POST["exerciseType"]) &&
            $_POST["preferredLocation"] == "home") ||
        $_POST["preferredLocation"] == "outdoors"
    ) {
        $recommendedExercises = [
            "Cha-Cha Basic Steps",
            "Waltz Box Step with Turn",
            "Rumba Basic Steps",
            "Charleston",
            "Jazz Square",
        ];
    } elseif (
        ($_POST["exerciseLevel"] == "advanced" &&
            in_array("dance", $_POST["exerciseType"]) &&
            $_POST["preferredLocation"] == "home") ||
        $_POST["preferredLocation"] == "outdoors"
    ) {
        $recommendedExercises = [
            "Samba Basic Steps",
            "Tango Basic Steps with Promenade",
            "Swing Dance",
            "Quickstep",
            "Jive Basic Steps",
        ];
    } else {
        $recommendedExercises = [
            "Jumping Jacks",
            "Swimming",
            "Jump Rope",
            "Box Jumps",
            "Battle Ropes"
        ];
    }

    $response = saveWorkoutPreferences(
        $_SESSION["user"],
        $_POST["gender"],
        $_POST["age"],
        $_POST["height"],
        $_POST["weight"],
        $_POST["exerciseLevel"],
        $_POST["goals"],
        $_POST["exerciseType"],
        $_POST["preferredLocation"],
        $_POST["exerciseDuration"]
    );
    saveExercises($_SESSION["user"], $recommendedExercises);
    if ($response) {
        $result = "Routine generated!";
    } else {
        $result = "Error, no routine generated.";
    }
    $_SESSION["routine"] = true;

    // Calculate BMI
    $heightInMeters = $_POST["height"] / 100; // Convert height from cm to meters
    $weightInKg = $_POST["weight"] * 0.453592; // Convert weight from lbs to kg
    $bmi = round($weightInKg / pow($heightInMeters, 2), 1);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link type="text/css" rel="stylesheet" href="workoutcustomizer.css" />
    <title>Workout Routine Builder - GymFlow</title>
    </style>
    <title>Workout Routine Builder - GymFlow</title>
</head>

<body>

<div class="navbar">
    <a href="index.php">Homepage</a>
    <div class="dropdown">
        <button class="dropbtn">Services
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="workoutcustomizer.php">Workout Customizer</a>
            <a href="caloriccounter.php">Calorie Tracker</a>
        </div>
    </div>
    <a href="schedule.php">Schedule</a>
    <a href="about.php">About Us</a>
    <a href="account.php">My Account</a>
</div>

<h1>Workout Routine Builder</h1>

<style>
    #bmi, #form1 {
        width: 50%;
    }

    .container {
        display: flex;
        flex-wrap: wrap;
    }
</style>
<div class="container">
    <div id="form1">
        <form action="" method="post">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" min="10" max="120" required>

            <label for="height">Height (cm):</label>
            <input type="number" id="height" name="height" min="100" max="250" required>

            <label for="weight">Weight (lbs):</label>
            <input type="number" id="weight" name="weight" min="30" max="300" required>

            <label for="exerciseLevel">Exercise Level:</label>
            <select name="exerciseLevel" id="exerciseLevel" required>
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>

            <label for="goals">Goals:</label>
            <textarea name="goals" id="goals" placeholder="Enter your fitness goals here" required></textarea>

            <label for="exerciseType">Exercise Type:</label>
            <select name="exerciseType[]" id="exerciseType" multiple required>
                <option value="walking">Walking</option>
                <option value="jogging">Jogging</option>
                <option value="dance">Dance</option>
                <option value="yoga">Yoga</option>
                <option value="team_sport">Team Sport</option>
                <option value="i_dont_exercise">I Don't Exercise</option>
                <option value="gym">Gym</option>
            </select>

            <label for="preferredLocation">Preferred Location:</label>
            <select name="preferredLocation" id="preferredLocation" required>
                <option value="gym">Gym</option>
                <option value="home">Home</option>
                <option value="outdoors">Outdoors</option>
            </select>

            <label for="exerciseDuration">Exercise Duration (minutes):</label>
            <input type="number" id="exerciseDuration" name="exerciseDuration" min="5" max="180" required>

            <input type="submit" name="submit" value="Save Workout Preferences">
            <p><?php echo $result; ?></p>
        </form>
    </div>

    <div id="bmi">
        <h1>Your BMI</h1>
        <?php if ($bmi): ?>
            <p style="text-align: center; font-size: 24px">Your calculated BMI is: <?php echo $bmi; ?></p>
            <p style="text-align: center; font-size: 24px">
                <?php if ($bmi < 18.5) {
                    echo "Underweight";
                } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
                    echo "Normal weight";
                } elseif ($bmi >= 25 && $bmi <= 29.9) {
                    echo "Overweight";
                } else {
                    echo "Obese";
                } ?>
            </p>
        <?php endif; ?>
        <h1>Recommended Exercises</h1>

        <style>
            /* Add your custom CSS here */
            #exercises-list {
                list-style-type: none;
                padding: 0;
            }

            #exercises-list li {
                background-color: white;
                padding: 10px;
                border-radius: 5px;
                width: 75%;
                text-align: center;
                margin: 5px auto;
            }
        </style>
        <ul id="exercises-list">
            <?php foreach ($recommendedExercises as $exercise) {
                echo "<li>" . $exercise . "</li>";
            } ?>
        </ul>
    </div>
</div>
</body>
</html>
