<?php
$arquivo = 'contatos.json';
$contatos = [];

if (file_exists($arquivo)) {
    $json = file_get_contents($arquivo);
    $contatos = json_decode($json, true) ?: [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Contatos</title>
    <link rel="stylesheet" href="styles/list.css" />
</head>
<body>
<main>
    <h1>📋 Lista de Contatos</h1>
    <a href="adicionar.php" class="btn btn-add">➕ Adicionar Novo</a>

    <ul>
        <?php if (count($contatos) === 0): ?>
            <li>Nenhum contato cadastrado.</li>
        <?php else: ?>
            <?php foreach ($contatos as $contato): ?>
                <li>
                    <strong><?= htmlspecialchars($contato['nome']) ?></strong><br />
                    📞 <?= htmlspecialchars($contato['telefone']) ?><br />
                    ✉️ <?= htmlspecialchars($contato['email']) ?><br />
                    
                    <div class="action-buttons">
                        <a href="editar.php?id=<?= urlencode($contato['id']) ?>" class="btn">✏️ Editar</a>
                        
                        <a href="excluir.php?id=<?= urlencode($contato['id']) ?>"
                           class="btn-secondary"
                           onclick="return confirm('Tem certeza que deseja excluir este contato?')">
                           🗑️ Excluir
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</main>
</body>
</html>
