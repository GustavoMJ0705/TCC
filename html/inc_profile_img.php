<?php
// inc_profile_img.php - imprime o bloco do botão de perfil com imagem do usuário quando disponível
// Uso: include __DIR__ . '/inc_profile_img.php';

// assumes session already started in including file
$tipo = $_SESSION['tipo'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;
$profileSrc = '../img/Perfil.png'; // default used in nav
if (!empty($tipo) && !empty($id_usuario)) {
    $uploadsDirRel = '../uploads/';
    $uploadsDir = __DIR__ . '/../uploads';
    $exts = ['png','jpg','jpeg','gif','webp'];
    foreach ($exts as $e) {
        $candidate = $uploadsDir . '/' . $tipo . '_' . $id_usuario . '.' . $e;
        if (file_exists($candidate)) {
            $profileSrc = $uploadsDirRel . $tipo . '_' . $id_usuario . '.' . $e;
            break;
        }
    }
}
?>
<div class="Perfil">
    <?php if (isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
        <a href="mperfil.php" class="lbottom_AlunoProf"><img src="<?php echo htmlspecialchars($profileSrc); ?>" alt=""></a>
    <?php endif; ?>
</div>
