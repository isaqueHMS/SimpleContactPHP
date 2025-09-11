<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $nome = trim($_POST['nome'] ?? '');
          $telefone = trim($_POST['telefone'] ?? '');
          $email = trim($_POST['email'] ?? '');

          // Validação básica
          if (!$nome || !$telefone || !$email) {
                    $erro = "Por favor, preencha todos os campos.";
                    // Aqui você pode redirecionar de volta com erro ou exibir mensagem
                    // Exemplo simples de mensagem e stop
                    echo "<p style='color: red;'>$erro</p>";
                    echo "<p><a href='adicionar.php'>Voltar</a></p>";
                    exit;
          }

          $arquivo = 'contatos.json';
          $contatos = [];

          if (file_exists($arquivo)) {
                    $json = file_get_contents($arquivo);
                    $contatos = json_decode($json, true) ?: [];
          }

          // Verifica duplicados
          foreach ($contatos as $c) {
                    if (
                              $c['nome'] === $nome ||
                              $c['telefone'] === $telefone ||
                              $c['email'] === $email
                    ) {
                              $erro = "Já existe um contato com o mesmo nome, telefone ou email.";
                              echo "<p style='color: red;'>$erro</p>";
                              echo "<p><a href='adicionar.php'>Voltar</a></p>";
                              exit;
                    }
          }

          // Cria novo contato com ID único (exemplo usando uniqid)
          $novoContato = [
                    'id' => uniqid(),
                    'nome' => $nome,
                    'telefone' => $telefone,
                    'email' => $email,
          ];

          // Adiciona e salva
          $contatos[] = $novoContato;
          file_put_contents($arquivo, json_encode($contatos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

          // Redireciona para a lista
          header('Location: list.php');
          exit;
}

echo "Requisição inválida.";
