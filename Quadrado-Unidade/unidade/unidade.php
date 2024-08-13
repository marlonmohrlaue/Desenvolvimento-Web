<?php
// Inclui as classes necessárias para o funcionamento do script
require_once("../classes/Unidade.class.php");
require_once("../classes/Database.class.php");

// Verifica se um ID foi passado via GET (geralmente usado para edição)
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$msg = isset($_GET['MSG']) ? $_GET['MSG'] : ""; // Captura mensagens de erro ou sucesso, se houver

// Se um ID válido foi fornecido, busca a unidade correspondente no banco de dados
if ($id > 0) {
    $contato = Unidade::listar(1, $id)[0];
}

// Processa as requisições POST (envio do formulário)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $nome_un = isset($_POST['nome_un']) ? $_POST['nome_un'] : "";
    $un = isset($_POST['un']) ? $_POST['un'] : "";
    $acao = isset($_POST['acao']) ? $_POST['acao'] : 0;

    try {
        // Cria um novo objeto Unidade com os dados do formulário
        $UnCadastro = new Unidade($id, $nome_un, $un);
        $res = "";

        // Verifica a ação a ser realizada
        if ($acao == 'Salvar') {
            if ($id > 0) { // Se o ID for maior que 0, significa que estamos editando uma unidade existente
                $res = $UnCadastro->alterar();
            } else { // Caso contrário, estamos inserindo uma nova unidade
                $res = $UnCadastro->incluir();
            }
        } elseif ($acao == 'Excluir') { // Se a ação for 'excluir', exclui a unidade
            $res = $UnCadastro->excluir();
        }

        // Se a ação foi bem-sucedida, redireciona para a página inicial (index.php)
        if ($res)
            header('Location: index.php');
        else // Caso contrário, exibe uma mensagem de erro genérica
            echo "erro ao inserir dados!";
    } catch (Exception $e) { // Captura e exibe qualquer exceção lançada durante o processo
        echo $e;
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') { // Processa as requisições GET (busca de unidades)
    $busca = isset($_GET['busca']) ? $_GET['busca'] : "";
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
    $lista = Unidade::listar($tipo, $busca);
}
