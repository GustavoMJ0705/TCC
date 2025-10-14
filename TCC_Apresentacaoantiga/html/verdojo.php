<?php require_once '../php/infodojo.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título dinâmico -->
    <title><?php echo htmlspecialchars($academia['nm_academia']); ?></title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/verdojo.css">
    <script src="../js/nav.js"></script>
</head>
<body>

    <!--Inicio da navbar-->
    <header>
        <nav class="navbar">
            <div class="menu-icon" onclick="toggleSidebar()">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Pesquisar academias...">
            </div>
            <div class="Perfil">
               <?php if (isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <a href="mperfil.php" class="lbottom">Meu Perfil</a>
                <?php elseif (!isset($_SESSION['academia_id']) ):    ?>
                    <a href="contas.html" id="login-link" class="lbottom">Login</a>
                    
                <?php endif; ?>
                <?php if (isset($_SESSION['academia_id'])): ?>
                    <a href="criardojo.php" class="lbottom">Criar Academia</a>
                <?php endif; ?>
            </div>
           
        </nav>
        <!--Inicio da barra de pesquisa-->
        <aside class="sidebar" id="sidebar">
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
            <ul>
                
                <li><a href="home.php">Pagina Inicial</a></li>
                <?php if (isset($_SESSION['academia_id'])): ?>
                    <li><a href="../html/criardojo.php">Criar Academia</a></li>

                <?php endif; ?>
                <li><a href="#">Suporte técnico</a></li>
                <li><a href="#">Seja um parceiro</a></li>
                <li><a href="#">Calendário de aulas</a></li>
                <?php if (isset($_SESSION['academia_id']) || isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <li><a href="../php/logout.php" id="logout-link">Sair</a></li>
               
                
                <?php endif; ?>
            </ul>
        </aside>
    </header> <!--Fim da navbar-->
    

    <main>

        <div class="Container-Carrossel">
            <div class="titulo">
   
                <!-- Nome dinâmico -->
                <h1><?php echo htmlspecialchars($academia['nm_academia']); ?></h1>
            </div>
            <!-- Imagem principal do carrossel -->
            <img id="main-image" src="<?php echo !empty($imagens) ? htmlspecialchars($imagens[0]['url_imagem']) : '../img/imgDojoTeste.jpg'; ?>" style="border-radius: 10px;" alt="Imagem principal da academia">
            
                    <!--Fotos de carrossel-->
            <div class="carrossel">
                
                <button class="buttonleft"><img src="../img/voltar.png" alt=""></button>

                <?php if (!empty($imagens)): ?>
                    <?php foreach ($imagens as $index => $imagem): ?>
                        <img class="minimg" src="<?php echo htmlspecialchars($imagem['url_imagem']); ?>" 
                             style="margin-left: 2vh; margin-top: 3vh; width: 5.5rem; height: 4.5rem; border-radius: 8px; cursor: pointer; <?php echo $index > 0 ? 'filter: brightness(60%);' : ''; ?>" 
                             alt="Imagem da academia <?php echo $index + 1; ?>">
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Imagem de fallback se não houver imagens no banco -->
                    <img class="minimg" src="../img/imgDojoTeste.jpg" style="margin-left: 2vh; margin-top: 3vh; width: 5.5rem; height: 4.5rem; border-radius: 8px;" alt="">
                <?php endif; ?>

                <button class="buttonright"><img src="../img/avancar.png" alt=""></button><br>


            </div>
            <div class="entrardojo" style="display: flex; align-items: center; justify-content: center; margin-top: 2vh; ">
                <button class="entrar-button"><strong>Quero me cadastrar!</strong></button>
            </div>

        </div>
  <!--Descrição da academia-->
            <div class="containerDescricao">
                <div class="DescriçãoDojo">
                    <h3>Descrição</h3>
                    <!-- Descrição dinâmica -->
                    <p>
                        <?php echo nl2br(htmlspecialchars($academia['ds_descricao'])); ?>
                    </p>
                </div>
            </div>
        <!--Cards de informações-->
        <div class="cards-container">
            
            <!-- Card de Horários -->
            <div class="card-wrapper">
                <div class="infoHorarios">
                    <img src="../img/relogio.png" alt="Relógio" />
                    <h3>Horários da Semana</h3>
                </div>
                <div class="semana-horarios">
                    <div class="dia-coluna">
                        <div class="dia-nome">Segunda</div>
                        <div class="horario-item">00:00 - 00:00</div>
                        <div class="horario-item">00:00 - 00:00</div>
                    </div>
                    <div class="dia-coluna">
                        <div class="dia-nome">Terça</div>
                        <div class="horario-item">00:00 - 00:00</div>
                        <div class="horario-item">00:00 - 00:00</div>
                    </div>
                    <div class="dia-coluna">
                        <div class="dia-nome">Quarta</div>                        
                        <div class="horario-item">00:00 - 00:00</div>
                        <div class="horario-item">00:00 - 00:00</div>
                    </div>
                    <div class="dia-coluna">
                        <div class="dia-nome">Quinta</div>
                        <div class="horario-item">00:00 - 00:00</div>
                        <div class="horario-item">00:00 - 00:00</div>
                    </div>
                    <div class="dia-coluna">
                        <div class="dia-nome">Sexta</div>
                        <div class="horario-item">00:00 - 00:00</div>
                        <div class="horario-item">00:00 - 00:00</div>
                    </div>
                    <div class="dia-coluna">
                        <div class="dia-nome">Sábado</div>
                        <div class="horario-item">00:00 - 00:00</div>
                        <div class="horario-item">00:00 - 00:00</div>
                    </div>
                    <div class="dia-coluna">
                        <div class="dia-nome">Domingo</div>
                        <div class="horario-item">Fechado</div>
                    </div>
                </div>
            </div>

            <!-- Card de Endereço -->
            <div class="card-wrapper">
                <div class="infoEndereco">
                    <img src="../img/local.png" alt="Endereço" />
                    <h3>Endereço</h3>
                </div>
                <div class="info-content">
                    <div class="card">
                        <div class="enderecoNome">
                            <!-- Endereço dinâmico -->
                            <p>
                                <?php 
                                    echo htmlspecialchars($academia['ds_rua']) . ', ' . htmlspecialchars($academia['nr_numero_endereco']) .
                                         ' - ' . htmlspecialchars($academia['ds_bairro']) . ', ' .
                                         htmlspecialchars($academia['ds_cidade']) . ' - ' . htmlspecialchars($academia['ds_estado']);
                                ?>
                            </p>
                        </div>
                        <iframe class="mapa" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3656.796430547949!2d-46.66232538502156!3d-23.58009028466925!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce59d25a1b1a01%3A0x2f3cfb7acb7b8b71!2sParque%20Ibirapuera!5e0!3m2!1spt-BR!2sbr!4v1695568891234" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('main-image');
            const thumbnails = document.querySelectorAll('.minimg');

            thumbnails.forEach((thumbnail, index) => {
                thumbnail.addEventListener('click', function() {
                    // Atualiza a imagem principal
                    mainImage.src = this.src;

                    // Remove o brilho de todas as miniaturas
                    thumbnails.forEach(t => t.style.filter = 'brightness(60%)');
                    
                    // Adiciona brilho à miniatura clicada
                    this.style.filter = 'brightness(100%)';
                });

                // Garante que a primeira miniatura não tenha filtro de brilho no início
                if (index === 0) {
                    thumbnail.style.filter = 'brightness(100%)';
                }
            });
        });
    </script>
</body>
</html>