<?php
//inclui a classe Receita para poder usá-la
require_once("../Classes/Receita.class.php");

//verifica se o formulário foi enviado (método POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //pega os dados do formulário ou usa valores padrão
    $id = isset($_POST['id'])?$_POST['id']:0;
    $nome = isset($_POST['nome'])?$_POST['nome']:"";
    $ingredientes = isset($_POST['ingredientes'])?$_POST['ingredientes']:"";
    $modo_preparo = isset($_POST['modo_preparo'])?$_POST['modo_preparo']:"";
    $tempo_preparo = isset($_POST['tempo_preparo'])?$_POST['tempo_preparo']:"";
    $acao = isset($_POST['acao'])?$_POST['acao']:"";

    //processa o upload da foto
    $destino_anexo = 'uploads/'.$_FILES['foto_prato']['name'];
    move_uploaded_file($_FILES['foto_prato']['tmp_name'], PATH_UPLOAD.$destino_anexo);

    //cria um objeto Receita com os dados
    $receita = new Receita($id, $nome, $ingredientes, $destino_anexo, $modo_preparo, $tempo_preparo);

    //executa a ação 
    if ($acao == 'salvar') {
        if ($id > 0)
            $resultado = $receita->alterar(); // Atualiza se já tem ID
        else
            $resultado = $receita->inserir(); // Insere se é novo
    }
    elseif ($acao == 'excluir') {
        $resultado = $receita->excluir(); // Remove a receita
    }

    //redireciona ou mostra erro
    if ($resultado)
        header("Location: index.php");
    else
        echo "Erro ao salvar dados: ". $receita; 

//se a requisição for GET (carregar a página)
}elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
    //carrega o template do formulário HTML
    $formulario = file_get_contents('form_cad_receita.html');

    //verifica se está editando uma receita existente
    $id = isset($_GET['id'])?$_GET['id']:0;
    $resultado = Receita::listar(1, $id); 
    
    //se encontrou a receita, preenche o formulário com seus dados
    if ($resultado){
        $receita = $resultado[0];
        $formulario = str_replace('{id}', $receita->getId(), $formulario);
        $formulario = str_replace('{nome}', $receita->getnome(), $formulario);
        $formulario = str_replace('{ingredientes}', $receita->getingredientes(), $formulario);
        $formulario = str_replace('{foto_prato}', $receita->getFoto_prato(), $formulario);
        $formulario = str_replace('{modo_preparo}', $receita->getModo_preparo(), $formulario);
        $formulario = str_replace('{tempo_preparo}', $receita->getTempo_preparo(), $formulario);
    }else{
        //se não encontrou, limpa o formulário
        $formulario = str_replace('{id}', 0, $formulario);
        $formulario = str_replace('{nome}', '', $formulario);
        $formulario = str_replace('{ingredientes}', '', $formulario);
        $formulario = str_replace('{foto_prato}', '', $formulario);
        $formulario = str_replace('{modo_preparo}', '', $formulario);
        $formulario = str_replace('{tempo_preparo}', '', $formulario);
    }
    
    //exibe o formulário e a lista de receitas
    print($formulario); 
    include_once('lista_receita.php');
}
?>