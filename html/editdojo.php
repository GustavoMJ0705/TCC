<?php
session_start();
require_once 'lista_horarios.php';
 require_once '../php/infodojo.php'; 
 
 ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título dinâmico -->
    <title><?php echo htmlspecialchars($academia['nm_academia']); ?></title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/editdojo.css">
    <script src="../js/nav.js"></script>
</head>
<body>

    <!--Inicio da navbar-->
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

            <?php include __DIR__ . '/inc_profile_img.php'; ?>
        </nav>
        
        <aside class="sidebar" id="sidebar">
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
            <ul>        
                <li><a href="home.php">Home</a></li>              
                <li><a href="suporte_tecnico.php">Suporte técnico</a></li>
                <li><a href="seja_parceiro.php">Seja um parceiro</a></li>
                <?php if (isset($_SESSION['academia_id']) || isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <li><a href="../php/logout.php" id="logout-link">Sair</a></li>
               
                
                <?php endif; ?>
            </ul>
        </aside>
    </header><!--Fim da navbar-->
    

    <main>

        <div class="Container-Carrossel">
            <div class="titulo">
   
                <!-- Nome dinâmico -->
                <h1><?php echo htmlspecialchars($academia['nm_academia']); ?></h1>
            </div>
            <!-- Imagem principal do carrossel -->
            <img id="main-image" src="<?php echo !empty($imagens) ? htmlspecialchars($imagens[0]['url_imagem']) : '../img/imgDojoTeste.jpg'; ?>" alt="Imagem principal da academia">
            
                    <!--Fotos de carrossel-->
            <div class="carrossel">
                
                <button class="buttonleft"><img src="../img/voltar.png" alt=""></button>

                <?php if (!empty($imagens)): ?>
                    <?php foreach ($imagens as $index => $imagem): ?>
                        <img class="minimg" src="<?php echo htmlspecialchars($imagem['url_imagem']); ?>" 
                             <?php echo $index > 0 ? 'filter: brightness(60%);' : ''; ?>
                             alt="Imagem da academia <?php echo $index + 1; ?>">
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Imagem de fallback se não houver imagens no banco -->
                    <img class="minimg" src="../img/imgDojoTeste.jpg" style="margin-left: 2vh; margin-top: 3vh; width: 5.5rem; height: 4.5rem; border-radius: 8px;" alt="">
                <?php endif; ?>

                <button class="buttonright"><img src="../img/avancar.png" alt=""></button><br>


            </div>
            <div class="entrardojo" style="display: flex; align-items: center; justify-content: center; margin-top: 2vh; ">
               <div class="btn-matricula">
                    <a href="vermatricula.php?id=<?php echo urlencode($id_academia); ?>" title="Matricular-se">
                        <button class="matricule-btn" aria-label="Matricule-se">Ver Matriculas</button>
                    </a>
                </div>
            </div>

        </div>
  <!--Descrição da academia com botão ao lado-->
            <div class="descricao-e-matricula">
                <div class="containerDescricao">
                    <div class="DescriçãoDojo">
                        <h3>Descrição</h3>
                        <!-- Descrição dinâmica -->
                        <p>
                            <?php echo nl2br(htmlspecialchars($academia['ds_descricao'])); ?>
                        </p>
                    </div>
                </div>

                
            </div>
        <!--Cards de informações-->
        <div class="cards-container">
            
            <!-- Card de Horários -->
           <?php 
           exibirHorariosPorDia($grouped, $daysOrder);
           ?>

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
                        <?php $endereco = $academia['nr_cep'] . ' ' . $academia['ds_rua'] . ', ' . $academia['nr_numero_endereco'] . ' - ' . $academia['ds_bairro'] . ', ' . $academia['ds_cidade'] . ' - ' . $academia['ds_estado'];
                                $endereco_url = urlencode($endereco);
                                $iframe_src = "https://www.google.com/maps?q=$endereco_url&output=embed";?>
                        <iframe class="mapa" src="<?php echo $iframe_src; ?>" allowfullscreen="" loading="lazy"></iframe>
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