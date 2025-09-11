    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8" />
        <title>Lista de Contatos</title>
        <link rel="stylesheet" href="styles/list.css" />
        <style>
            /* Só um estilinho básico */
            .search-input {
                width: 250px;
                padding: 8px;
                font-size: 16px;
                margin-bottom: 20px;
            }

            .btn {
                margin-right: 10px;
            }

            .action-buttons a {
                margin-right: 10px;
            }
        </style>
    </head>

    <body>
        <main>
            <h1>📋 Lista de Contatos</h1>
            <input
                type="text"
                id="search"
                class="search-input"
                placeholder="Buscar por nome, telefone ou email..."
                autocomplete="off" />
            <a href="adicionar.php" class="btn btn-add">➕ Adicionar Novo</a>

            <ul id="lista-contatos">
                <!-- A lista inicial pode estar vazia, vai preencher pelo JS -->
            </ul>
        </main>

        <script>
            const lista = document.getElementById('lista-contatos');
            const input = document.getElementById('search');

            async function buscarContatos(pesquisa = '') {
                try {
                    const response = await fetch(`busca.php?pesquisa=${encodeURIComponent(pesquisa)}`);
                    const contatos = await response.json();

                    if (contatos.length === 0) {
                        lista.innerHTML = '<li>Nenhum contato encontrado.</li>';
                        return;
                    }

                    lista.innerHTML = contatos.map(c => `
                <li>
                    <strong>${escapeHtml(c.nome)}</strong><br>
                    📞 ${escapeHtml(c.telefone)}<br>
                    ✉️ ${escapeHtml(c.email)}<br>
                    <div class="action-buttons">
                        <a href="editar.php?id=${encodeURIComponent(c.id)}" class="btn">✏️ Editar</a>
                        <a href="excluir.php?id=${encodeURIComponent(c.id)}" class="btn-secondary" onclick="return confirm('Tem certeza que deseja excluir este contato?')">🗑️ Excluir</a>
                    </div>
                </li>
            `).join('');

                } catch (err) {
                    lista.innerHTML = '<li>Erro ao carregar contatos.</li>';
                    console.error(err);
                }
            }

            // Função para escapar HTML (segurança)
            function escapeHtml(text) {
                return text.replace(/[&<>"']/g, function(m) {
                    return ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    })[m];
                });
            }

            // Busca inicial sem filtro
            buscarContatos();

            // Busca quando digitar (debounce pra não chamar a todo caractere)
            let timeout = null;
            input.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    buscarContatos(input.value);
                }, 300); // espera 300ms após parar de digitar
            });
        </script>

    </body>

    </html> 