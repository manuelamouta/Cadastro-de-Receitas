<?php
    include_once("../config/config.inc.php");
    require_once("../Classes/Receita.class.php");
    $busca = isset($_GET['busca'])?$_GET['busca']:0;
    $tipo = isset($_GET['tipo'])?$_GET['tipo']:0;
   
    $lista = Receita::listar($tipo, $busca);
    $itens = '';
    foreach($lista as $receita){
        $item = file_get_contents('itens_listagem_receitas.html');
        $item = str_replace('{id}',$receita->getId(),$item);
        $item = str_replace('{nome}',$receita->getNome(),$item);
        $item = str_replace('{ingredientes}',$receita->getIngredientes(),$item);
        $item = str_replace('{foto_prato}',PATH_UPLOAD.$receita->getFoto_prato(),$item);
        $item = str_replace('{modo_preparo}',$receita->getModo_preparo(),$item);
        $item = str_replace('{tempo_preparo}',$receita->getTempo_preparo(),$item);
        $itens .= $item;
    }
    $listagem = file_get_contents('listagem_receita.html');
    $listagem = str_replace('{itens}',$itens,$listagem);
    print($listagem);
     
?>