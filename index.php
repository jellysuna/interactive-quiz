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

// Fetch the question text
$sql = "SELECT question_text FROM question WHERE question_id=1"; // Adjust the condition if needed
$result = $conn->query($sql);

$questionText = "Responses"; // Default value
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
    <title>Response Display</title>
    <style>
        .response-container {
            position: relative;
            width: 86%;
            height: 600px;
            margin: auto;
            overflow: visible;
            border-radius: 5px;
            padding-top: 30px;
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
            font-family: Arial, Helvetica, sans-serif;
        }
        .reset-button {
            margin-top: 40px;
            margin-bottom:25px;
            margin-left:40px;
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
            margin:40px;
            color:#373737;
            margin-bottom:25px;
            font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
        body{
            background-image: url("img/bg img.png");
        }
    </style>
</head>
<body>
<div class="main-section">
    <h1 id="displayLabel"><?php echo $questionText; ?></h1>
    <div class="response-container" id="responseContainer">
    </div>
    <button class="reset-button" id="resetButton">Reset Responses</button>
</div>

<script>
        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min)) + min;
        }

        const colors = ['#ffd700', '#2790ef', '#112A46', '#3D9555', '#B3E400', '#FF4343'];
        function getRandomColor() {
            return colors[Math.floor(Math.random() * colors.length)];
        }

        function getRandomRotation() {
            const angles = [0, 90, -90];
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
            div.style.transform = 'rotate(' + getRandomRotation() + 'deg)';

            // Adjust font size based on frequency
            const baseFontSize = 14; // Smaller base size
            const increment = 6; // Larger increment per frequency
            div.style.fontSize = (baseFontSize + frequency * increment) + 'px';

            container.appendChild(div);

            let placed = false;

            const centerX = container.clientWidth / 2;
            const centerY = container.clientHeight / 2;
            const radius = Math.min(centerX, centerY) * 0.8; // Adjust the radius to fit your needs
            const angleStep = 0.1; // Adjust the angle step to control the spiral density

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
            const responseContainer = document.getElementById('responseContainer');
            const responses = JSON.parse(localStorage.getItem('responses')) || [];

            // Clear old responses before adding new ones
            responseContainer.innerHTML = '';

            // Calculate frequency of each response
            const frequencyMap = responses.reduce((acc, text) => {
                acc[text] = (acc[text] || 0) + 1;
                return acc;
            }, {});

            const placedElems = [];
            for (const [text, frequency] of Object.entries(frequencyMap)) {
                placeResponse(responseContainer, text, placedElems, frequency);
            }
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            const resetButton = document.getElementById('resetButton');

            updateLabels();
            updateResponses();

            window.addEventListener('storage', function(event) {
                if (event.key === 'responseLabel') {
                    updateLabels();
                }
                if (event.key === 'responses') {
                    updateResponses();
                }
            });

            resetButton.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete all responses?')) {
                    localStorage.removeItem('responses');
                    updateResponses();
                    alert('All responses have been cleared.');
                }
            });
        });
    </script>
</body>
</html>
