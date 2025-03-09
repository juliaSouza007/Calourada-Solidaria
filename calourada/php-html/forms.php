<?php
session_start();
require 'conexao.php';

$conexao = new Conexao();
$pdo = $conexao->getPdo();

$salaCores = [
    "101" => "#ff0000",
    "102" => "#ffdb58",
    "103" => "#9400d3",
    "104" => "#0096ff",
    "105" => "#008000",
    "106" => "#ff5da2"
];

$salaSelecionada = isset($_GET['salas']) ? $_GET['salas'] : [];
$registros = [];
$totaisPorSala = [];

if (!empty($salaSelecionada)) {
    foreach ($salaSelecionada as $sala) {
        if ($sala === 'todas') {
            foreach (array_keys($salaCores) as $s) {
                $stmt = $pdo->prepare("SELECT *, SUM(quantidade) OVER() as total FROM `$s` ORDER BY data_registro DESC");
                $stmt->execute();
                $dados = $stmt->fetchAll();

                if ($dados) {
                    $registros[$s] = $dados;
                    $totaisPorSala[$s] = array_sum(array_column($dados, 'quantidade'));
                }
            }
            break;
        } else {
            $stmt = $pdo->prepare("SELECT *, SUM(quantidade) OVER() as total FROM `$sala` ORDER BY data_registro DESC");
            $stmt->execute();
            $dados = $stmt->fetchAll();

            if ($dados) {
                $registros[$sala] = $dados;
                $totaisPorSala[$sala] = array_sum(array_column($dados, 'quantidade'));
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Doações</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Itens para Doação</h2>

        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="mensagem <?php echo $_SESSION['tipo_mensagem']; ?>">
                <?php 
                    echo $_SESSION['mensagem']; 
                    unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']);
                ?>
            </div>
        <?php endif; ?>

        <form action="processaRegistro.php" method="POST">
            <table>
                <tr>
                    <td><label for="sala">Sala:</label></td>
                    <td>
                        <select id="sala" name="sala" required>
                            <?php foreach ($salaCores as $sala => $cor): ?>
                                <option value="<?php echo $sala; ?>"><?php echo $sala; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="nome">Nome:</label></td>
                    <td><input type="text" id="nome" name="nome" required></td>
                </tr>
                <tr>
                    <td><label for="tipo_item">Tipo de Item:</label></td>
                    <td>
                        <select id="tipo_item" name="tipo_item" required>
                            <option value="roupa">Roupa</option>
                            <option value="higiene">Higiene Pessoal</option>
                            <option value="alimento">Alimento</option>
                            <option value="brinquedo">Brinquedo</option>
                            <option value="sapato">Sapato</option>
                            <option value="livro">Livro</option>
                        </select>
                    </td>
                </tr>
                <tr id="limited_section" style="display: none;">
                    <td><label for="nome_item">Nome do Item:</label></td>
                    <td><input type="text" id="nome_item" name="nome_item"></td>
                </tr>
                <tr>
                    <td><label for="quantidade">Quantidade:</label></td>
                    <td><input type="number" id="quantidade" name="quantidade" min="1" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit">Registrar</button>
                    </td>
                </tr>
            </table>
        </form>

        <h2>Consultar Registros</h2>
        <form method="GET">
            <label>Escolha as salas:</label><br><br>
            <input type="checkbox" id="todas" name="salas[]" value="todas" onclick="selecionarTodas()">
            <label for="todas">Todas as Salas</label><br>

            <?php foreach ($salaCores as $sala => $cor): ?>
                <input type="checkbox" id="sala_<?php echo $sala; ?>" name="salas[]" value="<?php echo $sala; ?>">
                <label for="sala_<?php echo $sala; ?>"><?php echo $sala; ?></label><br>
            <?php endforeach; ?> <br>

            <button type="submit">Ver Registros</button>
        </form>

        <?php if (!empty($salaSelecionada) && !empty($registros)): ?>
            <?php foreach ($registros as $sala => $dados): ?>
                <h3 style="color: <?php echo $salaCores[$sala]; ?>;">Registros da Sala <?php echo $sala; ?></h3>
                <p style="color: <?php echo $salaCores[$sala]; ?>;"><strong>Total de itens registrados: <?php echo $totaisPorSala[$sala]; ?></strong></p>
                <table class="tabela-dados" data-sala="<?php echo $sala; ?>">
                <thead style="background-color: <?php echo $salaCores[$sala]; ?>; color: white;">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Tipo de Item</th>
                        <th>Quantidade</th>
                        <th>Nome do Alimento</th>
                        <th>Data Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dados as $registro): ?>
                        <tr style="border: 1px solid <?php echo $salaCores[$sala]; ?>;">
                            <td><?php echo $registro['id']; ?></td>
                            <td><?php echo $registro['nome']; ?></td>
                            <td><?php echo $registro['tipo_item']; ?></td>
                            <td><?php echo $registro['quantidade']; ?></td>
                            <td><?php echo $registro['nome_item'] ?? '-'; ?></td>
                            <td><?php echo $registro['data_registro']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endforeach; ?>
        <?php elseif (!empty($salaSelecionada)): ?>
            <p>Nenhum registro encontrado para a sala selecionada.</p>
        <?php endif; ?>
    </div>

    <script>
        function selecionarTodas() {
            var todasCheckbox = document.getElementById('todas');
            var checkboxes = document.querySelectorAll('input[name="salas[]"]');

            checkboxes.forEach(function(checkbox) {
                if (checkbox !== todasCheckbox) {
                    checkbox.checked = todasCheckbox.checked;
                }
            });
        }

        document.getElementById('tipo_item').addEventListener('change', function() {
            var tipoSelecionado = this.value;
            var limitedSection = document.getElementById('limited_section');
            limitedSection.style.display = (tipoSelecionado === 'alimento' || tipoSelecionado === 'higiene') ? 'table-row' : 'none';
        });
    </script>
</body>
</html>
