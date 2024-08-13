<?php
require_once("../classes/Database.class.php"); // Inclui a classe para interação com o banco de dados
require_once("../classes/Unidade.class.php"); // Inclui a classe que representa unidades de medida

class Quadrado
{
    // Atributos da classe que representam as colunas da tabela "quadrados" no banco de dados
    private $id;        // ID do quadrado
    private $altura;    // Altura (lado) do quadrado
    private $cor;       // Cor do quadrado
    private $unidade;   // Objeto da classe Unidade, representando a unidade de medida da altura

    // Construtor da classe: inicializa os atributos com os valores passados como parâmetro, com valores padrão opcionais
    public function __construct($id = 0, $altura = 1, $cor = "black", Unidade $unidade = null)
    {
        $this->setId($id);          // Define o ID do quadrado, com validação
        $this->setAltura($altura);  // Define a altura do quadrado, com validação
        $this->setCor($cor);        // Define a cor do quadrado, com validação
        $this->setUnidade($unidade); // Define a unidade de medida do quadrado
    }

    // Msets para definir os valores dos atributos, com validações para garantir a integridade dos dados

    public function setId($id)
    {
        if ($id < 0) {
            throw new Exception("Erro: id inválido!"); // Lança uma exceção se o ID for negativo
        } else {
            $this->id = $id;
        }
    }

    public function setAltura($altura)
    {
        if ($altura < 1) {
            throw new Exception("Erro, número indefinido"); // Lança uma exceção se a altura for menor que 1
        } else {
            $this->altura = $altura;
        }
    }

    public function setCor($cor)
    {
        if ($cor == "") {
            throw new Exception("Erro, cor indefinido"); // Lança uma exceção se a cor for vazia
        } else {
            $this->cor = $cor;
        }
    }

    // Método para definir a unidade de medida do quadrado, recebendo um objeto da classe Unidade
    public function setUnidade(Unidade $unidade)
    {
        $this->unidade = $unidade;
    }

    //gets para acessar os valores dos atributos

    public function getId()
    {
        return $this->id;
    }

    public function getAltura()
    {
        return $this->altura;
    }

    public function getCor()
    {
        return $this->cor;
    }

    public function getUnidade()
    {
        return $this->unidade;
    }

    // Métodos para interagir com o banco de dados (CRUD - Create, Read, Update, Delete)

    // Método para inserir um novo quadrado no banco de dados
    public function incluir()
    {
        $sql = 'INSERT INTO quadrados (lado, cor, id_unidade, id) VALUES (:lado, :cor, :id_unidade, :id)';

        // Array com os parâmetros da consulta, incluindo o ID da unidade de medida obtido do objeto Unidade
        $parametros = array(
            ':lado' => $this->altura,
            ':cor' => $this->cor,
            ':id_unidade' => $this->unidade->getIdUnidade(),
            ':id' => $this->id
        );

        return Database::executar($sql, $parametros); // Executa a consulta e retorna o resultado
    }

    // Método para excluir um quadrado do banco de dados com base no ID
    public function excluir()
    {
        $conexao = Database::getInstance();
        $sql = 'DELETE FROM quadrados WHERE id = :id';

        $comando = $conexao->prepare($sql);
        $comando->bindValue(':id', $this->id);

        return $comando->execute();
    }

    // Método para atualizar os dados de um quadrado no banco de dados com base no ID
    public function alterar()
    {
        $sql = 'UPDATE quadrados SET lado = :lado, cor = :cor, id_unidade = :id_unidade WHERE id = :id';

        $parametros = array(':lado' => $this->altura, ':cor' => $this->cor, ':id_unidade' => $this->unidade->getIdUnidade(), ':id' => $this->id);

        return Database::executar($sql, $parametros);
    }

    // Método estático para listar quadrados do banco de dados, com opções de filtro
    public static function listar($tipo = 0, $busca = "")
    {
        $sql = "SELECT * FROM quadrados";

        if ($tipo > 0) {
            switch ($tipo) {
                case 1: // Filtra por ID exato
                    $sql .= " WHERE id = :busca";
                    break;
                case 2: // Filtra por lado (altura), permitindo busca parcial
                    $sql .= " WHERE lado LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
                case 3: // Filtra por cor, permitindo busca parcial
                    $sql .= " WHERE cor LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
                case 4: // Filtra por tipo de unidade de medida, buscando na tabela "unidades"
                    $sql .= ", id_unidade WHERE id_unidade.id = quadrados.id_unidade and tipo like :busca";
                    break;
            }
        }

        $parametros = [];

        if ($tipo > 0) {
            $parametros = array(':busca' => $busca);
        }

        $comando = Database::executar($sql, $parametros);
        $quadrados = array();

        // Itera sobre os resultados, buscando a unidade de medida relacionada e criando objetos Quadrado
        while ($forma = $comando->fetch(PDO::FETCH_ASSOC)) {
            $unidade = Unidade::listar(1, $forma['id_unidade'])[0]; // Busca a unidade pelo ID
            $quadrado = new Quadrado($forma['id'], $forma['lado'], $forma['cor'], $unidade);
            array_push($quadrados, $quadrado);
        }

        return $quadrados;
    }
}
