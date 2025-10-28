<?php
require_once __DIR__ . '/../php/db_connect.php';

function getTodasAcademias() {
    global $pdo;
    
    try {
        $sql = "SELECT p.*, i.url_imagem 
                FROM tb_perfil_academia p
                LEFT JOIN (
                    SELECT id_perfil_academia, MIN(url_imagem) as url_imagem 
                    FROM tb_perfil_academia_imagem 
                    GROUP BY id_perfil_academia
                ) i ON p.id_perfil_academia = i.id_perfil_academia
                ORDER BY p.nm_academia";
                
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Erro ao buscar academias: " . $e->getMessage());
        return [];
    }
}

function exibirAcademias($academias) {
    if (empty($academias)) {
        echo "<p>Nenhuma academia encontrada.</p>";
        return;
    }

    foreach ($academias as $academia) {
        $imagem = !empty($academia['url_imagem']) ? htmlspecialchars($academia['url_imagem']) : '../img/imgDojoTeste.jpg';
        
        echo '<div class="card">';
        echo '<a href="verdojo.php?id=' . htmlspecialchars($academia['id_perfil_academia']) . '">';
        echo '<img src="' . $imagem . '" alt="Imagem da academia ' . htmlspecialchars($academia['nm_academia']) . '">';
        echo '</a>';
        echo '<div class="card-content">';
        echo '<h3>' . htmlspecialchars($academia['nm_academia']) . '</h3>';
        echo '<p>' . htmlspecialchars(substr($academia['ds_descricao'], 0, 100)) . '...</p>';
        echo '<a class="botao" href="../html/verdojo.php?id=' . htmlspecialchars($academia['id_perfil_academia']) . '">Ver academia</a>';
        echo '</div>';
        echo '</div>';
    }
}
?>
