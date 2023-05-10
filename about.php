<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link type="text/css" rel="stylesheet" href="gymflow.css" />
    <title>About Us - GymFlow</title>
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

<div class="aboutpage">
    <h1 id="aboutgym">Discover GymFlow</h1>
    <p id="about1">GymFlow is the brainchild of a group of dedicated Middle Tennessee State University students who share a passion for fitness and well-being. Our mission is to provide a user-friendly platform that helps individuals overcome the intimidation often associated with exercise and make informed decisions about their workout routines.</p>
    <br>
    <h2 id="whatwedo" style="text-align: center; text-decoration: underline;">What We Do</h2>
    <p id="about2">GymFlow offers a comprehensive suite of tools to help you create a fully personalized workout regimen, no matter your fitness level or experience. We understand that navigating the world of exercise can be overwhelming, which is why our platform is designed to guide you in developing a routine that is tailored to your unique needs and goals.</p>
    <br>
    <p id="about3">Our team has invested countless hours in creating GymFlow to ensure it caters to a wide range of users, from beginners and homebodies to bodybuilders and athletes. We're confident that GymFlow will empower you to take control of your fitness journey and lead a healthier, happier life.</p>
</div>

<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Middle_Tennessee_MT_Logomark.svg/1200px-Middle_Tennessee_MT_Logomark.svg.png" alt="Fitness Image">

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
