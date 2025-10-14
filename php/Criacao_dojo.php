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
