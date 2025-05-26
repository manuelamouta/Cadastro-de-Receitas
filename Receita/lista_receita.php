<?php
//inclui arquivos necessários
include_once("../config/config.inc.php"); 
require_once("../Classes/Receita.class.php");

//obtém parâmetros da busca (se existirem)
$busca = isset($_GET['busca']) ? $_GET['busca'] : 0;  
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;   
//busca as receitas no banco de dados
$lista = Receita::listar($tipo, $busca);

//variável para acumular os itens processados
$itens = '';

//processa cada receita encontrada
foreach($lista as $receita) {
    //carrega o template HTML de um item
    $item = file_get_contents('itens_listagem_receitas.html');
    
    //substitui os placeholders pelos valores reais
    $item = str_replace('{id}', $receita->getId(), $item);
    $item = str_replace('{nome}', $receita->getNome(), $item);
    $item = str_replace('{ingredientes}', $receita->getIngredientes(), $item);
    $item = str_replace('{foto_prato}', PATH_UPLOAD.$receita->getFoto_prato(), $item);
    $item = str_replace('{modo_preparo}', $receita->getModo_preparo(), $item);
    $item = str_replace('{tempo_preparo}', $receita->getTempo_preparo(), $item);
    
    //adiciona o item processado à lista
    $itens .= $item;
}

//carrega o template principal da listagem
$listagem = file_get_contents('listagem_receita.html');

// insere todos os itens no template principal
$listagem = str_replace('{itens}', $itens, $listagem);

// exibe a listagem completa
print($listagem);
?>