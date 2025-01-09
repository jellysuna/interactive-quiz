<?php
require 'config.php';

if (isset($_GET['action']) && $_GET['action'] === 'fetch_responses') {
    $sql = "SELECT responses_text FROM responses";
    $result = $conn->query($sql);

    $responses = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $responses[] = $row['responses_text'];
        }
    }

    echo json_encode($responses);
    $conn->close();
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'fetch_question') {
    $sql = "SELECT question_text FROM question WHERE question_id=1";
    $result = $conn->query($sql);

    $response = [];
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['question_text'] = $row['question_text'];
    } else {
        $response['question_text'] = "Responses";
    }

    echo json_encode($response);
    $conn->close();
    exit();
}

$sql = "SELECT question_text FROM question WHERE question_id=1";
$result = $conn->query($sql);

$questionText = "Responses";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $questionText = $row['question_text'];
}

$sql = "SELECT responses_text FROM responses";
$result = $conn->query($sql);

$responses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $responses[] = $row['responses_text'];
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/wq-tablogo.png" type="image/png">

    <title>Response Display</title>
    <style>
        .response-container {
            position: relative;
            width: 86%;
            height: 600px;
            margin: auto;
            overflow: visible;
            border-radius: 5px;
            padding-top: 20px;
            margin-bottom: 70px;
            padding-bottom: 20px;
        }
        .response {
            position: absolute;
            white-space: normal;
            word-break: break-word;
            text-align: center;
            color: white;
            padding: 5px;
            box-sizing: border-box;
            transition: transform 0.2s, font-size 0.2s; /* Smooth scaling transition */
            max-width: 150px;
            font-family: "Archivo Black", sans-serif;
        }
        .reset-button {
            margin-top: 18px;
            margin-bottom: 25px;
            margin-left: 40px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #ff4c4c;
            color: white;
            font-size: 16px;
            cursor: pointer;
            display: block;
        }
        .reset-button:hover {
            background-color: #e43b3b;
        }
        #displayLabel {
            font-size: 45px;
            margin: 40px;
            color: #373737;
            margin-bottom: 15px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
        body {
            background-image: url("img/bg img2.png");
            font-family: 'Roboto', Arial, Helvetica, sans-serif;
        }
        .image-container {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px; /* Space between the images */
        }

        .tab-logo {
            width: 100px;
            height: 100px;
        }

        .qr-code {
            width: 120px;
            height: 120px;
        }
        @media screen and (max-width: 768px) {
            .response-container {
                width: 80%;
                height: auto;
                margin: 265px 60px 60px;
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

            .image-container {
                flex-direction: column;
                align-items: flex-end;
                gap: 5px; /* Adjust space between the images */
                top: 10px;
                right: 10px;
            }

            .tab-logo, .qr-code {
                width: 50px;
                height: 50px;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="main-section">
    <h1 id="displayLabel"><?php echo $questionText; ?></h1>
    <div class="image-container">
        <img src="img/wq-tablogo.png" class="tab-logo" alt="Tab Logo">
        <img src="img/qrcode.png" class="qr-code" alt="Join Quiz QR Code">
    </div>
    <button class="reset-button" id="resetButton">Reset Responses</button>

    <div class="response-container" id="responseContainer">
        <?php foreach ($responses as $response): ?>
            <div class="response"><?php echo htmlspecialchars($response); ?></div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    }

    const colors = ['#ffd700', '#2790ef', '#186fff', '#ff99cd', '#07b87f', '#ff423e', '#f3c138', '#b333de'];
    function getRandomColor() {
        return colors[Math.floor(Math.random() * colors.length)];
    }

    function getRandomRotation() {
        const angles = [-90, 0, 90];
        return angles[Math.floor(Math.random() * angles.length)];
    }

    function isOverlap(newElem, placedElems) {
        const newRect = newElem.getBoundingClientRect();
        for (let elem of placedElems) {
            const rect = elem.getBoundingClientRect();
            if (!(newRect.right < rect.left || newRect.left > rect.right || newRect.bottom < rect.top || newRect.top > rect.bottom)) {
                return true;
            }
        }
        return false;
    }

    function placeResponse(container, text, placedElems, frequency) {
        const div = document.createElement('div');
        div.className = 'response';
        div.textContent = text;
        div.style.color = getRandomColor();
        div.style.transform = `rotate(${getRandomRotation()}deg)`;

        const baseFontSize = 14;
        const increment = 6;
        div.style.fontSize = (baseFontSize + frequency * increment) + 'px';

        container.appendChild(div);

        let placed = false;

        const centerX = container.clientWidth / 2;
        const centerY = container.clientHeight / 2;
        const radius = Math.min(centerX, centerY) * 0.8;
        const angleStep = 0.1;

        let angle = 0;
        let currentRadius = 0;

        while (!placed) {
            const top = centerY - currentRadius * Math.sin(angle) - div.clientHeight / 2;
            const left = centerX + currentRadius * Math.cos(angle) - div.clientWidth / 2;

            div.style.top = top + 'px';
            div.style.left = left + 'px';

            if (!isOverlap(div, placedElems)) {
                placed = true;
                placedElems.push(div);
            } else {
                angle += angleStep;
                currentRadius += 1;
            }
        }
    }

    function updateResponses() {
        $.ajax({
            url: 'index.php?action=fetch_responses',
            method: 'GET',
            dataType: 'json',
            success: function(responses) {
                const responseContainer = document.getElementById('responseContainer');
                responseContainer.innerHTML = '';

                const frequencyMap = responses.reduce((acc, text) => {
                    acc[text] = (acc[text] || 0) + 1;
                    return acc;
                }, {});

                const placedElems = [];
                for (const [text, frequency] of Object.entries(frequencyMap)) {
                    placeResponse(responseContainer, text, placedElems, frequency);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error updating responses: ", error);
            }
        });
    }

    function fetchQuestionText() {
        $.ajax({
            url: 'index.php?action=fetch_question',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#displayLabel').text(response.question_text);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching question text: ", error);
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const resetButton = document.getElementById('resetButton');

        updateResponses();
        fetchQuestionText();

        resetButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete all responses?')) {
                window.location.href = 'reset_index.php';
            }
        });

        setInterval(fetchQuestionText, 5000);
        setInterval(updateResponses, 5000);
    });
</script>
</body>
</html>
