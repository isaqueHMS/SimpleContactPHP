<?php
// excluir.php

if (!isset($_GET['id'])) {
          die("ID não fornecido.");
}

$id = $_GET['id'];

$arquivo = 'contatos.json';

if (!file_exists($arquivo)) {
          die("Arquivo de contatos não encontrado.");
}

$contatos = json_decode(file_get_contents($arquivo), true);

// Filtra os contatos removendo aquele com o ID correspondente
$contatos = array_filter($contatos, function ($contato) use ($id) {
          return $contato['id'] !== $id;
});

// Salva o novo array no JSON
file_put_contents($arquivo, json_encode(array_values($contatos), JSON_PRETTY_PRINT));

// Redireciona de volta para a lista
header('Location: list.php');
exit;
