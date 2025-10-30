<?php
require_once 'db_connect.php';

// Permitir acesso de qualquer origem durante o desenvolvimento
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Validar parâmetros (espera perfil_id, que é id_perfil_academia)
$perfil_id = filter_input(INPUT_GET, 'perfil_id', FILTER_VALIDATE_INT);
$modalidade_id = filter_input(INPUT_GET, 'modalidade_id', FILTER_VALIDATE_INT);

if (!$perfil_id || !$modalidade_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Parâmetros inválidos']);
    exit;
}

try {
    // Buscar horários disponíveis para a modalidade na academia específica
    $sql = "SELECT DISTINCT a.id_aulas, a.nm_aulas AS nome_aula, 
                   a.hr_inicio_aula AS horario_inicio, 
                   a.hr_fim_aula AS horario_fim, 
                   d.nm_dia AS dia_semana
            FROM tb_aulas a
            INNER JOIN tb_dia d ON a.id_dia = d.id_dia
            INNER JOIN aula_modalidade am ON a.id_aulas = am.id_aulas
            INNER JOIN tb_academia_aulas aa ON a.id_aulas = aa.id_aulas
            INNER JOIN tb_perfil_academia pa ON aa.id_perfil_academia = pa.id_perfil_academia
            WHERE pa.id_perfil_academia = :perfil_id
            AND am.id_modalidade = :modalidade_id
            ORDER BY FIELD(d.nm_dia, 'Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'),
                     a.hr_inicio_aula";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':perfil_id' => $perfil_id,
        ':modalidade_id' => $modalidade_id
    ]);

    $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($horarios);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar horários']);
    // Log do erro real (não expor ao usuário)
    error_log("Erro ao buscar horários: " . $e->getMessage());
}
?>