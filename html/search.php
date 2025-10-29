<?php
    session_start();
    require_once 'Listasearch.php';

    // receber parâmetro de ordenação da query string
    $order = isset($_GET['order']) ? $_GET['order'] : 'relevance';
    $academias = getTodasAcademias($order);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchFight - Academias</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/search.css">
    <script src="../js/nav.js"></script>
</head>
<body>

    <!--todo o conteúdo da navbar-->
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

        <!--botão de cadastro, login, ver perfil, etc...-->
        <div class="Perfil">
                <?php if (isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                <?php include __DIR__ . '/inc_profile_img.php'; ?>
                <?php elseif (isset($_SESSION['academia_id'])):?>
                <?php else: ?>
                    <a href="contas.html" id="login-link" class="lbottom">Cadastrar</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['academia_id'])): ?>
                    <a href="criardojo.php" class="lbottom">Criar Academia</a>
                <?php endif; ?>
        </div>       
        </nav>

                <!--links que estão dentro da side bar-->
        
        <aside class="sidebar" id="sidebar">
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
            <ul>                  
                <li><a href="suporte_tecnico.php">Suporte técnico</a></li>
                <li><a href="seja_parceiro.php">Seja um parceiro</a></li>
                <li><a href="#">Calendário de aulas</a></li>
                <?php if (isset($_SESSION['academia_id']) || isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <li><a href="../php/logout.php" id="logout-link">Sair</a></li>
               
                
                <?php endif; ?>
            </ul>
        </aside>
    </header>
    <!--fim todo o conteúdo da navbar-->

    <div class="barra-pesquisa">
        <input type="text" class="pesquisa" id="pesquisa" placeholder="Encontre o centro de treinamento desejado">
        <button class="botao-lupa" id="botao-lupa">
            <img src="../img/lupa.png" alt="Lupa">
        </button>
    </div>


    <!--main que comporta todo o conteúdo abaixo da barra de pesquisa-->
    <main>
        <!--div container de todo o conteúdo do filtro-->
        <div class="filtro"> 
            <h2>Filtrar por:</h2>
            <hr>

            <!--div que comporta a seção de filtragem modalidades-->
            <div>
                <!--título do filtro, para identificar ele e com um botão que faz a div abrir e mostrar o conteúdo interno-->
                <div class="tituloFiltro"  onclick="toggleFiltro('modalidades')">
                    <span><strong>Modalidades</strong></span>
                    <span class="seta">⌄</span>
                </div>

                <div class="conteudo-filtro" id="modalidades">
                    <label><input type="radio" name="modalidade"> Karate</label>
                    <label><input type="radio" name="modalidade"> Boxe</label>
                    <label><input type="radio" name="modalidade"> Capoeira</label>
                    <label><input type="radio" name="modalidade"> Caratê</label>
                    <label><input type="radio" name="modalidade"> Jiu-jitsu</label>
                    <label><input type="radio" name="modalidade"> Judô</label>
                    <label><input type="radio" name="modalidade"> Kickboxing</label>
                    <label><input type="radio" name="modalidade"> Muay Thai</label>
                </div>
            </div>

            <hr>

            <!--div que comporta a seção de filtragem modalidades-->
            <div class="seçoes">
                <!--título do filtro, para identificar ele e com um botão que faz a div abrir e mostrar o conteúdo interno-->
                <div class="tituloFiltro" onclick="toggleFiltro('localidade')">
                    <span><strong>Localidade</strong></span>
                    <span class="seta">⌄</span>
                </div>
                
                <!--div que comporta todo o conteúdo da filtragem localidade-->
                <div class="conteudo-filtro" id="localidade">
                <!--select de escolha de estado, os seus values são essenciais para que o datalist cidades consiga identificar as cidades de cada estado-->
                    <label for="estado" >Estado:</label><br>
                    <select id="estado" name="estado">
                        <option value="" selected disable>Selecione o estado</option>
                        <option value="AC">Acre (AC)</option>
                        <option value="AL">Alagoas (AL)</option>
                        <option value="AP">Amapá (AP)</option>
                        <option value="AM">Amazonas (AM)</option>
                        <option value="BA">Bahia (BA)</option>
                        <option value="CE">Ceará (CE)</option>
                        <option value="DF">Distrito Federal (DF)</option>
                        <option value="ES">Espírito Santo (ES)</option>
                        <option value="GO">Goiás (GO)</option>
                        <option value="MA">Maranhão (MA)</option>
                        <option value="MT">Mato Grosso (MT)</option>
                        <option value="MS">Mato Grosso do Sul (MS)</option>
                        <option value="MG">Minas Gerais (MG)</option>
                        <option value="PA">Pará (PA)</option>
                        <option value="PB">Paraíba (PB)</option>
                        <option value="PR">Paraná (PR)</option>
                        <option value="PE">Pernambuco (PE)</option>
                        <option value="PI">Piauí (PI)</option>
                        <option value="RJ">Rio de Janeiro (RJ)</option>
                        <option value="RN">Rio Grande do Norte (RN)</option>
                        <option value="RS">Rio Grande do Sul (RS)</option>
                        <option value="RO">Rondônia (RO)</option>
                        <option value="RR">Roraima (RR)</option>
                        <option value="SC">Santa Catarina (SC)</option>
                        <option value="SP">São Paulo (SP)</option>
                        <option value="SE">Sergipe (SE)</option>
                        <option value="TO">Tocantins (TO)</option>
                    </select><br>

                    <!--input e datalist que comporta as cidades que vieram de uma API-->
                    <label for="cidade">Cidade:</label><br>
                    <input list="lista-cidades" id="cidade" placeholder="Digite a cidade..." />
                    <datalist id="lista-cidades"></datalist>
                </div>
            </div>
            
                <!--Botão filtrar, que finaliza o processo de filtragem e pede para o banco mostrar as academias-->
            <button class="botao-filtrar">Filtrar</button>
        </div>

        <!--div container que comporta todo o conteúdo dos dojos presentes no banco de dados-->
        <div class="academias-container">
            <!-- seletor de ordenação -->
            <form id="orderForm" method="get" class="order-form">
                <label for="orderSelect">Ordenar por:</label>
                <select name="order" id="orderSelect" onchange="document.getElementById('orderForm').submit()">
                    <option value="relevance" <?php echo (isset($order) && $order === 'relevance') ? 'selected' : ''; ?>>Relevância</option>
                    <option value="alpha_asc" <?php echo (isset($order) && $order === 'alpha_asc') ? 'selected' : ''; ?>>Ordem alfabética A - Z</option>
                    <option value="alpha_desc" <?php echo (isset($order) && $order === 'alpha_desc') ? 'selected' : ''; ?>>Ordem alfabética Z - A</option>
                    <option value="recent" <?php echo (isset($order) && $order === 'recent') ? 'selected' : ''; ?>>Mais recentes</option>
                </select>
            </form>

            <div class="academiasSearch">
                <?php
                    // exibindo academias usando função centralizada
                    exibirAcademias($academias);
                ?>
            </div>
        </div>
    </main>

    <script>
    function toggleFiltro(id) {
      const conteudo = document.getElementById(id);
      const titulo = conteudo.previousElementSibling;
      conteudo.style.display = conteudo.style.display === "flex" ? "none" : "flex";
      titulo.classList.toggle("ativo");
    }

    const estadoSelect = document.getElementById('estado');
    const cidadeInput = document.getElementById('cidade');
    const listaCidades = document.getElementById('lista-cidades');
    let cidades = [];

    estadoSelect.addEventListener('change', async () => {
      const uf = estadoSelect.value;
      cidades = [];
      listaCidades.innerHTML = '';
      cidadeInput.value = '';
      listaCidades.style.display = 'none';

      if (!uf) return;

      cidadeInput.placeholder = 'Carregando cidades...';

      try {
        const response = await fetch(`https://brasilapi.com.br/api/ibge/municipios/v1/${uf}`);
        cidades = await response.json();
        cidadeInput.placeholder = 'Digite a cidade...';
      } catch (error) {
        cidadeInput.placeholder = 'Erro ao carregar cidades';
        console.error(error);
      }
    });

    cidadeInput.addEventListener('input', () => {
      const texto = cidadeInput.value.toLowerCase();
      const filtradas = cidades.filter(c => c.nome.toLowerCase().includes(texto));
      listaCidades.innerHTML = filtradas
        .map(c => `<div>${c.nome}</div>`)
        .join('');
      listaCidades.style.display = filtradas.length ? 'block' : 'none';
    });

    listaCidades.addEventListener('click', e => {
      if (e.target.tagName === 'DIV') {
        cidadeInput.value = e.target.textContent;
        listaCidades.style.display = 'none';
      }
    });

    document.addEventListener('click', e => {
      if (!listaCidades.contains(e.target) && e.target !== cidadeInput) {
        listaCidades.style.display = 'none';
      }
    });
  </script>

</body>
</html>