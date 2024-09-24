<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../navbar.php');
include_once('../traingulo/triangulo.php');
require_once("../classes/Unidade.class.php");


// Verifica se a requisição GET foi feita para buscar triângulos
$busca = isset($_GET['busca']) ? $_GET['busca'] : "";
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
$lista = Triangulo::listar($tipo, $busca); // Busca os triângulos conforme a busca

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar') {
    // Captura o lado e a cor do formulário
    $lado = $_POST['lado']; // Agora apenas um lado, para equilátero
    $cor = $_POST['cor'];
    $id_unidade = $_POST['medida']; // Certifique-se de que o campo 'medida' é enviado

    // Criação de um novo objeto Triangulo
    $unidade = new Unidade($id_unidade);
    $triangulo = new Triangulo(0, $lado, $lado, $lado, $cor, $unidade); // Todos os lados iguais

    // Tente incluir o triângulo no banco de dados
    try {
        $triangulo->incluir();
        // Redirecionar ou mostrar mensagem de sucesso
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        echo "Erro ao salvar: " . $e->getMessage();
    }
}
?>

<head>
    <title>Formulário de criação de formas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Cadastro de Triângulos Equiláteros</h2>
        <form action="triangulo_equilatero.php" method="post" class="row g-3">
            <div class="col-md-4">
                <label for="lado" class="form-label">Lado:</label>
                <input type="number" name="lado" id="lado" class="form-control" placeholder="Digite o lado" required>
            </div>
            <div class="col-md-4">
                <label for="cor" class="form-label">Cor:</label>
                <input type="color" name="cor" id="cor" class="form-control form-control-color" value="#000000">
            </div>
            <div class="col-md-4">
                <label for="medida" class="form-label">Unidade de medida:</label>
                <select name='medida' id='medida' class="form-select" required>
                    <option value="0">Selecione</option>
                    <?php
                    $uniLista = Unidade::listar();
                    foreach ($uniLista as $unidade) {
                        echo "<option value='{$unidade->getIdUnidade()}'>{$unidade->getNome()}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-12">
                <input type="hidden" name="id" id="id" value="0">
                <button type="submit" name="acao" value="salvar" class="btn btn-primary">Salvar</button>
                <button type="reset" class="btn btn-secondary">Resetar</button>
            </div>
        </form>

        <hr>

        <h2>Pesquisar</h2>
        <form action="" method="get" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="busca" id="busca" class="form-control" placeholder="Procurar">
            </div>
            <div class="col-md-3">
                <select name="tipo" id="tipo" class="form-select">
                    <option value="1">ID</option>
                    <option value="2">Lado</option>
                    <option value="3">Cor</option>
                    <option value="4">Unidade</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success">Buscar</button>
            </div>
        </form>

        <br>

        <h2>Triângulos Desenhados</h2>
        <div class="row" style="display: flex; flex-wrap: wrap;">
            <?php
            $lista = Triangulo::listar(0, ""); // Passa valores padrão
            foreach ($lista as $triangulo) {
                $lado = $triangulo->getLado1(); // Agora todos os lados são iguais
                $cor = $triangulo->getCor();
                $unidade = $triangulo->getUnidade()->getNome();

                // Calcular a altura
                $altura = ($lado * sqrt(3)) / 2; // Altura de um triângulo equilátero

                // Fator de conversão baseado na unidade selecionada
                $fatorConversao = match ($unidade) {
                    'mm' => 3.77953,
                    'cm' => 37.7953,
                    'in' => 96,
                    'pt' => 1.33333,
                    'pc' => 16,
                    'px' => 1,
                    'em' => 16, // ajuste conforme necessário
                    'rem' => 16, // ajuste conforme necessário
                    'vw' => 1, // porcentagem da viewport
                    'vh' => 1, // porcentagem da viewport
                    'vmin' => 1, // porcentagem da viewport
                    'vmax' => 1, // porcentagem da viewport
                    default => 1, // px
                };

                // Calcular os pontos do triângulo equilátero em pixels
                $ladoPx = $lado * $fatorConversao;
                $alturaPx = $altura * $fatorConversao;
                $points = "0,$alturaPx $ladoPx,$alturaPx " . ($ladoPx / 2) . ",0";

                // Exibir o triângulo
                echo "<div class='col-md-4' style='overflow: visible; margin-bottom: 20px;'>"; // Remover altura fixa
                echo "<svg width='100%' height='" . ($alturaPx + 20) . "px'>"; // Altura do SVG
                echo "<polygon points='$points' style='fill:$cor;stroke:black;stroke-width:1' />";
                echo "</svg>";
                echo "<p>Lado: $lado $unidade </p>";
                echo "<p>Perímetro: " . (3 * $lado) . " $unidade</p>";
                echo "<p>Área: " . (($lado * $altura) / 2) . " {$unidade}²</p>"; // Unidade² para área
                echo "id: " . $triangulo->getId();
                echo "<a href='delete.php?id=" . $triangulo->getId() . "' class='btn btn-danger btn-sm'>Excluir</a> ";
                echo "</div>";
            }
            ?>
        </div>



    </div>
</body>

</html>