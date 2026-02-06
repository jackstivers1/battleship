<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Battleship PvC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Battleship: Player vs Computer</h1>

<div class="boards">
    <div>
        <h2>Your Board</h2>
        <div id="playerBoard" class="board"></div>
    </div>

    <div>
        <h2>Enemy Board</h2>
        <div id="computerBoard" class="board"></div>
    </div>
</div>

<button onclick="resetGame()">Reset Game</button>

<script src="script.js"></script>
</body>
</html>
