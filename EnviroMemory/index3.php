<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da memória</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>

<img src="./images/LogoEnviroMemory.png" id="logomain">

  

<?php
  /********************************************************
    *                                                     *
    *                                                     *
    *       Código que gera o array dos tabuleiros        *
    *                                                     *
    *                                                     *
    *******************************************************/
session_start();
$array_itenstotal = ["im01","im02","im03","im04","im05","im06","im07","im08","im09","im10","im11","im12","im13","im14","im15","im16","im17","im18"];

function leveltabuleirof($l, $c, $sel, $nomeTabuleiro){

    global $array_itenstotal;

    if (!isset($_SESSION[$nomeTabuleiro])) {
        $array_itens = 0;
        $array_itens = array_slice($array_itenstotal, 0, $sel);

        // Duplica o array de itens para garantir que haja pares correspondentes
        $array_itens = array_merge($array_itens, $array_itens);
        shuffle($array_itens);

        // Inicialize o tabuleiro
        $tabuleiro = [];
        for ($i = 0; $i < $l; $i++) {
            $linha = [];
            for ($j = 0; $j < $c; $j++) {
                // Pegue um item do array de itens embaralhado
                $item = array_pop($array_itens);
                // Adicione o item à linha do tabuleiro
                $linha[] = $item;
            }
            // Adicione a linha ao tabuleiro
            $tabuleiro[] = $linha;
        }

        // Armazene o tabuleiro gerado na sessão com uma chave única
        $_SESSION[$nomeTabuleiro] = $tabuleiro;
    }

    // Recupere e retorne o tabuleiro da sessão
    $tabuleiro = $_SESSION[$nomeTabuleiro];

    // Reindexe o array para evitar problemas de chaves ausentes
    $tabuleiro = array_values($tabuleiro);

    return $tabuleiro;
   
}

// Chamadas da função para gerar tabuleiros
$tabuleiro1 = leveltabuleirof(4, 4, 8, 'tabuleiro1');
$tabuleiro2 = leveltabuleirof(4, 5, 10, 'tabuleiro2');
$tabuleiro3 = leveltabuleirof(6, 6, 18, 'tabuleiro3');

$tabuleiros = [$tabuleiro1, $tabuleiro2, $tabuleiro3];
var_dump($tabuleiro3);
?>

<?php
session_start();

    //Declarações de Funções a serem utilizadas
    function ResetButton() {
        echo '
        <div class="select">
            <form method="POST">
                <input type="hidden" name="acao" value="reset" />
                <input type="submit" value="Reiniciar"/>
            </form>
        </div>';
    }
    
    function StartButton() {
        echo '
        <div class="select">
            <form method="POST">
                <input type="hidden" name="acao" value="start"/>
                <input type="submit" value="Start"/>
            </form>
        </div>';
    }
    
    function PreviousPhase(){
        echo '
        <div class="select3">
            <form method="POST">
                <input type="hidden" name="acao" value="prev"/>
                <input type="submit" value="Anterior"/>
            </form>
        </div>'; 
    }
    function NextPhase(){
        echo '
        <div class="select2">
            <form method="POST">
                <input type="hidden" name="acao" value="next"/>
                <input type="submit" value="Próxima"/>
            </form>
        </div>';
    }
    

    /******************************************************
     *                                                    *
     *                                                    *
     *    Atribuindo resultado as chamadas das funções    *
     *                                                    *
     *                                                    *
    ******************************************************/

    if (isset($_POST['acao']) && $_POST['acao'] == "prev") {
        session_destroy();
        session_start();
        unset($_GET['l']);
        unset($_GET['c']);
        header("Location: index2.php");
    }

    if (isset($_POST['acao']) && $_POST['acao'] == "reset")
    {
        session_destroy();
        session_start();
        unset($_GET['l']);
        unset($_GET['c']);
        header("Location: index3.php");
    }



    /*******************************************
     *                                         *
     *                                         *
     *    Declarando as sessões de início      *
     *                                         *
     *                                         *
    ********************************************/

    //1°Verifica se o primeiro acerto foi feito.
    if (!isset($_SESSION['primeiros_10_pontos']))
    {
        $_SESSION['primeiros_10_pontos'] = false;
    }
    //2° Verifica se o jogo foi concluído.
    if (!isset($_SESSION['jogo_concluido']))
    {
        $_SESSION['jogo_concluido'] = false;
    }
    //3°Faz a contagem dos pontos
    if (!isset($_SESSION['pontos']))
    {
        $_SESSION['pontos'] = 0;
    }
    if (!isset($_SESSION['tabResultado']))
    {
        $tabEscolhido = 2;
        $_SESSION['tabEscolhido'] = $tabEscolhido;
        $_SESSION['tabResultado'] = [
            [false, false, false, false, false, false],
            [false, false, false, false, false, false],
            [false, false, false, false, false, false],
            [false, false, false, false, false, false],
            [false, false, false, false, false, false],
            [false, false, false, false, false, false],
        ];
    }


      /******************************************************
     *                                                      *
     *                                                      *
     *         Lógica do jogo propriamente dito             *
     *                                                      *
     *                                                      *
    ********************************************************/

    //Geração do Tabuleiro do jogo, onde estarão as cartas
    if (isset($_SESSION['tabResultado']))
    {
        echo '<div class="tabuleiro-container">';
        echo '<table class="table">';
            for ($l = 0; $l < 6; $l++)
            {
                echo '<tr>';
                    for ($c = 0; $c <6; $c++)
                    {
                        if (isset($_GET["l"]) && isset($_GET["c"]) && $_GET['l'] == $l && $_GET['c'] == $c)
                        {
                            echo '<td>';
                            echo '<a href="?l=' . $l . '&c=' . $c . '">';
                            echo '<img src="images/' . $tabuleiros[$_SESSION['tabEscolhido']][$l][$c] . '.png">';
                            echo '</a>';
                            echo '</td>';
                        }
                        elseif ($_SESSION['numero_jogadas'] >= 1 && isset($_SESSION['valorLinha']) && isset($_SESSION['valorColuna']) && ($_SESSION['valorLinha'] == $l && $_SESSION['valorColuna'] == $c))
                        {
                            echo '<td>';
                            echo '<a href="?l=' . $_SESSION['valorLinha'] . '&c=' . $_SESSION['valorColuna'] . '">';
                            echo '<img src="images/' . $tabuleiros[$_SESSION['tabEscolhido']][$_SESSION['valorLinha']][$_SESSION['valorColuna']] . '.png">';
                            echo '</a>';
                            echo '</td>';
                        }
                        elseif (isset($_SESSION['tabResultado']) && $_SESSION['tabResultado'][$l][$c] == true)
                        {
                            echo '<td>';
                            echo '<img src="images/' . $tabuleiros[$_SESSION['tabEscolhido']][$l][$c] . '.png">';
                            echo '</td>';
                        } 
                        else
                        {
                            echo '<td><a href="?l=' . $l . '&c=' . $c . '">';
                            echo '<img src="images/Carta.png">';
                            echo '</a></td>';
                        }
                    }
                echo '</tr>';
            }
        echo '</table>';
        echo '</div>';


    if (!isset($_SESSION['numero_jogadas']) || $_SESSION['numero_jogadas'] >= 2)
    {
        $_SESSION['numero_jogadas'] = 1;
    } 
    else
    {
        $_SESSION['numero_jogadas'] = $_SESSION['numero_jogadas'] + 1;
    }

    if (isset($_GET["l"]) && isset($_GET["c"]) && isset($_SESSION['numero_jogadas']) && $_SESSION['numero_jogadas'] == 1)
    {
        $_SESSION['valorLinha'] = $_GET['l'];
        $_SESSION['valorColuna'] = $_GET['c'];
    }

    //verifica se a SESSION numero_jogadas está definida e se vale 2 e inicia a condição
    if (isset($_SESSION['numero_jogadas']) && $_SESSION['numero_jogadas'] == 2 && !isset($_POST['acao']))
    {   //Verifica se existe na URL da página o conteúdo "l" e "c", indicando que houve clique em uma imagem e verifica o conteúdo de valorLinha e Coluna para comparar seo valor
        if (isset($_GET['l']) && isset($_GET['c']) && isset($_SESSION['valorLinha']) && isset($_SESSION['valorColuna'])
        && $tabuleiros[$_SESSION['tabEscolhido']][$_GET['l']][$_GET['c']] == $tabuleiros[$_SESSION['tabEscolhido']][$_SESSION['valorLinha']][$_SESSION['valorColuna']])
        {
            $_SESSION['tabResultado'][$_GET['l']][$_GET['c']] = true;
            $_SESSION['tabResultado'][$_SESSION['valorLinha']][$_SESSION['valorColuna']] = true;

            // O jogador acertou, ganha 10 pontos
            $_SESSION['pontos'] += 10;

            // Se o jogador não fez os primeiros 10 pontos, marque como feito agora
            if ($_SESSION['pontos'] >= 10 && !$_SESSION['primeiros_10_pontos'])
            {
                $_SESSION['primeiros_10_pontos'] = true;
            }

        } 
        else
        {
            // Se o jogador fez os primeiros 10 pontos, ele pode perder 1 ponto por erro
            if ($_SESSION['primeiros_10_pontos'])
            {
                $_SESSION['pontos'] -= 1;
            }
        }

    }
    PreviousPhase();
    ResetButton();
    }
    else
    {
    StartButton();
    }
?>
<div class="info">
    <p>Pontos: <?php echo $_SESSION['pontos']; ?></p>
</div>
</body>
<img src="./images/foot.png" id="foot">
</html>
