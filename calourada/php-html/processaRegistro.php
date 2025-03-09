<?php
session_start();
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexao = new Conexao();
    $pdo = $conexao->getPdo();

    $sala = $_POST['sala'];
    $nome = $_POST['nome'];
    $tipo_item = $_POST['tipo_item'];
    $quantidade = $_POST['quantidade'];
    $nome_item = null;

    if ($tipo_item === 'alimento') {
        $nome_item = $_POST['nome_item'];

        // Verifica se já existem 15 unidades desse alimento na sala
        $stmt_verifica = $pdo->prepare("SELECT SUM(quantidade) as total FROM `$sala` WHERE tipo_item = 'alimento' AND nome_item = ?");
        $stmt_verifica->execute([$nome_item]);
        $totalExistente = $stmt_verifica->fetchColumn() ?? 0;

        if (($totalExistente + $quantidade) > 15) {
            $_SESSION['mensagem'] = "Erro: O limite de 15 unidades para o item '$nome_item' já foi atingido nesta sala.";
            $_SESSION['tipo_mensagem'] = "erro";
            header("Location: forms.php");
            exit();
        }
    }

    if ($tipo_item === 'higiene') {
        $nome_item = $_POST['nome_item'];

        // Verifica se já existem 15 unidades desse item de higiene na sala
        $stmt_verifica = $pdo->prepare("SELECT SUM(quantidade) as total FROM `$sala` WHERE tipo_item = 'higiene' AND nome_item = ?");
        $stmt_verifica->execute([$nome_item]);
        $totalExistente = $stmt_verifica->fetchColumn() ?? 0;

        if (($totalExistente + $quantidade) > 15) {
            $_SESSION['mensagem'] = "Erro: O limite de 15 unidades para o item '$nome_item' já foi atingido nesta sala.";
            $_SESSION['tipo_mensagem'] = "erro";
            header("Location: forms.php");
            exit();
        }
    }

    // Insere no banco de dados na tabela correspondente à sala escolhida
    $stmt = $pdo->prepare("INSERT INTO `$sala` (nome, tipo_item, quantidade, nome_item) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $tipo_item, $quantidade, $nome_item]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['mensagem'] = "Registro inserido com sucesso na sala $sala!";
        $_SESSION['tipo_mensagem'] = "sucesso";
    } else {
        $_SESSION['mensagem'] = "Erro ao inserir registro.";
        $_SESSION['tipo_mensagem'] = "erro";
    }

    header("Location: forms.php");
    exit();
}
?>
