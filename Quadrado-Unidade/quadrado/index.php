<!DOCTYPE html>
<html lang="en">
<?php
include_once('quadrado.php');
?>

<head>
    <title>Formulário de criação de formas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <nav class="mb-4">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Cadastro de Quadrados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../unidade/index.php">Cadastro de Unidade</a>
                </li>
            </ul>
        </nav>


        <h2>Cadastro de Quadrados</h2>
        <form action="quadrado.php" method="post" class="row g-3">
            <div class="col-md-4">
                <label for="altura" class="form-label">Tamanho dos lados:</label>
                <input type="number" name="altura" id="altura" class="form-control" value="<?= $id ? $contato->getAltura() : "" ?>" placeholder=" Digite a altura de sua forma">
            </div>
            <div class="col-md-4">
                <label for="cor" class="form-label">Cor:</label>
                <input type="color" name="cor" id="cor" class="form-control form-control-color" placeholder=" Digite a cor de sua forma" value="<?= $id ? $contato->getCor() : "black" ?>">
            </div>
            <div class="col-md-4">
                <label for="unidade" class="form-label">Unidade de medida:</label>
                <select name='idUnForm' id='idUnForm' class="form-select">
                    <option value="0">Selecione</option>
                    <?php
                    $uniLista = Unidade::listar();
                    foreach ($uniLista as $unidade) {
                        $str = "<option value='{$unidade->getIdUnidade()} '";
                        if (isset($contato) && $contato->getUnidade()->getIdUnidade() == $unidade->getIdUnidade())
                            $str .= " selected";
                        $str .= ">{$unidade->getNome()}</option>";
                        echo $str;
                    }
                    ?>
                </select>
            </div>

            <div class="col-12">
                <input type="text" name="id" id="id" value="<?= isset($contato) ? $contato->getId() : 0 ?>" placeholder="Digite a unidade de sua forma" hidden>
                <button type="submit" name="acao" id="acao" value="salvar" class="btn btn-primary">Salvar</button>
                <button type="reset" name="resetar" id="resetar" value="Resetar" class="btn btn-secondary">Resetar</button>
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
                <button type="submit" name="acao" id="acao" value="Buscar" class="btn btn-success">Buscar</button>
            </div>
        </form>

        <br>

        <table>
            <?php
            echo "<tr>"; 

            foreach ($lista as $quadrado) {
                echo "<td style='padding-right:10px;'>";
                echo "<div style='";
                echo "width: " . $quadrado->getAltura() . $quadrado->getUnidade()->getTipo() . "; ";
                echo "height: " . $quadrado->getAltura() . $quadrado->getUnidade()->getTipo() . "; ";
                echo "background-color: " . $quadrado->getCor() . "; ";
                echo "display: flex; ";
                echo "flex-direction: column; ";
                echo "justify-content: center; ";
                echo "align-items: center; ";
                echo "'>";

                echo "<div>";
                echo "ID: " . $quadrado->getId() . "<br>";
                echo "Lado: " . $quadrado->getAltura() . " " . $quadrado->getUnidade()->getNome() . "<br>";
                echo "Cor: " . $quadrado->getCor() . "<br>";
                echo "</div>";
                
                echo "</div>"; 
                echo "</a>";  

                // Botões de ação fora do quadrado
                echo "<div style='text-align: left;'>"; 
                echo "<a href='delete.php?id=" . $quadrado->getId() . "'>Excluir</a><br>";
                echo "<a href='index.php?id=" . $quadrado->getId() . "'>Editar</a>";
                echo "</div>";

                echo "</td>";
            }

            echo "</tr>";
            ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>