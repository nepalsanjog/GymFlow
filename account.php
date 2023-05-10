<?php
require "workout.php";
if (!isset($_SESSION["user"])) {
    header("location: index.php");
    exit();
}

if (isset($_GET["logout"])) {
    logoutUser();
    header("location: index.php");
}

if (isset($_GET["confirm-account-deletion"])) {
    deleteAccount();
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link type="text/css" rel="stylesheet" href="gymflow.css" />
    <title>Account Details - GymFlow</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5BB5FB;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 40px;
        }

        .accbutton {
            font-size: 16px;
            padding: 10px 20px;
            background-color: #025577;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .accbutton a {
            text-decoration: none;
            color: #fff;
        }

        .accbutton:hover {
            background-color: #01384a;
        }

        h3 {
            margin-bottom: 20px;
        }
    </style>
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

<h1>Hello, <?php echo $_SESSION["user"]; ?>.</h1>

<div>
    <?php
    $data = retrieveUserInfo($_SESSION["user"]);
    if ($data != null) {
        echo "<p>Gender: " . ucfirst($data["gender"]) . "</p><br>";
        echo "<p>Age: " . $data["age"] . "</p><br>";
        echo "<p>Weight: " . $data["weight"] . "</p><br>";
        echo "<p>Height: " . $data["height"] . "</p><br>";

        $heightInMeters = $data["height"] / 100; // Convert height from cm to meters
        $weightInKg = $data["weight"] * 0.453592; // Convert weight from lbs to kg
        $bmi = round($weightInKg / pow($heightInMeters, 2), 1);

        echo "<p>BMI: " . $bmi . "</p><br>";
        echo "<p>Skill Level: " . $data["exercise_level"] . "</p><br>";
        echo "<p>Current Goals: " . $data["goals"] . "</p>";
    }
    ?>

    <h1>Here are your recommended exercises:</h1>

    <?php if ($_SESSION["routine"]) {
        consoleLog($_SESSION["user"]);
        $data = listExercises($_SESSION["user"]);
        if ($data != null) {
            foreach ($data as $exercise) {
                echo "<p>" . $exercise . "</p><br>";
            }
        }
        echo "<br>";
    } else {
        echo "You haven't created any routines!" . "<br>";
    } ?>
</div>

<button class="accbutton"><a href="?logout">Logout</a></button>
<button class="accbutton"><a href="?confirm-account-deletion">Delete account</a></button>

<div>
</div>

</body>

</html>
