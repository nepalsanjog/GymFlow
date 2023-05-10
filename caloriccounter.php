<?php
// Import functions for the calorie tracker database.
require "calories.php";
// We save the date in the users session_id to save the position when
// they check past and future calorie logs.

if (!isset($_SESSION["user"])) {
    header("location: index.php");
    exit();
}

if ($_SESSION["date"] == null) {
    //If they don't have a date saved, save today's date.
    $_SESSION["date"] = date("Y/m/d");
}

if ($_SESSION["date"] != null) {
    $dataPoints = [];

    for ($i = 0; $i < 7; $i++) {
        $temp = date(
            "Y/m/d",
            strtotime("-" . $i . " day", strtotime($_SESSION["date"]))
        );
        $calories = retrieveCalorieSum($temp);
        if ($calories != null) {
            $dataPoints[] = ["DATE" => $temp, "CALORIES" => $calories];
        } else {
            $dataPoints[] = ["DATE" => $temp, "CALORIES" => 0];
        }
    }
    $json_data = json_encode($dataPoints);
}

// Empty result variable is used to view status of functions.
$result = "";
// If the user submits the new item form, and the values are not empty...
if (isset($_POST["submit"])) {
    if ($_POST["item"] != null && $_POST["cal"] != null) {
        // Add the item, the calorie count, the username, and the date it was added to the database.
        addMeal(
            $_POST["item"],
            $_POST["cal"],
            $_SESSION["user"],
            $_SESSION["date"]
        );
        // Return to result if needed for viewing.
        $result = "Successfully saved!";

        // Store the submitted data in the session
        $_SESSION["food_data"][] = [
            "food" => $_POST["item"],
            "calories" => $_POST["cal"],
            "date" => $_SESSION["date"],
        ];
    }
}
// If the user sends a GET request using the previous button...
if (isset($_GET["previous"])) {
    // Subtract the date by 1 day
    // We do this because the propagateHTML function pulls the users calorie logs according to
    // the date that's passed to the function.
    $_SESSION["date"] = date(
        "Y/m/d",
        strtotime("-1 day", strtotime($_SESSION["date"]))
    );
}
// If the user sends a GET request using the next button...
if (isset($_GET["next"])) {
    // Add 1 day to the date
    // We do this because the propagateHTML function pulls the users calorie logs according to
    // the date that's passed to the function.
    $_SESSION["date"] = date(
        "Y/m/d",
        strtotime("+1 day", strtotime($_SESSION["date"]))
    );
}
// If there's a request to reset the date...
if (isset($_GET["reset"])) {
    // Set the users session date to today's date.
    $_SESSION["date"] = date("Y/m/d");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link type="text/css" rel="stylesheet" href="gymflow.css" />
    <title>Calorie Tracker - GymFlow</title>

    <script>
        window.onload = function() {
            <?php echo "var data = $json_data;"; ?>
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "Daily Caloric Intake This Week"
                },
                axisX: {
                    title: "Date",
                    labelAngle: 0
                },
                axisY: {
                    title: "Calories"
                },
                data: [{
                    type: "bar",
                    dataPoints: data.map(function (d) {
                        return {label: d.DATE, y: d.CALORIES};
                    })
                }]
            });
            chart.render();
        }
    </script>

</head>

<body>
<H1>Calorie Tracker</H1>

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

<div class="calcon">
    <p id = "contactpar">Keep track of your daily calorie intake and achieve your fitness goals with our easy-to-use calorie tracking feature.</p>
    <img id = "img19" src="gymimg19.jpg" alt= "Gym image 19">
</div>

<style>
    .calcon {
        background: #025577;
        color: #FFF;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #img19{
        width:400px;
        height: auto;
        margin-left: 90px;
    }
</style>

<br><br><br><br>

<h2>Showing caloric intake for <?php echo $_SESSION["date"]; ?></h2>

<!-- This sends the request to the server to reset the users saved date in the calorie logger.  -->
<a href="?reset=true">
    <button name="reset">Reset date</button>
</a>

<div>
    <?php
    /*
     * propagateHTML()
     * This function uses retrieveCalories() from calories.php in order to
     * pull the users log for the given date from the database.
     * It then displays this information by iterating through it and adding
     * it to a table which is displayed for the user.
     *
     */
    function propagateHTML()
    {
        // Table start
        echo "<table>";
        // Add table headers
        echo "<tr><th>Food</th><th>Calories</th></tr>";
        // Attempt to pull data from the database based on the given date.
        $output = retrieveCalories($_SESSION["date"]);
        // Validate the output
        if ($output != null) {
            // $output is n associative array, therefore each value in this foreach loop
            // is an array of the rows that were pulled from the database.
            foreach ($output as $innerArray) {
                // This verifies that $innerArray, which represents a single row, is valid.
                if (is_array($innerArray)) {
                    // Start table row.
                    echo "<tr>";
                    // Start static variable sum, this is how we display the total calories of that given date.
                    static $sum = 0;
                    // This iterates through the provided row.
                    foreach ($innerArray as $value) {
                        // Add a row item for each value in this array
                        echo "<td>" . $value . "</td>";
                        // If $value is an int, which only occurs when $value = calories, then
                        // we add them to sum.
                        if (is_int($value)) {
                            // Add the value to the sum
                            $sum += $value;
                        }
                    }
                    // Close the row
                    echo "</tr>";
                } else {
                    // If it's not an array, then print the single value.
                    // This is really only here for testing/error logging purposes.
                    // Will probably be removed/reconfigured in future versions.
                    echo $innerArray;
                }
            }
        }
        // Close table
        echo "</table>";
        // Validate that $sum exists (otherwise this would result in an error)
        if (isset($sum)) {
            // If so, output the total.
            echo "<p> Total calories consumed: " . $sum . "</p>";
        } else {
            // Otherwise...
            echo "<p> No meals have been tracked! </p>";
        }
    }
    // Run the function
    propagateHTML();
    ?>
    <br>
</div>

<!-- This is the form for adding new meal items. -->
<form action="" method="post">
    <input type="text" id="item" name="item" placeholder="Add what you ate here...">
    <br>
    <input type="text" id="cal" name="cal" placeholder="Input caloric intake for this meal here...">
    <br>
    <input type="submit" name="submit" value="Save">
    <!-- This displays the value of result, which is typically a status or error message. -->
    <p> <?php echo $result; ?></p>
</form>

<a style="display: inline-flex; text-decoration: none; margin: 0 auto;" href="?previous=true">
    <input type="button" name="previous" value="<- Previous">
</a>
<a style="display: inline-flex; text-decoration: none; margin: 0 auto;" href="?next=true">
    <input type="button" name="next" value="Next ->">
</a>

<br>

<div id="chartContainer" style="height: 370px; width: 100%;"></div>

<br>

<style>
    button[name="reset"] {
        background-color: #025577;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
    }

    button[name="reset"]:hover {
        cursor: pointer;
        background-color: #003f5c;
    }
    form {
        background-color: white;
        padding: 20px;
        width:300px;
        border-radius: 10px;
        border: 5px solid #025577;
        margin: 0 auto;
        margin-top: 50px;
    }

    input[type="text"], input[type="submit"] {
        padding: 10px;
        margin-bottom: 10px;
        width: 100%;
        border-radius: 5px;
        border: 2px solid #025577;
    }

    input[type="submit"] {
        background-color: #025577;
        color: white;
        font-weight: bold;
    }

    p {
        color: white;
        font-weight: bold;
        text-align: center;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 20px;
    }

    input[type="button"] {
        padding: 10px;
        margin: 10px;
        background-color: white;
        color: #025577;
        font-weight: bold;
        border-radius: 5px;
        border: none;
        cursor: pointer;
    }

    input[type="button"]:hover {
        background-color: #025577;
        color: white;
    }
</style>

<style>
    .calcon {
        background: #025577;
        color: #FFF;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #img19{
        width:400px;
        height: auto;
        margin-left: 90px;
    }
</style>

<div class="contact-info">
    <h1 id = "contactinfo"> Contact </h1>

    <p id ="ct">Our Address: MTSU</p><br>
    <p id = "ct">Email: gymflow@gmail.com</p><br>
    <p id = "ct">Phone: 615-375-1242</p><br>
    <h5>© 2023 GymFlow</h5>
</div>

<style>
    .contact-info {
        justify-content: center;
        align-items: center;
        text-align: center;
        background-color: #454545;
        background-size:cover;
        width: 100%;
        height: 40%;
    }

    .contact-info p{
        display: inline-block;
        text-align:center;
        margin: 0;
        padding: 10px;
    }

    #contactinfo {
        font-size: 30px;
        text-decoration: underline;
        color:white;
    }
</style>

</body>
</html>
