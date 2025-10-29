<?php

require_once __DIR__ . '/../php/db_connect.php';

// Página de matrícula de aluno
// Mantemos a consistência incluindo a navbar e os estilos
session_start();
// tipo e id do usuário (usados para buscar dados do usuário logado)
$tipo = $_SESSION['tipo'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$dados = [
    'nome' => '',
    'telefone' => '',
    'genero' => '',
    'nascimento' => '',
    'email' => '',
    'senha' => ''
];

// busca generos para o select (mesma lógica de cad_aluno.php)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_genero, nm_genero FROM tb_genero";
    $result = $pdo->query($sql);
    $generos = $result->fetchAll(PDO::FETCH_ASSOC);

    // Se houver um usuário logado, buscar seus dados para pré-preencher o formulário
    if ($id_usuario) {
        $stmt = $pdo->prepare("SELECT nm_aluno AS nome, nr_telefone AS telefone, ds_email AS email, nm_senha_hash AS senha, id_genero AS genero, dt_nascimento AS nascimento FROM tb_aluno WHERE id_aluno = :id");
        $stmt->execute([':id' => $id_usuario]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC) ?: $dados;
    }
} 

catch (PDOException $e) {
    $generos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matricula - Inscrição de Aluno</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/matricula.css">
</head>
<body>
     <header>
     <nav class="navbar">
        <div class="menu-search">
            <div class="menu-icon" onclick="toggleSidebar()">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <a href="search.php">
                <div class="search">
                    <button class="buttonSearch hover-button">
                        <img src="../img/lupa.png" alt="Lupa" class="icon-lupa">
                        <span class="search-text">Pesquisar Dojo</span>
                    </button>
                </div>
            </a>
        </div>
            <div class="logo">
                <img src="../img/match_ofc2.0.png" alt="Logo do Match Fight, um homem chutando ao lado da escrita Match Fight" width="150rem">
            </div>
           
            <div class="cadastrar">
                <?php
               
                if (!isset($_SESSION['professor_id']) && !isset($_SESSION['aluno_id']) && !isset($_SESSION['academia_id'])): ?>
                    <a href="contas.html" id="login-link" class="lbottom">Cadastrar</a>
                <?php endif; ?>

                <?php 
                if (isset($_SESSION['academia_id'])): ?>
                    <a href="criardojo.php" class="lbottom">Criar Academia</a>
                <?php endif; ?>
            </div>

            <div class="Perfil">
                <?php
                if (isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <a href="mperfil.php" class="l  bottom_AlunoProf"><img src="../img/Perfil.png" alt=""></a>
                <?php endif; ?>
            </div>
        </nav>
        
        <aside class="sidebar" id="sidebar">
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
            <ul>                  
                <li><a href="home.php">Home</a></li>          
                <li><a href="suporte_tecnico.php">Suporte técnico</a></li>
                <li><a href="seja_parceiro.php">Seja um parceiro</a></li>
                <li><a href="#">Calendário de aulas</a></li>
                <?php if (isset($_SESSION['academia_id']) || isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <li><a href="../php/logout.php" id="logout-link">Sair</a></li>
               
                
                <?php endif; ?>
            </ul>
        </aside>
    </header>

    <main>
        <div class="matricula-wrapper">
            <div class="matricula-card">
                <h2>Inscrição de Aluno</h2>
                <p class="lead">Preencha seus dados para se inscrever nesta academia.</p>

            <?php $academia_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : ''; ?>

            <form action="../php/matricula_usuario.php" method="post" autocomplete="on" class="matricula-form">
                <input type="hidden" name="academia_id" value="<?php echo htmlspecialchars($academia_id); ?>">
                <div class="form-row">
                    <label for="nome">Nome completo</label>
                    <input id="nome" name="nome" type="text" maxlength="100" required value="<?php echo htmlspecialchars($dados['nome']); ?>">
                </div>

                <div class="form-row">
                    <label for="nascimento">Data de nascimento</label>
                    <input id="nascimento" name="nascimento" type="date" min="1960-01-01" max="2025-12-31" value="<?php echo htmlspecialchars($dados['nascimento']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="genero">Gênero</label>
                    <select id="genero" name="genero" required>
                        <option value="" <?php echo ($dados['genero'] === '') ? 'selected' : ''; ?>>Selecione seu gênero</option>
                        <?php foreach ($generos as $genero): ?>
                            <option value="<?php echo htmlspecialchars($genero['id_genero']); ?>" <?php echo ($dados['genero'] == $genero['id_genero']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($genero['nm_genero']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label for="telefone">Telefone</label>
                     <input type="text" id="telefone" name="telefone" readonly value="<?php echo htmlspecialchars($dados['telefone']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="email">E-mail</label>
                    <input id="email" name="email" type="email" maxlength="40" placeholder="matchfight@gmail.com" required>
                </div>

                <div class="form-row">
                    <label for="senha">Senha</label>
                    <input id="senha" name="senha" type="password" maxlength="40" required>
                </div>

                <div class="form-row">
                    <label for="senha2">Confirme a senha</label>
                    <input id="senha2" name="confirmar" type="password" maxlength="40" required>
                </div>

                <div class="checkbox-row">
                    <input id="termos" name="termos" type="checkbox" required>
                    <label for="termos">Concordo com os termos e condições</label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="matricule-btn">Enviar inscrição</button>
                </div>
            </form>
            </div>
        </div>
    </main>

    <script>
        // Intercepta o envio do formulário para confirmar pagamento
        document.querySelector('.matricula-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o envio imediato do formulário
            
            if (confirm('O pagamento já foi efetuado?')) {
                // Se confirmar que pagou, envia o formulário
                this.submit();
            }
            // Se não confirmou, nada acontece e o formulário não é enviado
        });

        // Função para permitir apenas numero no telefone
        function apenasNumeros(event) {
            const charCode = event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        // Validação do domínio do email (mesma lista usada nas outras páginas)
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value.trim().toLowerCase();
            const allowedDomains = [
                '@gmail.com',
                '@outlook.com',
                '@hotmail.com',
                '@yahoo.com',
                '@icloud.com',
                '@aol.com'
            ];
            const isValid = allowedDomains.some(domain => email.endsWith(domain));
            if (email && !isValid) {
                alert('O email registrado não é compatível.\nUse apenas:\n gmail.com, outlook.com, hotmail.com, yahoo.com, icloud.com ou aol.com');
                this.value = '';
                this.focus();
            }
        });

        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
            }
            if (value.length > 10) {
                value = value.substring(0, 10) + '-' + value.substring(10, 15);
            }
            e.target.value = value;
        });

    </script>
    <script src="../js/nav.js"></script>
</body>
</html>