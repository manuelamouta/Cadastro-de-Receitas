<?php
//inclui o arquivo de configuração com dados de conexão
include "../config/config.inc.php";

class Database {
    //método privado para abrir conexão com o banco
    private static function abrirConexao() {
        try {
            //cria e retorna uma nova conexão PDO
            return new PDO(DSN, USUARIO, SENHA);
        } catch(PDOException $e) {
            //mostra erro se a conexão falhar
            echo "Erro ao conectar com o banco de dados: ".$e->getMessage();
        }
    }

    //método privado para preparar uma SQL
    private static function preparar($sql) {
        //abre conexão e prepara a query SQL
        $conexao = self::abrirConexao();
        return $conexao->prepare($sql);
    }

    //método privado para vincular parâmetros à query
    private static function vincularParametros($comando, $parametros) {
        // Para cada parâmetro, vincula o valor à query
        foreach($parametros as $chave => $valor) {
            $comando->bindValue($chave, $valor);
        }
        return $comando;
    }

    // Método público principal para executar queries
    public static function executar($sql, $parametros) {
        //prepara a SQL
        $comando = self::preparar($sql);
        //vincula os parâmetros
        $comando = self::vincularParametros($comando, $parametros);
        //executa a query
        $comando->execute();
        // Retorna o resultado
        return $comando;
    }
}