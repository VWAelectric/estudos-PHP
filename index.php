<?php

// Inicializa um array para armazenar os produtos
$produtos = [];

// Verifica se o formulário foi enviado (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? '';

    // Validação básica (opcional, mas recomendada)
    if (!empty($descricao) && is_numeric($preco)) {
        // Adiciona o novo produto ao array
        $produtos[] = [
            'descricao' => $descricao,
            'preco' => floatval($preco) // Converte o preço para um número float
        ];

        // Se já existirem produtos cadastrados anteriormente na sessão,
        // vamos mesclar com os novos produtos.
        if (isset($_SESSION['produtos_cadastrados'])) {
            $produtos = array_merge($_SESSION['produtos_cadastrados'], $produtos);
        }

        // Armazena o array de produtos na sessão para manter os dados
        // mesmo após o redirecionamento ou recarregamento da página.
        session_start();
        $_SESSION['produtos_cadastrados'] = $produtos;

        // Redireciona de volta para a página para limpar o formulário
        // e exibir os produtos atualizados.
        header("Location: index.php");
        exit();
    } else {
        // Exibe uma mensagem de erro se a validação falhar (opcional)
        $erro = "Por favor, preencha a descrição e um preço válido.";
    }
} else {
    // Se a página for acessada via GET (primeira vez ou após o redirecionamento),
    // tenta recuperar os produtos da sessão.
    session_start();
    if (isset($_SESSION['produtos_cadastrados'])) {
        $produtos = $_SESSION['produtos_cadastrados'];
    }
}

?>

<table border="1">
    <tr>
        <th>Descrição</th>
        <th>Preço</th>
    </tr>

    <?php foreach ($produtos as $produto): ?>

        <tr>
            <td>
                <?php echo htmlspecialchars($produto['descricao']); ?>
                <td>
                     R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                </td>
            </td>
        </tr>

    <?php endforeach; ?>
</table>