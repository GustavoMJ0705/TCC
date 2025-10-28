<?php
require_once __DIR__ . '/../php/db_connect.php';

$academias = [];

try {

    // Consulta para buscar academias e a primeira imagem de cada uma
    $sql = "SELECT p.*, i.url_imagem 
            FROM tb_perfil_academia p
            LEFT JOIN (
                SELECT id_perfil_academia, MIN(url_imagem) as url_imagem 
                FROM tb_perfil_academia_imagem 
                GROUP BY id_perfil_academia
            ) i ON p.id_perfil_academia = i.id_perfil_academia";
            
    $stmt = $pdo->query($sql);
    $academias = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

function exibirAcademias($academias) {
    if (empty($academias)) {
        echo "<p>Nenhuma academia encontrada.</p>";
        return;
    }

    foreach ($academias as $academia) {
        // Define a imagem a ser usada: a do banco ou uma padrão
        $imagem = !empty($academia['url_imagem']) ? htmlspecialchars($academia['url_imagem']) : '../img/imgDojoTeste.jpg';
        
        echo '<div class="card">';
        echo '<a href="verdojo.php?id=' . htmlspecialchars($academia['id_perfil_academia']) . '">';
        echo '<img src="' . $imagem . '" alt="Imagem da academia ' . htmlspecialchars($academia['nm_academia']) . '">';
        echo '</a>';
        echo '<div class="card-content">';
        echo '<h3>' . htmlspecialchars($academia['nm_academia']) . '</h3>';
        echo '<p>' . htmlspecialchars(substr($academia['ds_descricao'], 0, 100)) . '...</p>';
        echo '<a href="../html/verdojo.php?id=' . htmlspecialchars($academia['id_perfil_academia']) . '"><button class="buttonDojo">Ver academia</button></a>';
        echo '</div>';
        echo '</div>';
        
    }
}
?>
