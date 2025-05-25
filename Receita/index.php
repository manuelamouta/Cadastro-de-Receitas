<?php
require_once("../Classes/Receita.class.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = isset($_POST['id'])?$_POST['id']:0;
    $nome = isset($_POST['nome'])?$_POST['nome']:"";
    $ingredientes = isset($_POST['ingredientes'])?$_POST['ingredientes']:"";
    //$foto_prato = isset($_POST['foto_prato'])?$_POST['foto_prato']:"";
    $modo_preparo = isset($_POST['modo_preparo'])?$_POST['modo_preparo']:"";
    $tempo_preparo = isset($_POST['tempo_preparo'])?$_POST['tempo_preparo']:"";
    $acao = isset($_POST['acao'])?$_POST['acao']:"";

    $destino_anexo = 'uploads/'.$_FILES['foto_prato']['name'];
    move_uploaded_file($_FILES['anexo']['tmp_name'],PATH_UPLOAD.$destino_anexo);
    $receita = new Receita($id,$nome,$ingredientes,$destino_anexo, $modo_preparo, $tempo_preparo);
    if ($acao == 'salvar')
        if ($id > 0)
            $resultado = $receita->alterar();
        else
            $resultado = $receita->inserir();
    elseif ($acao == 'excluir')
        $resultado = $receita->excluir();

    if ($resultado)
        header("Location: index.php");
    else
        echo "Erro ao salvar dados: ". $receita;
}elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $formulario = file_get_contents('form_cad_receita.html');

    $id = isset($_GET['id'])?$_GET['id']:0;
    $resultado = receita::listar(1,$id);
    if ($resultado){
        $receita = $resultado[0];
        $formulario = str_replace('{id}',$receita->getId(),$formulario);
        $formulario = str_replace('{nome}',$receita->getnome(),$formulario);
        $formulario = str_replace('{ingredientes}',$receita->getingredientes(),$formulario);
        $formulario = str_replace('{foto_prato}',$receita->getFoto_prato(),$formulario);
        $formulario = str_replace('{modo_preparo}',$receita->getModo_preparo(),$formulario);
        $formulario = str_replace('{tempo_preparo}',$receita->getTempo_preparo(),$formulario);
    }else{
        $formulario = str_replace('{id}',0,$formulario);
        $formulario = str_replace('{nome}','',$formulario);
        $formulario = str_replace('{ingredientes}','',$formulario);
        $formulario = str_replace('{foto_prato}','',$formulario);
        $formulario = str_replace('{modo_preparo}','',$formulario);
        $formulario = str_replace('{tempo_preparo}','',$formulario);
    }
    print($formulario); 
    include_once('lista_receita.php');
 

}
?>