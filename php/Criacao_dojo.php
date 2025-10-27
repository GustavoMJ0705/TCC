<?php
$host = 'localhost';
$dbname = 'matchfight';
$username = 'root';
$password = 'root'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    

    $stmt = $pdo->prepare("INSERT INTO tb_perfil_academia
            (nm_academia, ds_descricao, nr_cep, ds_rua, nr_numero_endereco, ds_bairro, ds_cidade, ds_estado, nr_telefone, ds_email)
            VALUES
            (:nome, :descricao, :cep, :rua, :numero, :bairro, :cidade, :estado, :telefone, :email)");

    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':cep' => $cep,
        ':rua' => $rua,
        ':numero' => $numero,
        ':bairro' => $bairro,
        ':cidade' => $cidade,
        ':estado' => $estado,
        ':telefone' => $telefone,
        ':email' => $email
    ]);

    $id_perfil_academia = $pdo->lastInsertId();

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
       // Inserção de aulas (corrige nomes de POST)
    $aulaNomes = $_POST['aulaNome'] ?? [];
    $aulaTimesInicio = $_POST['aulaTime'] ?? [];
    $aulaTimesFim = $_POST['aulaTimefim'] ?? [];
    $aulaTipos = $_POST['aulaTipo'] ?? [];

    // Verifica se existem aulas e se os arrays têm tamanhos compatíveis
    if (!empty($aulaNomes)) {
        $count = count($aulaNomes);
        if ($count === count($aulaTimesInicio) && $count === count($aulaTimesFim) && $count === count($aulaTipos)) {
            $stmt_aula = $pdo->prepare("
                INSERT INTO tb_aulas (nm_aulas, hr_inicio_aula, hr_fim_aula, id_perfil_academia, id_professor)
                VALUES (:nome, :hora_inicio, :hora_fim, :id_academia, :id_professor)
            ");
            $stmt_aula_modalidade = $pdo->prepare("
                INSERT INTO aula_modalidade (id_aulas, id_modalidade) VALUES (:id_aula, :id_modalidade)
            ");

            for ($i = 0; $i < $count; $i++) {
                $nomeAula = trim($aulaNomes[$i]);
                $horaInicio = trim($aulaTimesInicio[$i]);
                $horaFim = trim($aulaTimesFim[$i]);
                $idModalidade = intval($aulaTipos[$i]);

                // Validações mínimas
                if ($nomeAula === '' || !preg_match('/^\d{2}:\d{2}$/', $horaInicio) || !preg_match('/^\d{2}:\d{2}$/', $horaFim)) {
                    continue;
                }

                // Se não houver professor no form, inserir NULL
                $idProfessor = null;
                if (isset($_POST['aulaProfessor'][$i]) && $_POST['aulaProfessor'][$i] !== '') {
                    $idProfessor = intval($_POST['aulaProfessor'][$i]);
                }

                $stmt_aula->execute([
                    ':nome' => $nomeAula,
                    ':hora_inicio' => $horaInicio,
                    ':hora_fim' => $horaFim,
                    ':id_academia' => $id_perfil_academia,
                    ':id_professor' => $idProfessor
                ]);

                $idAula = $pdo->lastInsertId();
                $stmt_aula_modalidade->execute([
                    ':id_aula' => $idAula,
                    ':id_modalidade' => $idModalidade
                ]);
            }
        }
    }

    $pdo->commit();
    header("Location: ../html/home.php");
    exit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Erro: " . $e->getMessage();
}
?>
