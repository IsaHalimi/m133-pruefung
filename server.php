<?php
session_start();

function generateCharacter() {
    $zeichenvorratOptions = [
        'alpha' => 'ABCDEFGHJKMNPQTUVWXYZabcdefghjkmnpqtuvxyz',
        'alphanum' => 'ABCDEFGHJKMNPQTUVWXYZabcdefghjkmnpqtuvxyz2346789',
        'num' => '2346789'
    ];
    $vorrat = $zeichenvorratOptions[$_SESSION['zeichenvorrat']];
    do {
        $zufaelligesZeichen = $vorrat[rand(0, strlen($vorrat) - 1)];
    } while (isset($_SESSION['currentCharacter']) && $zufaelligesZeichen === $_SESSION['currentCharacter']);
    $_SESSION['currentCharacter'] = $zufaelligesZeichen;
    echo $zufaelligesZeichen;
}

if (isset($_GET['stats'])) {
    if (isset($_SESSION['startTime']) && isset($_SESSION['endTime']) && isset($_SESSION['anzahlTests'])) {
        $totalTime = $_SESSION['endTime'] - $_SESSION['startTime'];
        $averageTime = $totalTime / $_SESSION['anzahlTests'];

        echo json_encode([
            'totalTime' => $totalTime,
            'averageTime' => $averageTime,
            'correctPresses' => $_SESSION['correctPresses'],
            'wrongPresses' => $_SESSION['wrongPresses']
        ]);
    } else {
        echo json_encode(['error' => 'Statistiken nicht verfügbar']);
    }
    exit();
}

if (isset($_GET['reset'])) {
    session_unset();
    echo json_encode(['status' => 'reset_successful']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['zeichenvorrat']) && isset($_POST['anzahlTests'])) {
        $_SESSION['zeichenvorrat'] = $_POST['zeichenvorrat'];
        $_SESSION['anzahlTests'] = $_POST['anzahlTests'];
        $_SESSION['currentTest'] = 0;
        $_SESSION['correctPresses'] = 0;
        $_SESSION['wrongPresses'] = 0;
        $_SESSION['startTime'] = time();
        generateCharacter();
    }

    if (isset($_POST['keyPress'])) {
        if ($_SESSION['currentTest'] >= $_SESSION['anzahlTests']) {
            echo "STOP";
            exit();
        }

        $pressedKey = $_POST['keyPress'];
        $correctKey = $_SESSION['currentCharacter'];

        if ($pressedKey === $correctKey) {
            $_SESSION['correctPresses'] += 1;
        } else {
            $_SESSION['wrongPresses'] += 1;
        }

        $_SESSION['currentTest'] += 1;
        if ($_SESSION['currentTest'] >= $_SESSION['anzahlTests']) {
            $_SESSION['endTime'] = time();
            echo "STOP";
            exit();
        } else {
            generateCharacter();
        }
    }
}
?>
