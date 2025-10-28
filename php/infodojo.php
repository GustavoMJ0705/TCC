<?php



// 1. Verifica se o ID foi passado na URL e se é um número
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Se não houver ID, redireciona para a página inicial para evitar erros
    header('Location: home.php');
    exit;
}

$id_academia = $_GET['id'];
$academia = null; // Inicializa a variável
$imagens = []; // Inicializa o array de imagens

// 2. Conexão com o banco de dados
require_once __DIR__ . '/db_connect.php';

try {

    // 4. Prepara e executa a consulta para buscar a academia específica
    $stmt = $pdo->prepare("SELECT * FROM tb_perfil_academia WHERE id_perfil_academia = ?");
    $stmt->execute([$id_academia]);
    
    // 5. Armazena os dados da academia na variável $academia
    $academia = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se nenhuma academia for encontrada com o ID, redireciona para a home
    if (!$academia) {
        header('Location: home.php');
        exit;
    }

    // 6. Busca as imagens da academia
    $stmt_img = $pdo->prepare("SELECT url_imagem FROM tb_perfil_academia_imagem WHERE id_perfil_academia = ?");
    $stmt_img->execute([$id_academia]);
    $imagens = $stmt_img->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    // Em caso de erro, exibe uma mensagem e interrompe o script
    die("Erro ao buscar informações da academia: " . $e->getMessage());
}
?>