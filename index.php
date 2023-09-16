<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="styles.css">
<title>Zeichenvorräte Auswahl</title>
</head>
<body>
<div class="container">
    <h2>Zeichenvorräte Auswahl</h2>
    <form method="POST" action="server.php">  
    <div class="radio-group">
    <input type="radio" id="radio1" name="zeichenvorrat" value="alpha" class="radio-input" checked>
    <label for="radio1" class="radio-label">
      <span class="radio-inner-circle"></span>
      Alpha
    </label>
    
    <input type="radio" id="radio2" name="zeichenvorrat" value="alphanum" class="radio-input">
    <label for="radio2" class="radio-label">
      <span class="radio-inner-circle"></span>
      Alphanum
    </label>
    
    <input type="radio" id="radio3" name="zeichenvorrat" value="num" class="radio-input">
    <label for="radio3" class="radio-label">
      <span class="radio-inner-circle"></span>
      Num
    </label>
</div>
        <div class="anzahl">
            <label for="anzahlTests">Anzahl Tests:</label>
            <input type="number" id="anzahlTests" name="anzahlTests" min="1" value="5">
        </div>
        
        <div class="button">
            <button type="submit" id="startButton">Start</button>
            <div id="responseArea">Hier wird das zufällige Zeichen angezeigt</div>
            <br>
            <div id="resultArea"></div>
            <button type="button" id="restartButton" style="display: none;">Neuer Test Starten</button>
        </div>

    </form>
</div>
<script src="script.js"></script>
</body>
</html>
