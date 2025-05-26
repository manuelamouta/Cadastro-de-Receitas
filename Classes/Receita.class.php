<?php
require_once ("Database.class.php");

//classe que representa uma Receita
class Receita{
    //propriedades(dados) da receita
    private $id;
    private $nome;
    private $ingredientes;
    private $foto_prato;
    private $modo_preparo;
    private $tempo_preparo;
   
    //construtor - cria uma nova receita com os dados passados
    public function __construct($id,$nome,$ingredientes,$foto_prato,$modo_preparo,$tempo_preparo){
        $this->id = $id;
        $this->nome = $nome;
        $this->ingredientes = $ingredientes;
        $this->foto_prato = $foto_prato;
        $this->modo_preparo = $modo_preparo;
        $this->tempo_preparo = $tempo_preparo;
    }

    //métodos set(para alterar valores)
    public function setNome($nome){ $this->nome = $nome; }
    
    public function setId($id){
        if ($id < 0)
            throw new Exception("Erro, a ID deve ser maior que 0!");
        else
            $this->id = $id;
    }

    public function setIngredientes($ingredientes){ $this->ingredientes = $ingredientes; }
    public function setFoto_prato($foto_prato = ''){ $this->foto_prato = $foto_prato; }
    public function setModo_preparo($modo_preparo = ''){ $this->modo_preparo = $modo_preparo; }
    public function setTempo_preparo($tempo_preparo = ''){ $this->tempo_preparo = $tempo_preparo; }

    //métodos get(para ler valores)
    public function getId(): int{ return $this->id; }
    public function getnome(): String{ return $this->nome; }
    public function getingredientes(): String{ return $this->ingredientes; }
    public function getFoto_prato(): String{ return $this->foto_prato; }
    public function getModo_preparo(): String{ return $this->modo_preparo; }
    public function getTempo_preparo(): String{ return $this->tempo_preparo; }

    //transforma a receita em texto para exibição
    public function __toString():String{  
        return "Receita: $this->id - $this->nome
               - ingredientes: $this->ingredientes
               - foto_prato: $this->foto_prato
               - modo_preparo: $this->modo_preparo
               - tempo_preparo: $this->tempo_preparo";      
    }

    //salva a receita no banco de dados
    public function inserir():Bool{
        $sql = "INSERT INTO receita (nome, ingredientes, foto_prato, modo_preparo, tempo_preparo)
                VALUES(:nome, :ingredientes, :foto_prato, :modo_preparo, :tempo_preparo)";
        
        $parametros = array(
            ':nome'=>$this->getNome(),
            ':ingredientes'=>$this->getIngredientes(),
            ':foto_prato'=>$this->getFoto_prato(),
            ':modo_preparo'=>$this->getModo_preparo(),
            ':tempo_preparo'=>$this->getTempo_preparo()
        );
        
        return Database::executar($sql, $parametros);
    }

    //lista receitas do banco (com opção de filtro)
    public static function listar($tipo=0, $info=''):Array{
        $sql = "SELECT * FROM receita";
        
        //verifica tipo de filtro
        switch ($tipo){
            case 1: $sql .= " WHERE id = :info ORDER BY id"; break; // Filtra por ID
            case 2: $sql .= " WHERE nome like :info ORDER BY nome"; $info = '%'.$info.'%'; break; // Filtra por nome
        }
        
        $parametros = ($tipo > 0) ? [':info'=>$info] : [];
        
        //busca no banco e transforma em objetos Receita
        $comando = Database::executar($sql, $parametros);
        $receitas = [];
        while ($registro = $comando->fetch()){
            $receita = new Receita($registro['id'],$registro['nome'],$registro['ingredientes'],
                                 $registro['foto_prato'],$registro['modo_preparo'],$registro['tempo_preparo']);
            array_push($receitas,$receita);
        }
        return $receitas;
    }

    //atualiza a receita no banco
    public function alterar():Bool{       
       $sql = "UPDATE receita SET 
                  nome = :nome, 
                  ingredientes = :ingredientes,
                  foto_prato = :foto_prato,
                  modo_preparo = :modo_preparo,
                  tempo_preparo = :tempo_preparo
               WHERE id = :id";
               
       $parametros = array(
            ':id'=>$this->getId(),
            ':nome'=>$this->getNome(),
            ':ingredientes'=>$this->getIngredientes(),
            ':foto_prato'=>$this->getFoto_prato(),
            ':modo_preparo'=>$this->getModo_preparo(),
            ':tempo_preparo'=>$this->getTempo_preparo()
       );
       
       return Database::executar($sql, $parametros);
    }

    //remove a receita do banco
    public function excluir():Bool{
        $sql = "DELETE FROM receita WHERE id = :id";
        $parametros = array(':id'=>$this->getid());
        return Database::executar($sql, $parametros);
    }
}