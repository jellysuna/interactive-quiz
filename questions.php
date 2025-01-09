<?php
require 'config.php';

if (isset($_GET['action']) && $_GET['action'] === 'fetch_question') {
    $sql = "SELECT question_text FROM question WHERE question_id=1";
    $result = $conn->query($sql);

    $response = [];
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['question_text'] = $row['question_text'];
    } else {
        $response['question_text'] = "Question";
    }

    echo json_encode($response);
    $conn->close();
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'submit_response') {
    $responseText = trim($_POST['responseInput']);

    $response = ['success' => false];
    if (!empty($responseText)) {
        $sql = "INSERT INTO responses (responses_text) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $responseText);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['error'] = $stmt->error;
        }

        $stmt->close();
    }

    echo json_encode($response);
    $conn->close();
    exit();
}

$sql = "SELECT question_text FROM question WHERE question_id=1";
$result = $conn->query($sql);

$questionText = "Question";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $questionText = $row['question_text'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="img/wq-tablogo.png" type="image/png">

    <title>Quiz System</title>
       <style>
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

        #logo {
            width: 150px;
            margin-bottom: 20px;
        }
        #container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        #responseForm {
            display: flex;
            flex-direction: column;
            padding: 30px 40px 40px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 650px;
            width: 100%;
            box-sizing: border-box;
        }

        #responseForm label {
            margin-bottom: 30px;
            font-size: 28px;
            color: #032250;
            font-weight: bold;
        }

        #responseForm input[type="text"] {
            width: 95%;
            padding: 10px;
            margin-top: 15px;
            margin-bottom: 30px;
            margin-right: 120px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        #responseForm button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #5B9CFF;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #responseForm button:hover {
            background-color: #9DC2FA;
        }
        
        @media screen and (max-width: 768px) {
            #responseForm {
                max-width: 90%; /* Reduce the width */
                padding: 40px 20px; /* Adjust padding */
            }

            #logo {
                width: 100px;
                margin-bottom: 10px;
            }

            .response {
                font-size: 12px;
            }

            #displayLabel {
                font-size: 24px;
                margin: 20px;
                margin-bottom: 15px;
            }

            .reset-button {
                margin-top: 20px;
                margin-bottom: 15px;
                margin-left: 20px;
                padding: 5px 10px;
                font-size: 14px;
            }
             #responseForm input{
                 max-width:90%;
             }

             
            #responseForm label{
                font-size:23px;
                margin-bottom: 20px;

            }
        }
    </style>
</head>
<body>
     <div id="container">
        <img id="logo" src="img/wq-icon.png" alt="Logo">
        <form id="responseForm" method="post" action="questions.php">
            <label for="responseInput" id="responseLabel"><?php echo $questionText; ?></label>
            <input type="text" id="responseInput" name="responseInput" required>
            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            var responseLabel = $('#responseLabel');

            function fetchQuestionText() {
                $.ajax({
                    url: 'questions.php?action=fetch_question',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        responseLabel.text(response.question_text);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching question text: ", error);
                    }
                });
            }

            $('#responseForm').on('submit', function(e) {
                e.preventDefault();
                var responseInput = $('#responseInput').val();

                $.ajax({
                    url: 'questions.php',
                    method: 'POST',
                    data: { action: 'submit_response', responseInput: responseInput },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#responseInput').val('');
                            alert('Response submitted successfully!');
                            // Optionally, you can trigger a function to update responses in index.php
                            updateResponsesInResponsesPHP();
                        } else {
                            alert('Failed to submit response: ' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error submitting response: ", error);
                    }
                });
            });

            function updateResponsesInResponsesPHP() {
                $.ajax({
                    url: 'index.php?action=fetch_responses',
                    method: 'GET',
                    dataType: 'json',
                    success: function(responses) {
                        // Update the responses display in index.php
                        // This function should update the responses in the index.php page
                        console.log("Responses updated", responses);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error updating responses: ", error);
                    }
                });
            }

            fetchQuestionText();
            setInterval(fetchQuestionText, 5000);
        });
    </script>
</body>
</html>
