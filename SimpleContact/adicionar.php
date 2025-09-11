<?php
// Função para validar telefone simples
function telefoneValido($telefone)
{
    // Permite dígitos, espaço, +, (), - e .
    // Exemplo válido: +55 (11) 91234-5678
    return preg_match('/^\+?[\d\s\-\(\)\.]{8,15}$/', $telefone);
}

// Processa o POST para adicionar contato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!$nome || !$telefone || !$email) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif (!telefoneValido($telefone)) {
        $erro = "Telefone inválido. Use apenas números, espaço, parênteses, traços e '+' no início.";
    } else {
        $arquivo = 'contatos.json';
        $contatos = [];

        if (file_exists($arquivo)) {
            $json = file_get_contents($arquivo);
            $contatos = json_decode($json, true) ?: [];
        }

        // Verifica duplicados em nome, telefone ou email
        $duplicado = false;
        foreach ($contatos as $c) {
            if (
                $c['nome'] === $nome ||
                $c['telefone'] === $telefone ||
                $c['email'] === $email
            ) {
                $duplicado = true;
                break;
            }
        }

        if ($duplicado) {
            $erro = "Já existe um contato com o mesmo nome, telefone ou email.";
        } else {
            // Gera ID único e adiciona
            $id = uniqid();
            $novoContato = [
                'id' => $id,
                'nome' => $nome,
                'telefone' => $telefone,
                'email' => $email,
            ];

            $contatos[] = $novoContato;
            file_put_contents($arquivo, json_encode($contatos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            header('Location: list.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Adicionar Contato</title>
    <link rel="stylesheet" href="styles/edit.css" />
</head>

<body>
    <main>
        <h1>➕ Adicionar Novo Contato</h1>

        <?php if (isset($erro)): ?>
            <p style="color:red"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Nome:<br />
                <input type="text" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required />
            </label><br /><br />
            <label>Telefone:<br />
                <input type="text" name="telefone" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" required />
            </label><br /><br />
            <label>Email:<br />
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required />
            </label><br /><br />
            <button type="submit">Salvar</button>
            <a href="list.php">Cancelar</a>
        </form>
    </main>
</body>

</html>