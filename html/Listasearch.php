<?php
require_once __DIR__ . '/../php/db_connect.php';

function getTodasAcademias($order = 'relevance') {
    global $pdo;

    // Mapear valores permitidos para ORDER BY
    $orderWhitelist = [
        'relevance' => 'p.nm_academia ASC',
        'alpha_asc' => 'p.nm_academia ASC',
        'alpha_desc' => 'p.nm_academia DESC',
        'recent' => 'p.id_perfil_academia DESC' // se tiver coluna de data, preferir ela
    ];

    $orderBy = isset($orderWhitelist[$order]) ? $orderWhitelist[$order] : $orderWhitelist['relevance'];

    try {
        $sql = "SELECT p.*, i.url_imagem 
                FROM tb_perfil_academia p
                LEFT JOIN (
                    SELECT id_perfil_academia, MIN(url_imagem) as url_imagem 
                    FROM tb_perfil_academia_imagem 
                    GROUP BY id_perfil_academia
                ) i ON p.id_perfil_academia = i.id_perfil_academia
                ORDER BY " . $orderBy;

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Erro ao buscar academias: " . $e->getMessage());
        return [];
    }
}

function exibirAcademias($academias) {
    if (empty($academias)) {
        echo "<p class=\"nenhuma-academia\">Nenhuma academia cadastrada.</p>";
        return;
    }

    echo '<div class="dojo-list">';
    foreach ($academias as $academia) {
        $imagem = !empty($academia['url_imagem']) ? htmlspecialchars($academia['url_imagem']) : '../img/imgDojoTeste.jpg';
        $id = htmlspecialchars($academia['id_perfil_academia']);
        $nome = htmlspecialchars($academia['nm_academia']);
        $endereco = '';
        // montar endereço se houver campos (ajustar nomes conforme seu banco)
        if (!empty($academia['ds_endereco'])) {
            $endereco = htmlspecialchars($academia['ds_endereco']);
        } elseif (!empty($academia['nm_cidade']) || !empty($academia['nm_estado'])) {
            $endereco = trim((!empty($academia['nm_cidade']) ? $academia['nm_cidade'] : '') . ' - ' . (!empty($academia['nm_estado']) ? $academia['nm_estado'] : ''));
        }

        echo '<div class="dojo-card">';
        echo '  <div class="dojo-card-inner">';
        echo '    <a class="dojo-img-link" href="verdojo.php?id=' . $id . '">';
        echo '      <div class="dojo-img-wrap">';
        echo '        <img src="' . $imagem . '" alt="Imagem da academia ' . $nome . '">';
        echo '      </div>';
        echo '    </a>';
        echo '    <div class="dojo-info">';
        echo '      <h3 class="dojo-name">' . $nome . '</h3>';
        if (!empty($endereco)) {
            echo '      <p class="dojo-endereco"><strong>Endereço:</strong> ' . $endereco . '</p>';
        }
        // Avaliação - se houver campo, mostrar, senão apenas o rótulo
        if (!empty($academia['avaliacao'])) {
            echo '      <p class="dojo-avaliacao"><strong>Avaliação:</strong> ' . htmlspecialchars($academia['avaliacao']) . '</p>';
        } else {
            echo '      <p class="dojo-avaliacao"><strong>Avaliação:</strong></p>';
        }
        echo '      <a class="botao" href="verdojo.php?id=' . $id . '">Ver academia</a>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
    echo '</div>';
}
?>