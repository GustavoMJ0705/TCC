<?php
require_once __DIR__ . '/db_connect.php';

try {

    // DEBUG: log versão do servidor e definição de tb_aulas conforme visto pela conexão PDO
    try {
        $serverInfo = $pdo->getAttribute(PDO::ATTR_SERVER_INFO) ?: '';
        file_put_contents(__DIR__ . '/../debug_sql.log', date('c') . " - PDO server info: " . $serverInfo . PHP_EOL, FILE_APPEND);
        $row = $pdo->query("SHOW CREATE TABLE tb_aulas")->fetch(PDO::FETCH_ASSOC);
        if ($row && isset($row['Create Table'])) {
            file_put_contents(__DIR__ . '/../debug_sql.log', date('c') . " - SHOW CREATE TABLE tb_aulas:\n" . $row['Create Table'] . PHP_EOL . PHP_EOL, FILE_APPEND);
        } else {
            // some MySQL versions return numeric keys
            $row2 = $pdo->query("SHOW CREATE TABLE tb_aulas")->fetch(PDO::FETCH_NUM);
            if ($row2 && isset($row2[1])) {
                file_put_contents(__DIR__ . '/../debug_sql.log', date('c') . " - SHOW CREATE TABLE tb_aulas (alt):\n" . $row2[1] . PHP_EOL . PHP_EOL, FILE_APPEND);
            }
        }
    } catch (Exception $e) {
        file_put_contents(__DIR__ . '/../debug_sql.log', date('c') . " - SHOW CREATE TABLE error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    }

    // Recebe os dados do formulário
    $nome = $_POST['dojoName'];
    $descricao = $_POST['dojoDescription'];
    $cep = $_POST['dojoCEP'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $telefone = $_POST['dojoPhone'];
    $email = $_POST['dojoEmail'];

   



    // Validação de email existente
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_perfil_academia WHERE ds_email = :email");
    $stmt->execute([':email' => $email]);
    $existe = $stmt->fetchColumn();

    if ($existe > 0) {
         header("Location: ../html/criardojo.php?erroemail=E-mail já cadastrado!");
        exit();
    }

    $pdo->beginTransaction();

    // Ler arrays de aulas antes de criar o perfil, porque o perfil referencia uma aula (id_aulas)
    $aulaNomes = $_POST['aulaNome'] ?? [];
    $aulaTimesInicio = $_POST['aulaTime'] ?? [];
    $aulaTimesFim = $_POST['aulaTimefim'] ?? [];
    $aulaTipos = $_POST['aulaTipo'] ?? [];
    $aulaDias = $_POST['aulaDia'] ?? [];
    $aulaDates = $_POST['aulaDate'] ?? [];

    // Inserir aulas primeiro (precisamos do id_aulas para depois vincular à academia)
    $firstAulaId = null;
    $insertedAulaIds = []; // coletar IDs inseridos para popular tb_academia_aulas
    if (!empty($aulaNomes)) {
        $countA = count($aulaNomes);
    // detectar nome da coluna de dia (algumas versões usam id_dia, outras id_dia_semana)
    $diaColumn = 'id_dia';
    $colStmtDia = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = 'tb_aulas' AND COLUMN_NAME IN ('id_dia','id_dia_semana')");
    $colStmtDia->execute([':db' => $dbname]);
    $foundDiaCol = $colStmtDia->fetchColumn();
    if ($foundDiaCol !== false && in_array($foundDiaCol, ['id_dia','id_dia_semana'])) {
        $diaColumn = $foundDiaCol;
    }

    // preparar insert de aula usando o nome de coluna de dia correto
    $stmt_insert_aula = $pdo->prepare("INSERT INTO tb_aulas (nm_aulas, ds_aulas, duracao_em_min, hr_inicio_aula, hr_fim_aula, id_professor, {$diaColumn}, dt_hora_aula) VALUES (:nm, :ds, :dur, :hin, :hfn, :prof, :id_dia, :dt)");
        $stmt_aula_modalidade = $pdo->prepare("INSERT INTO aula_modalidade (id_aulas, id_modalidade) VALUES (:id_aula, :id_modalidade)");

        for ($i = 0; $i < $countA; $i++) {
            $nomeAula = trim($aulaNomes[$i]);
            $horaInicio = trim($aulaTimesInicio[$i] ?? '');
            $horaFim = trim($aulaTimesFim[$i] ?? '');
            $idModalidade = intval($aulaTipos[$i] ?? 0);

            if ($nomeAula === '' || $horaInicio === '' || $horaFim === '') {
                continue;
            }

            // professor opcional
            $idProfessor = null;
            if (isset($_POST['aulaProfessor'][$i]) && $_POST['aulaProfessor'][$i] !== '') {
                $idProfessor = intval($_POST['aulaProfessor'][$i]);
            }

            // id_dia vindo do form (deve existir e ser 1..7)
            $idDia = intval($aulaDias[$i] ?? 0);
            // usar valor enviado para dt_hora_aula (nome do dia) ou NULL
            $dtHora = isset($aulaDates[$i]) ? $aulaDates[$i] : null;

            // Se id_dia não foi enviado ou é inválido, tentar mapear pelo nome do dia
            if ($idDia <= 0 && !empty($dtHora)) {
                // Busca no banco por correspondência (case-insensitive). Primeiro por igualdade, depois por LIKE
                $stmt_day = $pdo->prepare("SELECT id_dia FROM tb_dia WHERE LOWER(nm_dia) = LOWER(:nm) LIMIT 1");
                $stmt_day->execute([':nm' => $dtHora]);
                $found = $stmt_day->fetchColumn();
                if (!$found) {
                    $stmt_day = $pdo->prepare("SELECT id_dia FROM tb_dia WHERE LOWER(nm_dia) LIKE CONCAT('%', LOWER(:nm), '%') LIMIT 1");
                    $stmt_day->execute([':nm' => $dtHora]);
                    $found = $stmt_day->fetchColumn();
                }
                if ($found) {
                    $idDia = intval($found);
                }
            }

            if ($idDia <= 0) {
                // se faltar id_dia mesmo após tentativa de mapeamento, pular a inserção desta aula
                continue;
            }

            // debug: logar SQL e parâmetros antes do execute
            try {
                file_put_contents(__DIR__ . '/../debug_sql.log', date('c') . " - INSERT tb_aulas: " . $stmt_insert_aula->queryString . PHP_EOL, FILE_APPEND);
            } catch (Exception $e) {
                // ignore logging errors
            }

            $params_insert = [
                ':nm' => $nomeAula,
                ':ds' => null,
                ':dur' => null,
                ':hin' => $horaInicio,
                ':hfn' => $horaFim,
                ':prof' => $idProfessor,
                ':id_dia' => $idDia,
                ':dt' => $dtHora
            ];
            try {
                file_put_contents(__DIR__ . '/../debug_sql.log', date('c') . ' - PARAMS: ' . json_encode($params_insert) . PHP_EOL . PHP_EOL, FILE_APPEND);
            } catch (Exception $e) {}

            $stmt_insert_aula->execute($params_insert);

            $insertedAulaId = $pdo->lastInsertId();
            if ($firstAulaId === null) $firstAulaId = $insertedAulaId;
            $insertedAulaIds[] = intval($insertedAulaId);

            // vincular modalidade se informado
            if ($idModalidade > 0) {
                $stmt_aula_modalidade->execute([
                    ':id_aula' => $insertedAulaId,
                    ':id_modalidade' => $idModalidade
                ]);
            }
        }
    }

    

    // Agora inserir o perfil da academia (não existe mais coluna id_aulas na tabela)
    $stmt = $pdo->prepare("INSERT INTO tb_perfil_academia
            (nm_academia, vl_mensalidade, nr_cep, ds_rua, nr_numero_endereco, ds_bairro, ds_cidade, ds_estado, ds_descricao, id_modalidade, latitude, longitude, nr_telefone, ds_email)
            VALUES
            (:nome, :vl, :cep, :rua, :numero, :bairro, :cidade, :estado, :descricao, :id_modalidade, :lat, :lng, :telefone, :email)");

    $stmt->execute([
        ':nome' => $nome,
        ':vl' => null,
        ':cep' => $cep,
        ':rua' => $rua,
        ':numero' => $numero,
        ':bairro' => $bairro,
        ':cidade' => $cidade,
        ':estado' => $estado,
        ':descricao' => $descricao,
        ':id_modalidade' => null,
        ':lat' => null,
        ':lng' => null,
        ':telefone' => $telefone,
        ':email' => $email
    ]);

    $id_perfil_academia = $pdo->lastInsertId();

        // Verificar se obtivemos um id válido da academia criada
        if (empty($id_perfil_academia) || !is_numeric($id_perfil_academia)) {
            throw new Exception('Não foi possível obter o id da academia criada (id_perfil_academia vazio)');
        }

        // Se inserimos aulas antes de criar o perfil, vincular essas aulas à academia criada
        if (!empty($insertedAulaIds)) {
            $stmt_academia_aulas = $pdo->prepare("INSERT INTO tb_academia_aulas (id_perfil_academia, id_aulas) VALUES (:id_perfil, :id_aula)");
            foreach ($insertedAulaIds as $ida) {
                $stmt_academia_aulas->execute([
                    ':id_perfil' => intval($id_perfil_academia),
                    ':id_aula' => intval($ida)
                ]);
            }
        }

    // Processamento das imagens
    if (isset($_FILES['dojoImages'])) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $stmt_img = $pdo->prepare("INSERT INTO tb_perfil_academia_imagem (id_perfil_academia, url_imagem) VALUES (?, ?)");

        foreach ($_FILES['dojoImages']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['dojoImages']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = uniqid() . '-' . basename($_FILES['dojoImages']['name'][$key]);
                $targetFilePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($tmp_name, $targetFilePath)) {
                    // Salva o caminho relativo no banco
                    $dbPath = '../uploads/' . $fileName;
                    $stmt_img->execute([$id_perfil_academia, $dbPath]);
                }
            }
        }
    }
    

    $pdo->commit();
    header("Location: ../html/home.php");
    exit();

} catch (PDOException $e) {

    echo "Erro: " . $e->getMessage();
}
?>
