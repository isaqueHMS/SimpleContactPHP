<?php
// Caminho do arquivo JSON que armazena os contatos
$arquivo = 'contatos.json';

// Verifica se o arquivo existe, se não, cria um array vazio
if (file_exists($arquivo)) {
          $contatos = json_decode(file_get_contents($arquivo), true);
          if (!is_array($contatos)) {
                    $contatos = [];
          }
} else {
          $contatos = [];
}

// Recebe os dados do formulário
$id = isset($_POST['id']) ? intval($_POST['id']) : -1;
$nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Validações simples
if ($id < 0 || $id >= count($contatos)) {
          die('Contato não encontrado!');
}
if (empty($nome) || empty($telefone) || empty($email)) {
          die('Todos os campos são obrigatórios!');
}

// Atualiza o contato
$contatos[$id] = [
          'nome' => $nome,
          'telefone' => $telefone,
          'email' => $email,
];

// Salva o arquivo JSON
file_put_contents($arquivo, json_encode($contatos, JSON_PRETTY_PRINT));

// Redireciona para a lista de contatos
header('Location: list.html');
exit;
