<?php
// Inclui os arquivos das classes necessárias
require_once("../classes/Quadrado.class.php");
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");

// Verifica se um ID foi passado pela URL (para edição ou exclusão)
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$msg = isset($_GET['MSG']) ? $_GET['MSG'] : "";

// Se um ID válido foi fornecido, busca o quadrado correspondente no banco de dados
if ($id > 0) {
    $contato = Quadrado::listar(1, $id)[0];
}

// Processa as requisições POST (envio do formulário)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $altura = isset($_POST['altura']) ? $_POST['altura'] : 0;
    $cor = isset($_POST['cor']) ? $_POST['cor'] : "";
    $idUnForm = isset($_POST['idUnForm']) ? $_POST['idUnForm'] : 0;
    $acao = isset($_POST['acao']) ? $_POST['acao'] : 0;

    try {
        // Busca a unidade de medida pelo ID fornecido no formulário
        $id_un = Unidade::listar(1, $idUnForm)[0];
        // Cria um novo objeto Quadrado com os dados do formulário e a unidade de medida
        $Quadrado = new Quadrado($id, $altura, $cor, $id_un);
        $res = "";

        // Verifica a ação a ser realizada
        if ($acao == 'salvar') {
            if ($id > 0) { // Se o ID for maior que 0, significa que estamos editando um quadrado existente
                $res = $Quadrado->alterar();
            } else { // Caso contrário, estamos inserindo um novo quadrado
                $res = $Quadrado->incluir();
            }
        } elseif ($acao == 'excluir') { // Se a ação for 'excluir', exclui o quadrado
            $res = $Quadrado->excluir();
        }

        // Se a ação foi bem-sucedida, redireciona para a página inicial
        if ($res)
            header('Location: index.php');
        else // Caso contrário, exibe uma mensagem de erro genérica
            echo "erro ao inserir dados!";
    } catch (Exception $e) { // Captura e exibe qualquer exceção lançada durante o processo
        echo $e;
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') { // Processa as requisições GET (busca de quadrados)
    $busca = isset($_GET['busca']) ? $_GET['busca'] : "";
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
    $lista = Quadrado::listar($tipo, $busca);
}
