<?php

require_once('../config/config.inc.php'); // Inclui o arquivo de configuração com as credenciais do banco de dados

class Database {
    // Atributo estático para armazenar o ID da última inserção realizada
    public static $lastId; 

    // Método estático para obter uma instância da conexão com o banco de dados usando PDO
    public static function getInstance() {
        try {
            // Tenta criar uma nova conexão PDO usando as constantes definidas no arquivo de configuração
            return new PDO(DSN, USUARIO, SENHA); 
        } catch (PDOException $e) {
            // Captura e exibe uma mensagem de erro caso ocorra algum problema na conexão
            echo "Erro ao conectar ao banco de dados" . $e->getMessage(); 
        }
    }

    // Método estático para obter uma instância da conexão, um alias para o método getInstance()
    public static function conectar() {
        return Database::getInstance();
    }

    // Método estático para preparar uma consulta SQL usando a conexão fornecida
    public static function preparar($conexao, $sql) {
        return $conexao->prepare($sql); 
    }

    // Método estático para vincular parâmetros a uma consulta preparada
    public static function vincular($comando, $parametros = array()) {
        // Itera sobre o array de parâmetros e associa cada valor ao seu respectivo parâmetro na consulta
        foreach ($parametros as $key => $value) {
            $comando->bindValue($key, $value); 
        }
        return $comando; // Retorna o comando com os parâmetros vinculados
    }

    // Método estático para executar uma consulta SQL com parâmetros opcionais
    public static function executar($sql, $parametros = array()) {
        $conexao = self::conectar(); // Obtém uma instância da conexão

        $comando = self::preparar($conexao, $sql); // Prepara a consulta
        $comando = self::vincular($comando, $parametros); // Vincula os parâmetros à consulta

        try {
            $comando->execute(); // Executa a consulta

            // Armazena o ID da última inserção realizada, se houver
            self::$lastId = $conexao->lastInsertId(); 

            return $comando; // Retorna o objeto de comando para que possa ser usado para buscar resultados, se necessário
        } catch (PDOException $e) {
            // Captura e lança uma exceção mais detalhada em caso de erro na execução da consulta
            throw new Exception("Erro ao executar o comando no banco de dados: " . $e->getMessage() . " - " . $comando->errorInfo()[2]);
        }
    }
}
