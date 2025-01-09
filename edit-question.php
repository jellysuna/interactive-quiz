<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quizsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionText = $_POST['question_text'];

    // Update the question text
    $sql = "UPDATE question SET question_text=? WHERE question_id=1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $questionText);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/tablogo.png" type="image/png">

    <title>Quiz - Edit</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=San+Francisco:wght@400;700&display=swap');

        html {
            height: 100%;
            background-image: url("img/bgimg.png");
            background-size: cover; /* Ensures the image covers the entire background */
            background-repeat: no-repeat; /* Prevents repeating the image */
            background-position: center center; /* Centers the background image */
        }

        body {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #logo {
            width: 150px;
            margin-bottom: 20px;
        }

        h1{
            color: #032250; 
        }

         .settings-container {
            padding: 20px 30px 40px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color: #f9f9f9;
            width: 550px; /* Fixed width */
            box-sizing: border-box;
        }

        .settings-form {
            display: flex;
            flex-direction: column;
        }

        .settings-form label {
            margin: 10px 0 15px;
            color: #032250;
        }

        .settings-form input {
            margin-bottom: 30px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .settings-form button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #5B9CFF;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .settings-form button:hover {
            background-color: #9DC2FA;
        }
        @media screen and (max-width: 768px) {
            .settings-container {
                width: 100%; /* Increase the width */
                min-width: 360px; /* Ensure minimum width */
                padding: 20px 20px 30px; /* Adjust padding */
            }

            .settings-form input, .settings-form button {
                font-size: 14px;
            }
             #logo {
                width: 100px;
                margin-bottom: 10px;
            }

            h1{
                font-size:24px;
                font-family: -apple-system, BlinkMacSystemFont, "San Francisco", "Helvetica Neue", Helvetica, Arial, sans-serif;
            }

            .settings-form label{
                font-family: -apple-system, BlinkMacSystemFont, "San Francisco", "Helvetica Neue", Helvetica, Arial, sans-serif;
            }

            .settings-form input{
                font-size:16px;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="container">
        <img id="logo" src="img/BTA png logo.png" alt="Logo"><br>
        <div class="settings-container">
            <h1>Edit Question</h1>
            <form class="settings-form" id="settingsForm" method="post" action="">
                <label for="question_text">Enter your question here:</label>
                <input type="text" id="question_text" name="question_text" required>
                <button type="submit">Save Question</button>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#settingsForm').on('submit', function(event) {
                event.preventDefault();
                var questionText = $('#question_text').val();

                $.ajax({
                    url: 'edit-question.php',
                    type: 'POST',
                    data: { question_text: questionText },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Question updated successfully!');
                        } else {
                            alert('Error: ' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>
</html>
