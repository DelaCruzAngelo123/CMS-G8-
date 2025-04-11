<?php
session_start();

// Reset all session data if the reset button is clicked
if (isset($_POST['reset'])) {
    session_unset(); // Clear all session variables
    session_destroy(); // Destroy the session
    header("Location: main.php"); // Redirect to the same page to refresh
    exit();
}

// Initialize the divs array in the session if not already set
if (!isset($_SESSION['divs'])) {
    $_SESSION['divs'] = [];
}

// Retrieve values with defaults
$fontColor = $_SESSION['font_color'] ?? "black";
$bgColor = $_SESSION['bg_color'] ?? "white";
$textAlign = $_SESSION['text_align'] ?? "center";
$fontSize = $_SESSION['font_size'] ?? "16px";
$navbarColor = $_SESSION['navbar_color'] ?? "#333";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: <?php echo htmlspecialchars($bgColor); ?>;
            color: <?php echo htmlspecialchars($fontColor); ?>;
        }
        .navbar {
            background-color: <?php echo htmlspecialchars($navbarColor); ?>;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar button {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            font-size: 18px;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }
        .navbar button:hover {
            background-color: #575757;
            border-radius: 5px;
        }
        .main-content {
            display: flex;
            height: calc(100vh - 60px); /* Adjust height to exclude navbar */
        }
        .content-area {
            flex: 3;
            padding: 20px;
            overflow-y: auto;
            position: relative;
        }
        .sidebar {
            flex: 1;
            background-color: #f8d7da;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh; /* Full height of the viewport */
            overflow-y: auto; /* Enable vertical scrolling */
            position: sticky; /* Keep the sidebar in place */
            top: 0;
        }
        .sidebar input, .sidebar button {
            margin: 10px 0;
            padding: 10px;
            width: 90%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .sidebar button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        .sidebar button:hover {
            background-color: #0056b3;
        }
        .resizable-div {
            position: absolute;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            resize: both;
            overflow: auto;
            width: 200px;
            height: 150px;
        }
        .free-text {
            position: absolute;
            font-size: 16px;
            color: black;
            cursor: move;
        }
    </style>
    <script>
        let selectedDiv = null;
        let selectedText = null;

        function createResizableDiv() {
            const contentArea = document.querySelector('.content-area');
            const newDiv = document.createElement('div');
            newDiv.classList.add('resizable-div');
            newDiv.style.top = '50px';
            newDiv.style.left = '50px';
            newDiv.style.position = 'absolute';

            // Select the div when clicked
            newDiv.addEventListener('click', () => {
                selectedDiv = newDiv;
                updateSidebarInputs(newDiv);
            });

            contentArea.appendChild(newDiv);
            selectedDiv = newDiv;
            updateSidebarInputs(newDiv);
        }

        function addFreeText() {
            const contentArea = document.querySelector('.content-area');
            const text = document.getElementById('free-text').value;

            if (!text.trim()) {
                alert('Please enter some text!');
                return;
            }

            const newText = document.createElement('p');
            newText.classList.add('free-text');
            newText.textContent = text;
            newText.style.top = '100px';
            newText.style.left = '100px';
            newText.style.position = 'absolute';

            // Select the text when clicked
            newText.addEventListener('click', () => {
                selectedText = newText;
                updateTextInputs(newText);
            });

            contentArea.appendChild(newText);
            selectedText = newText;
            updateTextInputs(newText);
        }

        function updateSidebarInputs(div) {
            document.getElementById('div-width').value = div.style.width.replace('px', '');
            document.getElementById('div-height').value = div.style.height.replace('px', '');
            document.getElementById('div-top').value = div.style.top.replace('px', '');
            document.getElementById('div-left').value = div.style.left.replace('px', '');
            document.getElementById('div-bg-color').value = div.style.backgroundColor || '#f2f2f2';
        }

        function updateDivProperties() {
            if (!selectedDiv) return;

            const width = document.getElementById('div-width').value;
            const height = document.getElementById('div-height').value;
            const top = document.getElementById('div-top').value;
            const left = document.getElementById('div-left').value;
            const bgColor = document.getElementById('div-bg-color').value;

            selectedDiv.style.width = `${width}px`;
            selectedDiv.style.height = `${height}px`;
            selectedDiv.style.top = `${top}px`;
            selectedDiv.style.left = `${left}px`;
            selectedDiv.style.backgroundColor = bgColor;
        }

        function deleteSelectedDiv() {
            if (!selectedDiv) {
                alert('No div selected to delete!');
                return;
            }
            selectedDiv.remove();
            selectedDiv = null;

            // Clear the sidebar inputs
            document.getElementById('div-width').value = '';
            document.getElementById('div-height').value = '';
            document.getElementById('div-top').value = '';
            document.getElementById('div-left').value = '';
            document.getElementById('div-bg-color').value = '';
        }

        function updateTextInputs(textElement) {
            document.getElementById('text-top').value = textElement.style.top.replace('px', '');
            document.getElementById('text-left').value = textElement.style.left.replace('px', '');
            document.getElementById('text-font-size').value = textElement.style.fontSize.replace('px', '');
            document.getElementById('text-color').value = textElement.style.color || 'black';
        }

        function updateTextProperties() {
            if (!selectedText) return;

            const top = document.getElementById('text-top').value;
            const left = document.getElementById('text-left').value;
            const fontSize = document.getElementById('text-font-size').value;
            const color = document.getElementById('text-color').value;

            selectedText.style.top = `${top}px`;
            selectedText.style.left = `${left}px`;
            selectedText.style.fontSize = `${fontSize}px`;
            selectedText.style.color = color;
        }

        function deleteSelectedText() {
            if (!selectedText) {
                alert('No text selected to delete!');
                return;
            }
            selectedText.remove();
            selectedText = null;

            // Clear the sidebar inputs
            document.getElementById('text-top').value = '';
            document.getElementById('text-left').value = '';
            document.getElementById('text-font-size').value = '';
            document.getElementById('text-color').value = '';
        }
    </script>
</head>
<body>



<!-- Main Content -->
<div class="main-content">
    <!-- Content Area -->
    <div class="content-area">
        
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
       
        <h3>Div Block</h3>
         <button type="button" onclick="createResizableDiv()">Create Div Block</button>
        <label for="div-width">Width (px):</label>
        <input type="number" id="div-width" oninput="updateDivProperties()">

        <label for="div-height">Height (px):</label>
        <input type="number" id="div-height" oninput="updateDivProperties()">

        <label for="div-top">Top (px):</label>
        <input type="number" id="div-top" oninput="updateDivProperties()">

        <label for="div-left">Left (px):</label>
        <input type="number" id="div-left" oninput="updateDivProperties()">

        <label for="div-bg-color">Background Color:</label>
        <input type="text" id="div-bg-color" oninput="updateDivProperties()" placeholder="e.g., #f2f2f2">

        <h3>Adjust Text</h3>
        <label for="free-text">Add Text:</label>
        <input type="text" id="free-text" placeholder="Enter text here">
        <button type="button" onclick="addFreeText()">Add Text</button>

        <label for="text-top">Top (px):</label>
        <input type="number" id="text-top" oninput="updateTextProperties()">

        <label for="text-left">Left (px):</label>
        <input type="number" id="text-left" oninput="updateTextProperties()">

        <label for="text-font-size">Font Size (px):</label>
        <input type="number" id="text-font-size" oninput="updateTextProperties()">

        <label for="text-color">Text Color:</label>
        <input type="text" id="text-color" oninput="updateTextProperties()" placeholder="e.g., black">

        <button type="button" onclick="deleteSelectedDiv()">Delete Div</button>
        <button type="button" onclick="deleteSelectedText()">Delete Text</button>

        <form method="post">
            <button type="submit" name="reset">Reset All</button>
        </form>
    </div>
</div>

</body>
</html>
