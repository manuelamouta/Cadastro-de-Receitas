<?php
require_once ("Database.class.php");
class Receita{
    private $id;
    private $nome;
    private $ingredientes;
    private $modo_preparo;
    private $tempo_preparo;
    private $foto_prato;

    // construtor da classe
    public function __construct($id,$nome,$ingredientes,$modo_preparo,$tempo_preparo,$foto_prato){
        $this->id = $id;
        $this->nome = $nome;
        $this->ingredientes = $ingredientes;
        $this->modo_preparo = $modo_preparo;
        $this->tempo_preparo = $tempo_preparo;
        $this->foto_prato = $foto_prato;
    }

    // função / interface para aterar e ler
    public function setNome($nome){
        if ($nome == "")
            throw new Exception("Erro, o nome deve ser informado!");
        else
            $this->nome = $nome;
    }
    // cada atributo tem um método set para alterar seu valor
    public function setId($id){
        if ($id < 0)
            throw new Exception("Erro, a ID deve ser maior que 0!");
        else
            $this->id = $id;
    }

    public function setIngredientes($ingredientes){
            if ($ingredientes < 0)
                throw new Exception("Erro, o ingrediente deve ser maior que 0!");
            else
                $this->ingredientes = $ingredientes;
    }
    // foto_prato pode ser em branco por isso o parâmetro é opcional
    public function setFoto_prato($foto_prato = ''){
        $this->foto_prato = $foto_prato;
    }

     public function setModo_preparo($modo_preparo = ''){
        $this->modo_preparo = $modo_preparo;
    }

     public function setTempo_preparo($tempo_preparo = ''){
        $this->tempo_preparo = $tempo_preparo;
    }

    public function getId(): int{
        return $this->id;
    }
    public function getnome(): String{
        return $this->nome;
    }
    public function getingredientes(): float{
        return $this->ingredientes;
    }
    public function getFoto_prato(): String{
        return $this->foto_prato;
    }
     public function getModo_preparo(): String{
        return $this->modo_preparo;
    }
     public function getTempo_preparo(): String{
        return $this->tempo_preparo;
    }

    // método mágico para imprimir uma receita
    public function __toString():String{  
        $str = "Receita: $this->id - $this->nome
                 - ingredientes: $this->ingredientes
                 - foto_prato: $this->foto_prato
                 - modo_preparo: $this->modo_preparo
                 - tempo_preparo: $this->tempo_preparo";      
        return $str;
    }

    // insere uma receita no banco 
    public function inserir():Bool{
        // montar o sql/ query
        $sql = "INSERT INTO receita 
                    (nome, ingredientes, foto_prato, modo_preparo, tempo_preparo)
                    VALUES(:nome, :ingredientes, :foto_prato, :modo_preparo, :tempo_preparo)";
        
        $parametros = array(':nome'=>$this->getNome(),
                            ':ingredientes'=>$this->getIngredientes(),
                            ':foto_prato'=>$this->getFoto_prato(),
                            ':modo_preparo'=>$this->getModo_preparo(),
                            ':tempo_preparo'=>$this->getTempo_preparo());
        
        return Database::executar($sql, $parametros) == true;
    }

    public static function listar($tipo=0, $info=''):Array{
        $sql = "SELECT * FROM receita";
        switch ($tipo){
            case 0: break;
            case 1: $sql .= " WHERE id = :info ORDER BY id"; break; // filtro por ID
            case 2: $sql .= " WHERE nome like :info ORDER BY nome"; $info = '%'.$info.'%'; break; // filtro por descrição
        }
        $parametros = array();
        if ($tipo > 0)
            $parametros = [':info'=>$info];

        $comando = Database::executar($sql, $parametros);
        //$resultado = $comando->fetchAll();
        $receitas = [];
        while ($registro = $comando->fetch()){
            $receita = new receita($registro['id'],$registro['nome'],$registro['ingredientes'],$registro['foto_prato'], $registro['modo_preparo'], $registro['tempo_preparo']);
            array_push($receitas,$receita);
        }
        return $receitas;
    }

    public function alterar():Bool{       
       $sql = "UPDATE receita
                  SET nome = :nome, 
                      ingredientes = :ingredientes,
                      foto_prato = :foto_prato,
                      modo_preparo = :modo_preparo,
                      tempo_preparo = :tempo_preparo
                WHERE id = :id";
         $parametros = array(':id'=>$this->getId(),
                        ':nome'=>$this->getNome(),
                        ':ingredientes'=>$this->getIngredientes(),
                        ':foto_prato'=>$this->getFoto_prato(),
                        ':modo_preparo'=>$this->getModo_preparo(),
                        ':tempo_preparo'=>$this->getTempo_preparo());
        return Database::executar($sql, $parametros) == true;
    }

    public function excluir():Bool{
        $sql = "DELETE FROM receita
                      WHERE id = :id";
        $parametros = array(':id'=>$this->getid());
        return Database::executar($sql, $parametros) == true;
     }
}

?>