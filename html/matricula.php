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
   $erroSenha = '';
if (isset($_GET['erro'])) {
    $erroSenha = $_GET['erro'];
}

$erroEmail = '';
if (isset($_GET['erroemail'])) {
    $erroEmail = $_GET['erroemail'];
}

try {
    // Buscar gêneros
    $sql = "SELECT id_genero, nm_genero FROM tb_genero";
    $result = $pdo->query($sql);
    $generos = $result->fetchAll(PDO::FETCH_ASSOC);

    // Buscar modalidades disponíveis através das aulas oferecidas pelo perfil da academia
    $perfil_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $sql_modalidades = "SELECT DISTINCT m.id_modalidade, m.nm_modalidade 
                       FROM tb_modalidade m 
                       INNER JOIN aula_modalidade am ON m.id_modalidade = am.id_modalidade
                       INNER JOIN tb_aulas a ON am.id_aulas = a.id_aulas
                       INNER JOIN tb_academia_aulas aa ON a.id_aulas = aa.id_aulas
                       INNER JOIN tb_perfil_academia pa ON aa.id_perfil_academia = pa.id_perfil_academia
                       WHERE pa.id_perfil_academia = :perfil_id";
    $stmt_modalidades = $pdo->prepare($sql_modalidades);
    $stmt_modalidades->execute([':perfil_id' => $perfil_id]);
    $modalidades = $stmt_modalidades->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ou consultar: " . $e->getMessage());
}

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

            <?php $perfil_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : ''; ?>

            <form action="../php/matricula_usuario.php" method="post" autocomplete="on" class="matricula-form">
                <input type="hidden" name="perfil_id" value="<?php echo htmlspecialchars($perfil_id); ?>">
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
                        <option value="" selected>Selecione seu gênero</option>
            <?php foreach ($generos as $genero): ?>
                <option value="<?php echo htmlspecialchars($genero['id_genero']); ?>">
                    <?php echo htmlspecialchars($genero['nm_genero']); ?>
                </option>
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
                    <label for="modalidade">Modalidade</label>
                    <select id="modalidade" name="modalidade"  required onchange="carregarHorarios()">
                        <option value="" selected>Selecione a modalidade</option>
                        <?php foreach ($modalidades as $modalidade): ?>
                            <option value="<?php echo htmlspecialchars($modalidade['id_modalidade']); ?>">
                                <?php echo htmlspecialchars($modalidade['nm_modalidade']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label>Horários Disponíveis</label>
                    <div id="horarios-container">
                        <!-- Os horários serão carregados aqui via AJAX -->
                    </div>
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
        function carregarHorarios() {
            const modalidadeId = document.getElementById('modalidade').value;
            const perfilId = <?php echo $perfil_id; ?>;
            const horariosContainer = document.getElementById('horarios-container');
            
            console.log('Carregando horários para:', {
                modalidadeId: modalidadeId,
                perfilId: perfilId
            });
            
            if (!modalidadeId) {
                horariosContainer.innerHTML = '';
                return;
            }

            // Fazer requisição AJAX para buscar horários (usando id do perfil da academia)
            fetch(`../php/get_horarios.php?perfil_id=${perfilId}&modalidade_id=${modalidadeId}`)
                .then(response => {
                    console.log('Resposta recebida:', response);
                    return response.json();
                })
                .then(horarios => {
                    console.log('Horários recebidos:', horarios);
                    let html = '';
                    if (horarios.length === 0) {
                        html = '<p>Nenhum horário disponível para esta modalidade.</p>';
                    } else {
                        // Agrupar horários por dia da semana
                        const horariosPorDia = {};
                        horarios.forEach(horario => {
                            if (!horariosPorDia[horario.dia_semana]) {
                                horariosPorDia[horario.dia_semana] = [];
                            }
                            horariosPorDia[horario.dia_semana].push(horario);
                        });

                        // Criar HTML para cada dia e seus horários
                        for (const dia in horariosPorDia) {
                            html += `<div class="dia-horarios"><h4>${dia}</h4>`;
                            horariosPorDia[dia].forEach(horario => {
                                html += `
                                    <div class="horario-item">
                                        <input type="checkbox" 
                                               name="horarios_aula[]" 
                                               id="horario_${horario.id_aulas}" 
                                               value="${horario.id_aulas}"
                                               class="horario-checkbox">
                                        <label for="horario_${horario.id_aulas}">
                                            ${horario.nome_aula} - 
                                            ${horario.horario_inicio.substring(0, 5)} às 
                                            ${horario.horario_fim.substring(0, 5)}
                                        </label>
                                    </div>`;
                            });
                            html += '</div>';
                        }
                    }
                    horariosContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Erro ao carregar horários:', error);
                    horariosContainer.innerHTML = '<p>Erro ao carregar horários. Por favor, tente novamente.</p>';
                });
        }

        // Intercepta o envio do formulário para validar e confirmar pagamento
        document.querySelector('.matricula-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o envio imediato do formulário
            
            // Verifica se pelo menos um horário foi selecionado
            const horariosSelecionados = document.querySelectorAll('input[name="horarios_aula[]"]:checked');
            if (horariosSelecionados.length === 0) {
                alert('Por favor, selecione pelo menos um horário de aula.');
                return;
            }
            
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