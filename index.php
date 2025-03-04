<?php
session_start();
include 'config.php';

// पहले session check करें
if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
} 
// अगर session नहीं है लेकिन cookie है, तो session फिर से set करें
elseif (isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    $loggedIn = true;
} 
else {
    $loggedIn = false;
}

// अगर login में error था, तो उसे display करेंगे
$error = isset($_GET['error']) ? $_GET['error'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer News</title>
    <link rel="stylesheet" href="styles.css"> <!-- CSS File Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Login Modal (Only show if user is not logged in) -->
    <?php 
    if (!$loggedIn) { 
        include 'login.php'; 
    } 
    ?>

    <!-- News Section -->
    <main>
        <section class="news-container">
            <?php
                $sql = "SELECT * FROM news ORDER BY created_at DESC"; 
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<a href="news.php?id=' . $row["id"] . '" class="news-card">'; // A tag added here
                        echo '<img src="image/' . $row["image"] . '" alt="News Image">';
                        echo '<div class="news-content">';
                        echo '<h2>' . $row["title"] . '</h2>';
                        echo '<p class="news-date">Posted on: ' . date("d M Y, h:i A", strtotime($row["created_at"])) . '</p>';

                        if ($row["updated_at"] !== $row["created_at"]) {
                            echo '<p class="news-update">Updated on: ' . date("d M Y, h:i A", strtotime($row["updated_at"])) . '</p>';
                        }

                        echo '</div>';
                        echo '</a>'; // Closing A tag
                    }
                } else {
                    echo "<p>No news available</p>";
                }
            ?>
        </section>
    </main>
    
    <!-- Bottom Navigation -->
    <?php include 'nav.php'; ?>

    <script>
        // अगर URL में ?error= लिखा है तो modal खोलो और error दिखाओ
        let params = new URLSearchParams(window.location.search);
        if (params.has('error')) {
            let errorMessage = params.get('error');
            document.getElementById("loginError").innerText = errorMessage;
            openLogin();
        }
    </script>
    <script>
    let params = new URLSearchParams(window.location.search);

    // अगर रजिस्टर में error आया तो register modal खोलो
    if (params.has('register_error')) {
        document.getElementById("registerError").innerText = params.get('register_error');
        openRegister();
    }

    // अगर registration success हुआ तो login modal खोलो और message दिखाओ
    if (params.has('register_success')) {
        document.getElementById("loginError").innerText = "Registration successful! Please login.";
        openLogin();
    }
</script>



</body>
</html>
