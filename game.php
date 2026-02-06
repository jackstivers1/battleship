<?php
session_start();
header("Content-Type: application/json");

$size = 8;
$ships = 5;

function emptyBoard($size) {
    return array_fill(0, $size, array_fill(0, $size, 0));
}

function placeShips(&$board, $count, $size) {
    while ($count > 0) {
        $r = rand(0, $size - 1);
        $c = rand(0, $size - 1);
        if ($board[$r][$c] === 0) {
            $board[$r][$c] = 1;
            $count--;
        }
    }
}

function initGame($size, $ships) {
    $_SESSION["playerBoard"] = emptyBoard($size);
    $_SESSION["computerBoard"] = emptyBoard($size);
    $_SESSION["playerShots"] = [];
    $_SESSION["computerShots"] = [];

    placeShips($_SESSION["playerBoard"], $ships, $size);
    placeShips($_SESSION["computerBoard"], $ships, $size);
}

if (!isset($_SESSION["playerBoard"])) {
    initGame($size, $ships);
}

$data = json_decode(file_get_contents("php://input"), true);

/* RESET */
if (isset($data["reset"])) {
    initGame($size, $ships);
    echo json_encode(["status" => "reset"]);
    exit;
}

/* PLAYER SHOOT */
if (isset($data["row"], $data["col"])) {
    $r = $data["row"];
    $c = $data["col"];

    if (isset($_SESSION["playerShots"][$r][$c])) {
        echo json_encode(["error" => "already"]);
        exit;
    }

    $_SESSION["playerShots"][$r][$c] = true;
    $playerHit = $_SESSION["computerBoard"][$r][$c] === 1;

    /* COMPUTER TURN */
    do {
        $cr = rand(0, $size - 1);
        $cc = rand(0, $size - 1);
    } while (isset($_SESSION["computerShots"][$cr][$cc]));

    $_SESSION["computerShots"][$cr][$cc] = true;
    $computerHit = $_SESSION["playerBoard"][$cr][$cc] === 1;

    echo json_encode([
        "player" => [
            "row" => $r,
            "col" => $c,
            "hit" => $playerHit
        ],
        "computer" => [
            "row" => $cr,
            "col" => $cc,
            "hit" => $computerHit
        ]
    ]);
    exit;
}
