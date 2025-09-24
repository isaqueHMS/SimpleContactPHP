<?php
$arquivo = 'contatos.json';
$contatos = [];

if (file_exists($arquivo)) {
          $json = file_get_contents($arquivo);
          $contatos = json_decode($json, true) ?: [];
}

// Captura o ID do contato (via GET para mostrar o formulário ou POST para salvar)
$id = $_GET['id'] ?? ($_POST['id'] ?? null);

if (!$id) {
          echo "Contato não encontrado.";
          exit;
}

// Procura o contato pelo ID
$contato = null;
foreach ($contatos as $c) {
          if ($c['id'] === $id) {
                      $contato = $c;
                      break;
          }
}

if (!$contato) {
          echo "Contato não encontrado.";
          exit;
}

// Função simples para validar telefone (exemplo: só aceita números, espaços, +, -, parênteses, mínimo 8 caracteres)
function telefoneValido($telefone)
{
          return preg_match('/^\+?[\d\s\-\(\)]{8,15}$/', $telefone);
}

// Variável para mensagem de erro
$erro = null;

// Se veio POST, processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $nome = trim($_POST['nome'] ?? '');
          $telefone = trim($_POST['telefone'] ?? '');
          $email = trim($_POST['email'] ?? '');
          // Novos campos
          $dataNascimento = trim($_POST['data_nascimento'] ?? '');
          $observacoes = trim($_POST['observacoes'] ?? '');

          // Validação básica
          if (!$nome || !$telefone || !$email) {
                      $erro = "Por favor, preencha todos os campos.";
          } elseif (!telefoneValido($telefone)) {
                      $erro = "Telefone inválido. Use apenas números, espaços, parênteses, traços e '+' no início.";
          } else {
                      // Verifica duplicados ignorando o próprio contato
                      $duplicado = false;
                      foreach ($contatos as $c) {
                                  if ($c['id'] !== $id) {
                                              if ($c['nome'] === $nome || $c['telefone'] === $telefone || $c['email'] === $email) {
                                                          $duplicado = true;
                                                          break;
                                              }
                                  }
                      }

                      if ($duplicado) {
                                  $erro = "Já existe um contato com o mesmo nome, telefone ou email.";
                      } else {
                                  // Atualiza o contato
                                  foreach ($contatos as &$c) {
                                              if ($c['id'] === $id) {
                                                          $c['nome'] = $nome;
                                                          $c['telefone'] = $telefone;
                                                          $c['email'] = $email;
                                                          // Atualiza os novos campos
                                                          $c['data_nascimento'] = $dataNascimento;
                                                          $c['observacoes'] = $observacoes;
                                                          break;
                                              }
                                  }
                                  // Salva no arquivo
                                  file_put_contents($arquivo, json_encode($contatos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                                  // Redireciona para a lista após salvar
                                  header('Location: list.php');
                                  exit;
                      }
          }

          // Se houve erro, mantém os valores enviados no formulário
          $contato['nome'] = $nome;
          $contato['telefone'] = $telefone;
          $contato['email'] = $email;
          $contato['data_nascimento'] = $dataNascimento;
          $contato['observacoes'] = $observacoes;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
          <meta charset="UTF-8" />
          <title>Editar Contato</title>
          <link rel="stylesheet" href="styles/edit.css" />
</head>

<body>
          <main>
                      <h1>✏️ Editar Contato</h1>

                      <?php if ($erro): ?>
                                  <p style="color: red; font-weight: bold; text-align: center;"><?= htmlspecialchars($erro) ?></p>
                      <?php endif; ?>

                      <form method="POST" action="editar.php?id=<?= htmlspecialchars($id) ?>">
                                  <input type="hidden" name="id" value="<?= htmlspecialchars($contato['id']) ?>" />

                                  <label>Nome:<br />
                                              <input type="text" name="nome" value="<?= htmlspecialchars($contato['nome']) ?>" required />
                                  </label><br /><br />

                                  <label>Telefone:<br />
                                              <input type="text" name="telefone" value="<?= htmlspecialchars($contato['telefone']) ?>" required />
                                  </label><br /><br />

                                  <label>Email:<br />
                                              <input type="email" name="email" value="<?= htmlspecialchars($contato['email']) ?>" required />
                                  </label><br /><br />
                                  
                                  <label>Data de Nascimento:<br />
                                              <input type="date" name="data_nascimento" value="<?= htmlspecialchars($contato['data_nascimento'] ?? '') ?>" />
                                  </label><br /><br />
                                  <label>Observações:<br />
                                              <textarea name="observacoes"><?= htmlspecialchars($contato['observacoes'] ?? '') ?></textarea>
                                  </label><br /><br />

                                  <button type="submit" class="btn-salvar">💾 Salvar Alterações</button>
                                  <a href="list.php" class="btn-cancelar">Cancelar</a>
                      </form>
          </main>
</body>

</html>