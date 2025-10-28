<?php
require_once __DIR__ . '/../php/db_connect.php';

// Get academy id from query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<p>ID da academia inválido.</p>";
    return;
}

try {
    // Select dia id as well so we can reliably order/group by numeric day
    $sql = "SELECT
        a.id_aulas,
        a.nm_aulas AS nome_aula,
        a.hr_inicio_aula AS horario_inicio,
        a.hr_fim_aula AS horario_fim,
        d.id_dia AS dia_id,
        d.nm_dia AS dia_semana
    FROM
        tb_academia_aulas aa
    JOIN
        tb_aulas a ON aa.id_aulas = a.id_aulas
    JOIN
        tb_dia d ON a.id_dia = d.id_dia
    WHERE
        aa.id_perfil_academia = ?
    ORDER BY
        d.id_dia, a.hr_inicio_aula";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $aulas_academia = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Map day id -> display name (assumes tb_dia ids: 1=Domingo, 2=Segunda ... 7=Sábado)
$dayNamesById = [1=>'Domingo',2=>'Segunda',3=>'Terça',4=>'Quarta',5=>'Quinta',6=>'Sexta',7=>'Sábado'];

// Order of days to render (by id)
$daysOrder = array_values($dayNamesById);

// Group by normalized day name (use dia_id to avoid inconsistent text in DB)
$grouped = [];
foreach ($aulas_academia as $row) {
    $diaId = isset($row['dia_id']) ? intval($row['dia_id']) : 0;
    $dayName = $dayNamesById[$diaId] ?? ($row['dia_semana'] ?? 'Outros');
    if (!isset($grouped[$dayName])) {
        $grouped[$dayName] = [];
    }
    $grouped[$dayName][] = $row;
}

function exibirHorariosPorDia($grouped, $daysOrder)
{
    // If no horarios at all
    $hasAny = false;
    foreach ($grouped as $g) {
        if (!empty($g)) {
            $hasAny = true;
            break;
        }
    }

    if (!$hasAny) {
        echo "<p>A academia não possui uma grade de aulas definida</p>";
        return;
    }

    echo '<div class="card-wrapper">';
    echo '    <div class="infoHorarios">';
    echo '        <img src="../img/relogio.png" alt="Relógio" />';
    echo '        <h3>Horários da Semana</h3>';
    echo '    </div>';
    echo '    <div class="semana-horarios">';

    foreach ($daysOrder as $dayName) {
        echo '        <div class="dia-coluna">';
        echo '            <div class="dia-nome">' . htmlspecialchars($dayName) . '</div>';

        if (isset($grouped[$dayName]) && !empty($grouped[$dayName])) {
            foreach ($grouped[$dayName] as $item) {
                $inicio = isset($item['horario_inicio']) ? substr($item['horario_inicio'], 0, 5) : '00:00';
                $fim = isset($item['horario_fim']) ? substr($item['horario_fim'], 0, 5) : '00:00';
                $nome = isset($item['nome_aula']) ? htmlspecialchars($item['nome_aula']) : '';
                // Show class name above the time
                echo '            <div class="horario-item">';
                echo '                <div class="nome-aula">' . $nome . '</div>';
                echo '                <div class="horario-time">' . $inicio . ' - ' . $fim . '</div>';
                echo '            </div>';
            }
        } else {
            // For Sunday it might be closed, show Fechado; otherwise show placeholder
           
                echo '            <div class="horario-item">-</div>';
            
        }

        echo '        </div>';
    }

    echo '    </div>';
    echo '</div>';
}



?>
