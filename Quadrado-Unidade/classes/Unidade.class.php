<?php
require_once("../classes/Database.class.php"); // Inclui a classe que lida com o banco de dados

class Unidade {
    // Atributos da classe que representam as colunas da tabela "unidades" no banco de dados
    private $id;
    private $tipo;
    private $nome;

    // Construtor da classe: inicializa os atributos com os valores passados como parâmetro
    public function __construct($id, $nome, $tipo) {
        $this->setIdUnidade($id); // Define o ID da unidade, com validação
        $this->setTipo($tipo);     // Define o tipo da unidade, com validação
        $this->setNome($nome);     // Define o nome da unidade, com validação
    }

    // sets para definir os valores dos atributos, com validações para garantir a integridade dos dados

    public function setIdUnidade($id) {
        if ($id < 0) {
            throw new Exception("Erro: id inválido!"); // Lança uma exceção se o ID for negativo
        } else {
            $this->id = $id;
        }
    }

    public function setTipo($tipo) {
        if ($tipo == "") {
            throw new Exception("Erro, Tipo indefinido"); // Lança uma exceção se o tipo for vazio
        } else {
            $this->tipo = $tipo;
        }
    }

    public function setNome($nome) {
        if ($nome == "") {
            throw new Exception("Erro, nome indefinido"); // Lança uma exceção se o nome for vazio
        } else {
            $this->nome = $nome;
        }
    }

    // gets para acessar os valores dos atributos

    public function getTipo() {
        return $this->tipo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getIdUnidade() {
        return $this->id;
    }

 

    // Método para inserir uma nova unidade no banco de dados
    public function incluir() {
        $sql = 'INSERT INTO unidades (unidade, tipo, id) VALUES (:unidade, :tipo, :id)'; // Consulta SQL para inserção

        // Array com os parâmetros da consulta, associando os nomes dos parâmetros aos valores dos atributos
        $parametros = array(':tipo' => $this->nome, ':unidade' => $this->tipo, ':id' => $this->id);

        // Chama o método estático da classe Database para executar a consulta com os parâmetros
        return Database::executar($sql, $parametros); 
    }

    // Método para excluir uma unidade do banco de dados com base no ID
    public function excluir() {
        $conexao = Database::getInstance(); // Obtém uma instância da conexão com o banco de dados
        $sql = 'DELETE FROM unidades WHERE id = :id'; // Consulta SQL para exclusão

        $comando = $conexao->prepare($sql); // Prepara a consulta SQL
        $comando->bindValue(':id', $this->id); // Associa o valor do ID ao parâmetro da consulta

        return $comando->execute(); // Executa a consulta e retorna o resultado (true para sucesso, false para falha)
    }

    // Método para atualizar os dados de uma unidade no banco de dados com base no ID
    public function alterar() {
        $sql = 'UPDATE unidades SET unidade = :unidade, tipo = :tipo WHERE id = :id'; // Consulta SQL para atualização

        // Array com os parâmetros da consulta
        $parametros = array(':tipo' => $this->nome, ':unidade' => $this->tipo, ':id' => $this->id);

        // Chama o método estático da classe Database para executar a consulta com os parâmetros
        return Database::executar($sql, $parametros); 
    }

    // Método estático para listar unidades do banco de dados, com opções de filtro por tipo ou busca
    public static function listar($tipo = 0, $busca = "") {
        $sql = "SELECT * FROM unidades"; // Consulta SQL básica para selecionar todas as unidades

        // Adiciona condições à consulta com base nos parâmetros de filtro
        if ($tipo > 0) {
            switch ($tipo) {
                case 1: // Filtra por ID exato
                    $sql .= " WHERE id = :busca";
                    break;
                case 2: // Filtra por tipo, permitindo busca parcial
                    $sql .= " WHERE tipo LIKE :busca";
                    $busca = "%{$busca}%"; // Adiciona "%" para busca parcial
                    break;
            }
        }

        $parametros = []; // Inicializa o array de parâmetros

        // Adiciona o parâmetro de busca ao array se necessário
        if ($tipo > 0) {
            $parametros = array(':busca' => $busca);
        }

        // Executa a consulta com os parâmetros e armazena o resultado em $comando
        $comando = Database::executar($sql, $parametros); 

        $unidades = array(); // Inicializa o array que armazenará os objetos Unidade

        // Itera sobre os resultados da consulta, criando objetos Unidade e adicionando-os ao array
        while ($forma = $comando->fetch(PDO::FETCH_ASSOC)) {
            $unidade = new Unidade($forma['id'], $forma['tipo'], $forma['unidade']);
            array_push($unidades, $unidade);
        }

        return $unidades; // Retorna o array de objetos Unidade
    }
}
