<?php
require_once __DIR__ . '/../php/db_connect.php';

session_start();
if (!isset($_SESSION['academia_id']) && !isset($_SESSION['academia_id'])) {
    header("Location: ../html/contas.html");
    exit();
}



try {
   

    $sql = "SELECT id_modalidade, nm_modalidade FROM tb_modalidade";
    $result = $pdo->query($sql);

    $modalidade = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ou consultar: " . $e->getMessage());
}



?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crie a página de seu dojo!</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/criardojo.css">
    <script src="../js/nav.js"></script>

</head>

<body>
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
                <?php elseif (isset($_SESSION['academia_id'])):    ?>
                <?php else: ?>
                    <a href="contas.html" id="login-link" class="lbottom">Login</a>
            </div>

        <?php endif; ?>
        </div>
        </nav>

        <aside class="sidebar" id="sidebar">
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
            <ul>
                <li><a href="home.php">Pagina Inicial</a></li>
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
            <h1>Criar Nova Academia</h1>
            <form id="dojoForm" action="../php/Criacao_dojo.php" method="post" enctype="multipart/form-data" class="dojo-form">
                <div class="form-group">
                    <label for="dojoName">Nome da Academia:</label>
                    <input type="text" id="dojoName" name="dojoName" minlength="2" maxlength="100" required>
                </div>

                <div class="form-group">
                    <label for="dojoDescription">Descrição:</label>
                    <textarea id="dojoDescription" name="dojoDescription" rows="4" style="resize: none;" required></textarea>
                </div>
                <div class="form-group">
                    <label for="dojoPhone">Telefone:</label>
                    <input type="tel" id="dojoPhone" name="dojoPhone" onkeydown="return apenasNumeros(event)" minlength="8" maxlength="11" required>
                </div>

                <div class="form-group">
                    <label for="dojoPhone">Email:</label>
                    <input type="text" id="dojoEmail" name="dojoEmail" required>
                </div>
                <div class="form-group">
                    <label for="dojoCEP">CEP:</label>
                    <input type="text" id="dojoCEP" name="dojoCEP" placeholder="00000-000" maxlength="9" required>
                </div>
                <div class="form-group">
                    <label for="rua">Rua:</label>
                    <input type="text" id="rua" name="rua" maxlength="40" required>
                </div>
                <div class="form-group">
                    <label for="numero">Número:</label>
                    <input type="text" id="numero" name="numero" maxlength="12" required>
                </div>
                <div class="form-group">
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" maxlength="40" required>
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" maxlength="40" required>
                </div>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" maxlength="40" required>
                </div>

                <div class="form-group">
                    <label for="dojoImage">Imagens da Academia:</label>
                    <input type="file" id="dojoImage" name="dojoImages[]" accept="image/*" multiple onchange="previewDojoImage(event)">
                    <div id="dojoImagePreviewContainer" style="margin-top:10px; display: flex; gap: 10px;"></div>
                </div>







                <div class="form-actions">
                    <button type="button" class="btn-agenda" id="btn-agenda">Criar Agenda</button>
                    <button type="submit" class="btn-primary">Criar Academia</button>
                </div>
                <div id="Agenda">
                    <div class="Agenda">
                        <div class="calendar">

                            <!-- Domingo -->
                            <div class="day">
                                <h3>Domingo</h3>
                                <div class="Aula" id="Aula-domingo">
                                    <fieldset id="fieldset-domingo" disabled>
                                        <label for="aulaNome-domingo">Nome da Aula:</label>
                                        <input type="text" id="aulaNome-domingo" name="aulaNome[]" minlength="2" maxlength="100" data-required-on-active="true">
                                        <label>Selecione a modalidade da aula</label>
                                        <select id="aulaTipo-domingo" name="aulaTipo[]" class="select-modalidade" data-required-on-active="true">
                                            <option value="">Selecione</option>
                                            <?php foreach ($modalidade as $mod): ?>
                                                <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                                    <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label>Inicio da aula</label> <input type="time" id="aulaTime-domingo" name="aulaTime[]" data-required-on-active="true">
                                        <label>Fim da aula</label><input type="time" id="aulaTimefim-domingo" name="aulaTimefim[]" data-required-on-active="true">
                                                 </fieldset>
                                </div>
                       
                                <ul class="lista-resumos"></ul>
                                <button type="button" class="btn-adicionar" id="btn-adicionar-domingo">Adicionar aula</button>
                                <div class="btn-actions" id="btn-actions-domingo" style="display: none; margin-top: 10px;">

                                    <button type="button" class="btn-salvar">Salvar</button>
                                    <button type="button" class="btn-excluir">Excluir</button>

                                </div>

                            </div>

                            <!-- Segunda -->
                            <div class="day">
                                <h3>Segunda</h3>
                                <div class="Aula" id="Aula-segunda">
                                    <fieldset id="fieldset-segunda" disabled>
                                        <label for="aulaNome-segunda">Nome da Aula:</label>
                                        <input type="text" id="aulaNome-segunda" name="aulaNome[]" minlength="2" maxlength="100" data-required-on-active="true">
                                        <label>Selecione a modalidade da aula</label>
                                        <select id="aulaTipo-segunda" name="aulaTipo[]" class="select-modalidade" data-required-on-active="true">
                                            <option value="">Selecione</option>
                                            <?php foreach ($modalidade as $mod): ?>
                                                <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                                    <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label>Inicio da aula</label> <input type="time" id="aulaTime-segunda" name="aulaTime[]" data-required-on-active="true">
                                        <label>Fim da aula</label><input type="time" id="aulaTimefim-segunda" name="aulaTimefim[]" data-required-on-active="true">
                                                    </fieldset>
                                </div>
                    
                                <ul class="lista-resumos"></ul>
                                <button type="button" class="btn-adicionar" id="btn-adicionar-segunda">Adicionar aula</button>
                                <div class="btn-actions" id="btn-actions-segunda" style="display: none; margin-top: 10px;">

                                    <button type="button" class="btn-salvar">Salvar</button>
                                    <button type="button" class="btn-excluir">Excluir</button>

                                </div>

                            </div>

                            <!-- Terça -->
                            <div class="day">
                                <h3>Terça</h3>
                                <div class="Aula" id="Aula-terca">
                                    <fieldset id="fieldset-terca" disabled>
                                        <label for="aulaNome-terca">Nome da Aula:</label>
                                        <input type="text" id="aulaNome-terca" name="aulaNome[]" minlength="2" maxlength="100"data-required-on-active="true">
                                        <label>Selecione</label>
                                        <select id="aulaTipo-terca" name="aulaTipo[]" class="select-modalidade" data-required-on-active="true">
                                            <option value="">Selecione</option>
                                            <?php foreach ($modalidade as $mod): ?>
                                                <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                                    <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label>Inicio da aula</label> <input type="time" id="aulaTime-terca" name="aulaTime[]" data-required-on-active="true">
                                        <label>Fim da aula</label><input type="time" id="aulaTimefim-terca" name="aulaTimefim[]" data-required-on-active="true">
                                           </fieldset>
                                </div>
                             
                                <ul class="lista-resumos"></ul>
                                <button type="button" class="btn-adicionar" id="btn-adicionar-terca">Adicionar aula</button>
                                <div class="btn-actions" id="btn-actions-terca" style="display: none; margin-top: 10px;">
                                    <button type="button" class="btn-salvar">Salvar</button>
                                    <button type="button" class="btn-excluir">Excluir</button>

                                </div>

                            </div>

                            <!-- Quarta -->
                            <div class="day">
                                <h3>Quarta</h3>
                                <div class="Aula" id="Aula-quarta">
                                    <fieldset id="fieldset-quarta" disabled>
                                        <label for="aulaNome-quarta">Nome da Aula:</label>
                                        <input type="text" id="aulaNome-quarta" name="aulaNome[]" minlength="2" maxlength="100" data-required-on-active="true">
                                        <label>Selecione</label>
                                        <select id="aulaTipo-quarta" name="aulaTipo[]"  class="select-modalidade" data-required-on-active="true">
                                            <option value="">Selecione</option>
                                            <?php foreach ($modalidade as $mod): ?>
                                                <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                                    <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label>Inicio da aula</label> <input type="time" id="aulaTime-quarta" name="aulaTime[]" data-required-on-active="true">
                                        <label>Fim da aula</label><input type="time" id="aulaTimefim-quarta" name="aulaTimefim[]" data-required-on-active="true">
                                                   </fieldset>
                                </div>
                     
                                <ul class="lista-resumos"></ul>
                                <button type="button" class="btn-adicionar" id="btn-adicionar-quarta">Adicionar aula</button>
                                <div class="btn-actions" id="btn-actions-quarta" style="display: none; margin-top: 10px;">
                                    <button type="button" class="btn-salvar">Salvar</button>
                                    <button type="button" class="btn-excluir">Excluir</button>

                                </div>

                            </div>

                            <!-- Quinta -->
                            <div class="day">
                                <h3>Quinta</h3>
                                <div class="Aula" id="Aula-quinta">
                                    <fieldset id="fieldset-quinta" disabled>
                                        <label for="aulaNome-quinta">Nome da Aula:</label>
                                        <input type="text" id="aulaNome-quinta" name="aulaNome[]" minlength="2" maxlength="100"data-required-on-active="true">
                                        <label>Selecione</label>
                                        <select id="aulaTipo-quinta" name="aulaTipo[]"  class="select-modalidade" data-required-on-active="true">
                                            <option value="">Selecione</option>
                                            <?php foreach ($modalidade as $mod): ?>
                                                <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                                    <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label>Inicio da aula</label> <input type="time" id="aulaTime-quinta" name="aulaTime[]" data-required-on-active="true">
                                        <label>Fim da aula</label><input type="time" id="aulaTimefim-quinta" name="aulaTimefim[]" data-required-on-active="true">
                                              </fieldset>
                                </div>
                          
                                <ul class="lista-resumos"></ul>
                                <button type="button" class="btn-adicionar" id="btn-adicionar-quinta">Adicionar aula</button>
                                <div class="btn-actions" id="btn-actions-quinta" style="display: none; margin-top: 10px;">
                                    <button type="button" class="btn-salvar">Salvar</button>
                                    <button type="button" class="btn-excluir">Excluir</button>

                                </div>

                            </div>

                            <!-- Sexta -->
                            <div class="day">
                                <h3>Sexta</h3>
                                <div class="Aula" id="Aula-sexta">
                                    <fieldset id="fieldset-sexta" disabled>
                                        <label for="aulaNome-sexta">Nome da Aula:</label>
                                        <input type="text" id="aulaNome-sexta" name="aulaNome[]" minlength="2" maxlength="100" data-required-on-active="true">
                                        <label>Selecione</label>
                                        <select id="aulaTipo-sexta" name="aulaTipo[]" class="select-modalidade" data-required-on-active="true">
                                            <option value="">Selecione</option>
                                            <?php foreach ($modalidade as $mod): ?>
                                                <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                                    <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label>Inicio da aula</label> <input type="time" id="aulaTime-sexta" name="aulaTime[]" data-required-on-active="true">
                                        <label>Fim da aula</label><input type="time" id="aulaTimefim-sexta" name="aulaTimefim[]" data-required-on-active="true">
                                              </fieldset>
                                </div>
                          
                                <ul class="lista-resumos"></ul>
                                <button type="button" class="btn-adicionar" id="btn-adicionar-sexta">Adicionar aula</button>
                                <div class="btn-actions" id="btn-actions-sexta" style="display: none; margin-top: 10px;">
                                    <button type="button" class="btn-salvar">Salvar</button>
                                    <button type="button" class="btn-excluir">Excluir</button>
                                </div>

                            </div>

                            <!-- Sábado -->
                            <div class="day">
                                <h3>Sábado</h3>
                                <div class="Aula" id="Aula-sabado">
                                    <fieldset id="fieldset-sabado" disabled>
                                        <label for="aulaNome-sabado">Nome da Aula:</label>
                                        <input type="text" id="aulaNome-sabado" name="aulaNome[]" minlength="2" maxlength="100"data-required-on-active="true">
                                        <label>Selecione</label>
                                        <select id="aulaTipo-sabado" name="aulaTipo[]" class="select-modalidade" data-required-on-active="true">
                                            <option value="">Selecione o tipo de aula</option>
                                            <?php foreach ($modalidade as $mod): ?>
                                                <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                                    <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label>Inicio da aula</label><input type="time" id="aulaTime-sabado" name="aulaTime[]" data-required-on-active="true">
                                        <label>Fim da aula</label><input type="time" id="aulaTimefim-sabado" name="aulaTimefim[]" data-required-on-active="true">
                                          </fieldset>
                                </div>
                              
                                <ul class="lista-resumos"></ul>
                                <button type="button" class="btn-adicionar" id="btn-adicionar-sabado">Adicionar aula</button>
                                <div class="btn-actions" id="btn-actions-sabado" style="display: none; margin-top: 10px;">
                                    <button type="button" class="btn-salvar">Salvar</button>
                                    <button type="button" class="btn-excluir">Excluir</button>

                                </div>

                            </div>

                        </div>
                    </div>


                </div>
        </div>

        </form>

    </main>

    <script>

   document.addEventListener("DOMContentLoaded", () => {

  const form = document.querySelector('form.dojo-form');
  if (!form) return;

  const dias = ["domingo","segunda","terca","quarta","quinta","sexta","sabado"];
  const displayDias = ["Domingo","Segunda-feira","Terça-feira","Quarta-feira","Quinta-feira","Sexta-feira","Sabado"];

  // container oculto para inputs hidden
  let container = document.getElementById('aulas-salvas');
  if (!container) {
    container = document.createElement('div');
    container.id = 'aulas-salvas';
    container.style.display = 'none';
    form.appendChild(container);
  }

    dias.forEach(dia => {
        const aula = document.getElementById(`Aula-${dia}`);
        const fieldset = document.getElementById(`fieldset-${dia}`);
        const btnActions = document.getElementById(`btn-actions-${dia}`);
        const btnSalvar = btnActions ? btnActions.querySelector('.btn-salvar') : null;
        // CORREÇÃO: seletor do botão de adicionar estava errado (btn-salvar-...).
        const btnAdicionar = document.getElementById(`btn-adicionar-${dia}`);
        const lista = (aula && aula.parentElement.querySelector('.lista-resumos')) || null;

        if (!aula || !fieldset || !btnSalvar || !btnAdicionar || !lista) return;

        // elemento .day (usado para controle de expansão visual)
        const day = btnAdicionar.closest('.day');

        // estado inicial
        btnActions.style.display = 'none';
        fieldset.disabled = true;

        // Adicionar -> abre o editor
            btnAdicionar.addEventListener('click', () => {
                btnAdicionar.style.display = 'none';
                // usar flex para manter layout quando aplicado
                btnActions.style.display = 'flex';
                fieldset.disabled = false;
                // marca visualmente o dia como expandido (CSS usa .day.expandido)
                if (day) day.classList.add('expandido');
                // ativa required nos inputs marcados
                aula.querySelectorAll('[data-required-on-active="true"]').forEach(i => i.setAttribute('required','required'));
            });

        // Salvar -> cria hidden inputs e adiciona resumo
        btnSalvar.addEventListener('click', () => {
            // lê valores
            const nomeInput = aula.querySelector(`[id^="aulaNome"]`);
            const tipoInput = aula.querySelector(`[id^="aulaTipo"]`);
            const inicioInput = aula.querySelector(`[id^="aulaTime"]`);
            const fimInput = aula.querySelector(`[id^="aulaTimefim"]`);

            const nome = nomeInput ? nomeInput.value.trim() : '';
            const tipo = tipoInput ? tipoInput.value : '';
            const inicio = inicioInput ? inicioInput.value : '';
            const fim = fimInput ? fimInput.value : '';

            if (!nome || !tipo || !inicio || !fim) {
                alert('Preencha todos os campos da aula antes de salvar.');
                return;
            }

            // cria um id único para esse conjunto salvo
            const savedId = 'saved-' + Date.now() + '-' + Math.floor(Math.random()*1000);

            // criar hidden inputs (usar setAttribute para garantir o atributo data-saved-id)
            const hNome = document.createElement('input'); hNome.type='hidden'; hNome.name='aulaNome[]'; hNome.value=nome; hNome.setAttribute('data-saved-id', savedId);
            const hTipo = document.createElement('input'); hTipo.type='hidden'; hTipo.name='aulaTipo[]'; hTipo.value=tipo; hTipo.setAttribute('data-saved-id', savedId);
            const hInicio = document.createElement('input'); hInicio.type='hidden'; hInicio.name='aulaTime[]'; hInicio.value=inicio; hInicio.setAttribute('data-saved-id', savedId);
            const hFim = document.createElement('input'); hFim.type='hidden'; hFim.name='aulaTimefim[]'; hFim.value=fim; hFim.setAttribute('data-saved-id', savedId);

            // dia (id) e nome do dia (para dt_hora_aula caso queira armazenar o nome)
            const dayIndex = dias.indexOf(dia) + 1; // 1..7
            const hDia = document.createElement('input'); hDia.type='hidden'; hDia.name='aulaDia[]'; hDia.value=dayIndex; hDia.setAttribute('data-saved-id', savedId);
            const hDt = document.createElement('input'); hDt.type='hidden'; hDt.name='aulaDate[]'; hDt.value=displayDias[dias.indexOf(dia)]; hDt.setAttribute('data-saved-id', savedId);

            container.appendChild(hNome); container.appendChild(hTipo); container.appendChild(hInicio); container.appendChild(hFim); container.appendChild(hDia); container.appendChild(hDt);

            // adiciona item visual na lista com botão remover
            const li = document.createElement('li');
            li.className = 'resumo-item';
            li.setAttribute('data-saved-id', savedId);
            li.textContent = `${nome} — ${inicio}–${fim} — ${tipoInput.options[tipoInput.selectedIndex].text}`;

            const btnRemove = document.createElement('button');
            btnRemove.type = 'button';
            btnRemove.textContent = 'Remover';
            btnRemove.style.marginLeft = '8px';
            btnRemove.addEventListener('click', () => {
                // remove hidden inputs correspondentes (seleção pelo atributo data-saved-id)
                container.querySelectorAll(`input[data-saved-id="${savedId}"]`).forEach(n => n.remove());
                // remove li
                li.remove();
            });

            li.appendChild(btnRemove);
            lista.appendChild(li);

                    // limpa editor e fecha
            aula.querySelectorAll('input, select').forEach(i => i.value = '');
            aula.querySelectorAll('[data-required-on-active="true"]').forEach(i => i.removeAttribute('required'));
            fieldset.disabled = true;
            btnActions.style.display = 'none';
            btnAdicionar.style.display = 'inline-block';
                    if (day) day.classList.remove('expandido');
        });

        // Excluir (cancelar edição atual) -> volta ao estado inicial e limpa inputs editor
        const btnExcluir = btnActions.querySelector('.btn-excluir');
        if (btnExcluir) {
            btnExcluir.addEventListener('click', () => {
            aula.querySelectorAll('input, select').forEach(i => i.value = '');
            aula.querySelectorAll('[data-required-on-active="true"]').forEach(i => i.removeAttribute('required'));
            fieldset.disabled = true;
            btnActions.style.display = 'none';
            btnAdicionar.style.display = 'inline-block';
            if (day) day.classList.remove('expandido');
            });
        }
    });

  // Antes do submit: garantir que não existam fieldsets disabled que bloqueiem inputs já salvos.
  form.addEventListener('submit', () => {
    // hidden inputs já existem no container; apenas como segurança:
    document.querySelectorAll('fieldset').forEach(fs => {
      // se quiser garantir que nenhum fieldset disabled contenha inputs com name[] (não deveria)
      // nada a fazer, apenas aviso para console se necessário
    });
  });
});

    // Mostrar/esconder a agenda
    document.getElementById("btn-agenda").addEventListener("click", function() {
        const agenda = document.querySelector(".Agenda");
        agenda.style.display = getComputedStyle(agenda).display === "none" ? "flex" : "none";
    });

    // Observação: o bloco original que também adicionava resumos foi removido
    // porque duplicava handlers e não criava os inputs ocultos necessários
    // para o envio ao servidor. A lógica de salvar (criação de inputs
    // hidden) está implementada dentro do DOMContentLoaded acima.

        // CEP restringindo oa forma com que ele será escrito 
        document.getElementById('dojoCEP').addEventListener('input', function(e) {
            let valor = e.target.value.replace(/\D/g, ''); // Apenas números
            if (valor.length > 5) {
                valor = valor.slice(0, 5) + '-' + valor.slice(5, 8);
            }
            e.target.value = valor;
        });


        // Preencher endereço automaticamente ao perder o foco do campo CEP
        document.getElementById('dojoCEP').addEventListener('blur', function(e) {
            var cep = e.target.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch('https://viacep.com.br/ws/' + cep + '/json/')
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        if (!data.erro) {
                            document.getElementById('rua').value = data.logradouro || '';
                            document.getElementById('bairro').value = data.bairro || '';
                            document.getElementById('cidade').value = data.localidade || '';
                            document.getElementById('estado').value = data.uf || '';
                        } else {
                            alert('CEP não encontrado!');
                            document.getElementById('rua').value = '';
                            document.getElementById('bairro').value = '';
                            document.getElementById('cidade').value = '';
                            document.getElementById('estado').value = '';
                        }
                    })
                    .catch(function(err) {
                        alert('Erro ao consultar o CEP');
                        console.error('Erro fetch ViaCep:', err);
                    });
            } else if (cep.length > 0) {
                alert('CEP incompleto!');
                document.getElementById('rua').value = '';
                document.getElementById('bairro').value = '';
                document.getElementById('cidade').value = '';
                document.getElementById('estado').value = '';
            }
        });

        // Mapeamento de DDDs por UF
        const dddPorUF = {
            "AC": ["68"],
            "AL": ["82"],
            "AP": ["96"],
            "AM": ["92", "97"],
            "BA": ["71", "73", "74", "75", "77"],
            "CE": ["85", "88"],
            "DF": ["61"],
            "ES": ["27", "28"],
            "GO": ["61", "62", "64"],
            "MA": ["98", "99"],
            "MT": ["65", "66"],
            "MS": ["67"],
            "MG": ["31", "32", "33", "34", "35", "37", "38"],
            "PA": ["91", "93", "94"],
            "PB": ["83"],
            "PR": ["41", "42", "43", "44", "45", "46"],
            "PE": ["81", "87"],
            "PI": ["86", "89"],
            "RJ": ["21", "22", "24"],
            "RN": ["84"],
            "RS": ["51", "53", "54", "55"],
            "RO": ["69"],
            "RR": ["95"],
            "SC": ["47", "48", "49"],
            "SP": ["11", "12", "13", "14", "15", "16", "17", "18", "19"],
            "SE": ["79"],
            "TO": ["63"]
        };

        // Se quiser preencher DDD, chame preencherDDDs(data.uf) dentro do fetch do evento acima

        function preencherDDDs(uf) {
            const select = document.getElementById('dddSelect');
            select.innerHTML = `<option value="">Selecione</option>`;
            if (dddPorUF[uf]) {
                dddPorUF[uf].forEach(ddd => {
                    const opt = document.createElement('option');
                    opt.value = ddd;
                    opt.textContent = ddd;
                    select.appendChild(opt);
                });
                if (dddPorUF[uf].length === 1) {
                    select.value = dddPorUF[uf][0]; // Seleciona automático se só tiver um
                }
            }
        }
        // Função para permitir apenas numero no telefone
        function apenasNumeros(event) {
            const charCode = event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        // Validação do domínio do email
        document.getElementById('dojoEmail').addEventListener('blur', function() {
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



        // Form handling: não previne o envio para o servidor — permitir submit normal
        const dojoFormEl = document.getElementById('dojoForm');
        if (dojoFormEl) {
            dojoFormEl.addEventListener('submit', function(e) {
                // Garantir que os hidden inputs (em #aulas-salvas) estejam presentes.
                // Não chamamos e.preventDefault() aqui para permitir que o formulário
                // seja enviado normalmente para ../php/Criacao_dojo.php
            });
        }

        function createDojo() {
            const formData = new FormData(document.getElementById('dojoForm'));
            // Monta schedules a partir do objeto horariosPorDia
            const schedules = [];
            for (const dia in horariosPorDia) {
                if (horariosPorDia[dia].length > 0) {
                    schedules.push({
                        day: dia,
                        times: horariosPorDia[dia].filter(h => h)
                    });
                }
            }
            // Imagem
            let images = [];
            const imageInput = document.getElementById('dojoImage');
            if (imageInput && imageInput.files && imageInput.files.length > 0) {
                let filesProcessed = 0;
                Array.from(imageInput.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        images.push(e.target.result);
                        filesProcessed++;
                        if (filesProcessed === imageInput.files.length) {
                            salvarAcademia();
                        }
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                images = ['../img/afapm_jiu.png'];
                salvarAcademia();
            }

            function salvarAcademia() {
                const dojoData = {
                    name: formData.get('dojoName'),
                    description: formData.get('dojoDescription'),
                    address: formData.get('dojoAddress'),
                    phone: formData.get('dojoPhone'),
                    email: formData.get('dojoEmail'),
                    images: images,
                    schedules: schedules
                };
                try {
                    const newDojo = DojoStorage.saveDojo(dojoData);
                    alert('Academia criada com sucesso!');
                    window.location.href = '../html/home_dojo.html';
                } catch (error) {
                    alert('Erro ao criar academia: ' + error.message);
                }
            }
        }
        // Preview da imagem
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
                })
            };
            // Add sample data on first load
            if (DojoStorage.getAllDojos().length === 0) {
                DojoStorage.addSampleDojos();
            }
        };
    </script>
</body>

</html>