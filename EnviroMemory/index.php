<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da memória</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<img src="./images/LogoEnviroMemory.png" id="logomain">

<?php
session_start();

function generateResetButton() {
    echo '
    <div class="select" style="text-align: center;padding:2em">
        <form method="POST">
            <input type="hidden" name="acao" value="reset" />
            <input type="submit" value="Reset" style="font-size: 1.8em; background-color:red; color: white;" />
        </form>
    </div>';
}

function generateStartButton() {
    echo '
    <div class="select" style="text-align: center;padding:2em">
        <form method="POST">
            <input type="hidden" name="acao" value="start" />
            <input type="submit" value="Start" style="font-size: 1.8em; background-color:green; color: white;" />
        </form>
    </div>';
}

require_once 'tabuleiros.php';

if (isset($_POST['acao']) && $_POST['acao'] == "reset") {
    session_destroy();
    session_start();
    unset($_GET['l']);
    unset($_GET['c']);
    header("Location: index.php");
}

if ((isset($_POST['acao']) && $_POST['acao'] == "reset") || isset($_POST['acao']) && $_POST['acao'] == "start") {
    $tabEscolhido = rand(0, 2);
    $_SESSION['tabEscolhido'] = $tabEscolhido;
    $_SESSION['tabResultado'] = [
        [false, false, false, false],
        [false, false, false, false],
        [false, false, false, false],
        [false, false, false, false]
    ];
}

if (!isset($_SESSION['numero_jogadas']) || $_SESSION['numero_jogadas'] >= 2) {
    $_SESSION['numero_jogadas'] = 1;
} else {
    $_SESSION['numero_jogadas'] = $_SESSION['numero_jogadas'] + 1;
}

if (isset($_SESSION['numero_jogadas']) && $_SESSION['numero_jogadas'] == 2 && !isset($_POST['acao'])) {
    if (isset($_GET['l']) && isset($_GET['c']) && isset($_SESSION['valorLinha']) && isset($_SESSION['valorColuna']) && $tabuleiros[$_SESSION['tabEscolhido']][$_GET['l']][$_GET['c']] == $tabuleiros[$_SESSION['tabEscolhido']][$_SESSION['valorLinha']][$_SESSION['valorColuna']]) {
        $_SESSION['tabResultado'][$_GET['l']][$_GET['c']] = true;
        $_SESSION['tabResultado'][$_SESSION['valorLinha']][$_SESSION['valorColuna']] = true;
    }
}

if (
    isset($_GET["l"]) && isset($_GET["c"])
    && isset($_SESSION['numero_jogadas']) && $_SESSION['numero_jogadas'] == 1
) {
    $_SESSION['valorLinha'] = $_GET['l'];
    $_SESSION['valorColuna'] = $_GET['c'];
}

if (isset($_SESSION['tabResultado'])) {
    echo '<div class="tabuleiro-container">';
    echo '<table class="table">';
    for ($l = 0; $l < 4; $l++) {
        echo '<tr>';
        for ($c = 0; $c < 4; $c++) {
            if (isset($_GET["l"]) && isset($_GET["c"]) && $_GET['l'] == $l && $_GET['c'] == $c) {
                echo '<td>';
                echo '<a href="?l=' . $l . '&c=' . $c . '">';
                echo '<img src="images/' . $tabuleiros[$_SESSION['tabEscolhido']][$l][$c] . '.png">';
                echo '</a>';
                echo '</td>';
            } elseif ($_SESSION['numero_jogadas'] >= 1 && isset($_SESSION['valorLinha']) && isset($_SESSION['valorColuna']) && ($_SESSION['valorLinha'] == $l && $_SESSION['valorColuna'] == $c)) {
                echo '<td>';
                echo '<a href="?l=' . $_SESSION['valorLinha'] . '&c=' . $_SESSION['valorColuna'] . '">';
                echo '<img src="images/' . $tabuleiros[$_SESSION['tabEscolhido']][$_SESSION['valorLinha']][$_SESSION['valorColuna']] . '.png">';
                echo '</a>';
                echo '</td>';
            } elseif (isset($_SESSION['tabResultado']) && $_SESSION['tabResultado'][$l][$c] == true) {
                echo '<td>';
                echo '<img src="images/' . $tabuleiros[$_SESSION['tabEscolhido']][$l][$c] . '.png">';
                echo '</td>';
            } else {
                echo '<td><a href="?l=' . $l . '&c=' . $c . '">';
                echo '<img src="images/Carta.png">';
                echo '</a></td>';
            }
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';

    generateResetButton();
} else {
    generateStartButton();
}
?>

</body>
<img src="./images/foot.png" id="foot">
</html>
