<?php
session_start();
if (!isset($_SESSION['academia_id']) && !isset($_SESSION['academia_id'])) {
    header("Location: ../html/contas.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edite a página de seu dojo!</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/editdojo.css">
    <script src="../js/nav.js"></script>
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
                    <a href="mperfil.php" class="lbottom_AlunoProf"><img src="../img/Perfil.png" alt=""></a>
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
        <div class="form-container">
            <h1>Editar a Academia</h1>
            <form id="dojoForm" class="dojo-form">
                <div class="form-group">
                    <label for="dojoName">Nome da Academia:</label>
                    <input type="text" id="dojoName" name="dojoName" minlength="2" maxlength="100" required>
                </div>

                <div class="form-group">
                    <label for="dojoDescription">Descrição:</label>
                    <textarea id="dojoDescription" name="dojoDescription" rows="4" minlength="10" maxlength="800" required></textarea>
                </div>

                <div class="form-group">
                    <label for="dojoCEP">CEP:</label>
                    <input type="text" id="dojoCEP" name="dojoCEP" placeholder="00000-000" maxlength="9" required>
                </div>

                <div class="form-group">
                 <label for="dojoImage">Imagens da Academia:</label>
                    <input type="file" id="dojoImage" name="dojoImage" accept="image/*" multiple onchange="previewDojoImage(event)">
                    <div id="dojoImagePreviewContainer" style="margin-top:10px; display: flex; gap: 10px;"></div>
                </div>

             

                <div class="form-group">
                    <label for="dojoPhone">Telefone:</label>
                    <input type="tel" id="dojoPhone" name="dojoPhone" onkeydown="return apenasNumeros(event)" minlength="8" maxlength="9" required>
                </div>


                <div class="form-group">
                    <label for="dojoEmail">Email:</label>
                    <input type="email" id="dojoEmail" name="dojoEmail" minlength="10" maxlength="70">
                </div>
                

                <div class="form-group">
                    <label>Horários:</label>
                    <div class="form-group">
                        <select id="dayOfWeekSelect" required>
                            <option value="">Selecione o dia</option>
                            <option value="Segunda-feira">Segunda-feira</option>
                            <option value="Terça-feira">Terça-feira</option>
                            <option value="Quarta-feira">Quarta-feira</option>
                            <option value="Quinta-feira">Quinta-feira</option>
                            <option value="Sexta-feira">Sexta-feira</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                    </div>
                    <div id="horariosDiaContainer"></div>
                    <button type="button" id="addHorarioBtn">Adicionar Horário</button>
                    <button type="button" id="mostrarHorariosBtn">Mostrar Todos os Horários</button>
                    <div id="todosHorarios" style="margin-top:10px;"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Atualizar dados</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Preview das imagens selecionadas
        function previewDojoImage(event) {
            const container = document.getElementById('dojoImagePreviewContainer');
            container.innerHTML = '';
            const files = event.target.files;
            if (files) {
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.maxWidth = '120px';
                            img.style.maxHeight = '120px';
                            img.style.borderRadius = '8px';
                            img.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
                            container.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }
        
    </script>
</body>
</html>