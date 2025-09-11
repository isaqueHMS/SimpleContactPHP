<?php
header('Content-Type: application/json; charset=utf-8');

$arquivo = 'contatos.json';
$contatos = [];

if (file_exists($arquivo)) {
          $json = file_get_contents($arquivo);
          $contatos = json_decode($json, true) ?: [];
}

$pesquisa = trim($_GET['pesquisa'] ?? '');

if ($pesquisa !== '') {
          $pesquisa_lower = mb_strtolower($pesquisa);
          $contatos = array_filter($contatos, function ($contato) use ($pesquisa_lower) {
                    return
                              mb_stripos($contato['nome'], $pesquisa_lower) !== false ||
                              mb_stripos($contato['telefone'], $pesquisa_lower) !== false ||
                              mb_stripos($contato['email'], $pesquisa_lower) !== false;
          });
}

// Retorna só os contatos filtrados em JSON
echo json_encode(array_values($contatos), JSON_UNESCAPED_UNICODE);
