<?php
// Processa o POST para adicionar contato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!$nome || !$telefone || !$email) {
        $erro = "Todos os campos são obrigatórios.";
    } else {
        // Gera ID único
        $id = uniqid();

        $novoContato = [
            'id' => $id,
            'nome' => $nome,
            'telefone' => $telefone,
            'email' => $email,
        ];

        $arquivo = 'contatos.json';
        $contatos = [];

        if (file_exists($arquivo)) {
            $json = file_get_contents($arquivo);
            $contatos = json_decode($json, true) ?: [];
        }

        // Evitar duplicados por email (opcional)
        foreach ($contatos as $c) {
            if ($c['email'] === $email) {
                $erro = "Já existe um contato com esse email.";
                break;
            }
        }

        if (!isset($erro)) {
            $contatos[] = $novoContato;
            file_put_contents($arquivo, json_encode($contatos, JSON_PRETTY_PRINT));
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
                <input type="text" name="nome" required />
            </label><br /><br />
            <label>Telefone:<br />
                <input type="text" name="telefone" required />
            </label><br /><br />
            <label>Email:<br />
                <input type="email" name="email" required />
            </label><br /><br />
            <button type="submit">Salvar</button>
            <a href="list.php">Cancelar</a>
        </form>
    </main>
</body>
</html>
