<?php

$host = 'localhost';
$dbname = 'matchfight';
$username = 'root';
$password = 'root';

session_start();
if (!isset($_SESSION['academia_id']) && !isset($_SESSION['academia_id'])) {
    header("Location: ../html/contas.html");
    exit();
}



try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
            <form action="../php/Criacao_dojo.php" method="post" enctype="multipart/form-data" class="dojo-form">
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
                    <a type="submit" class="btn-agenda" id="btn-agenda">Criar Agenda</a>
                    <button type="submit" class="btn-primary">Criar Academia</button>
                </div>
            </form>

        </div>

        <form action="agenda.php" class="form-actions" method="post" enctype="multipart/form-data" id="Agenda">
            <div class="Agenda">
                <div class="calendar">

                    <!-- Domingo -->
                    <div class="day">
                        <h3>Domingo</h3>
                        <div class="Aula" id="Aula-domingo">
                            <label for="aulaNome-domingo">Nome da Aula:</label>
                            <input type="text" id="aulaNome-domingo" name="aulaNome[]" minlength="2" maxlength="100" required>
                            <select id="aulaTipo-domingo" name="aulaTipo[]" required>
                                <option value="">Selecione o tipo de aula</option>
                                <?php foreach ($modalidade as $mod): ?>
                                    <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                        <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="time" id="aulaTime-domingo" name="aulaTime[]" required>
                        </div>
                        <button type="button" class="btn-adicionar" id="btn-salvar-domingo">Adicionar aula</button>
                        <div class="btn-actions" id="btn-actions-domingo" style="display: none; margin-top: 10px;">
                            <button type="button" class="btn-excluir">Excluir</button>
                            <button type="submit" class="btn-salvar">Salvar</button>
                        </div>
                    </div>

                    <!-- Segunda -->
                    <div class="day">
                        <h3>Segunda</h3>
                        <div class="Aula" id="Aula-segunda">
                            <label for="aulaNome-segunda">Nome da Aula:</label>
                            <input type="text" id="aulaNome-segunda" name="aulaNome[]" minlength="2" maxlength="100" required>
                            <select id="aulaTipo-segunda" name="aulaTipo[]" required>
                                <option value="">Selecione o tipo de aula</option>
                                <?php foreach ($modalidade as $mod): ?>
                                    <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                        <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="time" id="aulaTime-segunda" name="aulaTime[]" required>
                        </div>
                        <button type="button" class="btn-adicionar" id="btn-salvar-segunda">Adicionar aula</button>
                        <div class="btn-actions" id="btn-actions-segunda" style="display: none; margin-top: 10px;">
                            <button type="button" class="btn-excluir">Excluir</button>
                            <button type="submit" class="btn-salvar">Salvar</button>
                        </div>
                    </div>

                    <!-- Terça -->
                    <div class="day">
                        <h3>Terça</h3>
                        <div class="Aula" id="Aula-terca">
                            <label for="aulaNome-terca">Nome da Aula:</label>
                            <input type="text" id="aulaNome-terca" name="aulaNome[]" minlength="2" maxlength="100" required>
                            <select id="aulaTipo-terca" name="aulaTipo[]" required>
                                <option value="">Selecione o tipo de aula</option>
                                <?php foreach ($modalidade as $mod): ?>
                                    <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                        <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="time" id="aulaTime-terca" name="aulaTime[]" required>
                        </div>
                        <button type="button" class="btn-adicionar" id="btn-salvar-terca">Adicionar aula</button>
                        <div class="btn-actions" id="btn-actions-terca" style="display: none; margin-top: 10px;">
                            <button type="button" class="btn-excluir">Excluir</button>
                            <button type="submit" class="btn-salvar">Salvar</button>
                        </div>
                    </div>

                    <!-- Quarta -->
                    <div class="day">
                        <h3>Quarta</h3>
                        <div class="Aula" id="Aula-quarta">
                            <label for="aulaNome-quarta">Nome da Aula:</label>
                            <input type="text" id="aulaNome-quarta" name="aulaNome[]" minlength="2" maxlength="100" required>
                            <select id="aulaTipo-quarta" name="aulaTipo[]" required>
                                <option value="">Selecione o tipo de aula</option>
                                <?php foreach ($modalidade as $mod): ?>
                                    <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                        <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="time" id="aulaTime-quarta" name="aulaTime[]" required>
                        </div>
                        <button type="button" class="btn-adicionar" id="btn-salvar-quarta">Adicionar aula</button>
                        <div class="btn-actions" id="btn-actions-quarta" style="display: none; margin-top: 10px;">
                            <button type="button" class="btn-excluir">Excluir</button>
                            <button type="submit" class="btn-salvar">Salvar</button>
                        </div>
                    </div>

                    <!-- Quinta -->
                    <div class="day">
                        <h3>Quinta</h3>
                        <div class="Aula" id="Aula-quinta">
                            <label for="aulaNome-quinta">Nome da Aula:</label>
                            <input type="text" id="aulaNome-quinta" name="aulaNome[]" minlength="2" maxlength="100" required>
                            <select id="aulaTipo-quinta" name="aulaTipo[]" required>
                                <option value="">Selecione o tipo de aula</option>
                                <?php foreach ($modalidade as $mod): ?>
                                    <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                        <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="time" id="aulaTime-quinta" name="aulaTime[]" required>
                        </div>
                        <button type="button" class="btn-adicionar" id="btn-salvar-quinta">Adicionar aula</button>
                        <div class="btn-actions" id="btn-actions-quinta" style="display: none; margin-top: 10px;">
                            <button type="button" class="btn-excluir">Excluir</button>
                            <button type="submit" class="btn-salvar">Salvar</button>
                        </div>
                    </div>

                    <!-- Sexta -->
                    <div class="day">
                        <h3>Sexta</h3>
                        <div class="Aula" id="Aula-sexta">
                            <label for="aulaNome-sexta">Nome da Aula:</label>
                            <input type="text" id="aulaNome-sexta" name="aulaNome[]" minlength="2" maxlength="100" required>
                            <select id="aulaTipo-sexta" name="aulaTipo[]" required>
                                <option value="">Selecione o tipo de aula</option>
                                <?php foreach ($modalidade as $mod): ?>
                                    <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                        <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="time" id="aulaTime-sexta" name="aulaTime[]" required>
                        </div>
                        <button type="button" class="btn-adicionar" id="btn-salvar-sexta">Adicionar aula</button>
                        <div class="btn-actions" id="btn-actions-sexta" style="display: none; margin-top: 10px;">
                            <button type="button" class="btn-excluir">Excluir</button>
                            <button type="submit" class="btn-salvar">Salvar</button>
                        </div>
                    </div>

                    <!-- Sábado -->
                    <div class="day">
                        <h3>Sábado</h3>
                        <div class="Aula" id="Aula-sabado">
                            <label for="aulaNome-sabado">Nome da Aula:</label>
                            <input type="text" id="aulaNome-sabado" name="aulaNome[]" minlength="2" maxlength="100" required>
                            <select id="aulaTipo-sabado" name="aulaTipo[]" required>
                                <option value="">Selecione o tipo de aula</option>
                                <?php foreach ($modalidade as $mod): ?>
                                    <option value="<?= htmlspecialchars($mod['id_modalidade']) ?>">
                                        <?= htmlspecialchars($mod['nm_modalidade']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="time" id="aulaTime-sabado" name="aulaTime[]" required>
                        </div>
                        <button type="button" class="btn-adicionar" id="btn-salvar-sabado">Adicionar aula</button>
                        <div class="btn-actions" id="btn-actions-sabado" style="display: none; margin-top: 10px;">
                            <button type="button" class="btn-excluir">Excluir</button>
                            <button type="submit" class="btn-salvar">Salvar</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>

    </main>

    <script>
        //interatividade dos botões de adicionar na agenda
        const botoes = document.querySelectorAll('.btn-adicionar');

        botoes.forEach(botao => {
            botao.addEventListener('click', () => {
                const day = botao.closest('.day');
                day.classList.toggle('expandido');
            });
        });
        // Mostrar/esconder a agenda
        document.getElementById("btn-agenda").addEventListener("click", function() {
            const agenda = document.querySelector(".Agenda");
            agenda.style.display = getComputedStyle(agenda).display === "none" ? "flex" : "none";
        });


const dias = ["domingo","segunda","terca","quarta","quinta","sexta","sabado"];

dias.forEach(dia => {
    const btnAdicionar = document.getElementById(`btn-salvar-${dia}`);
    const btnActions = document.getElementById(`btn-actions-${dia}`);
    const aula = document.getElementById(`Aula-${dia}`);

    if (btnAdicionar && btnActions && aula) {
        // Botão Adicionar
        btnAdicionar.addEventListener('click', () => {
            btnAdicionar.style.display = 'none';
            btnActions.style.display = 'block';
            aula.classList.add('expandido'); // abre a aula
        });

        // Botão Excluir
        btnActions.querySelector('.btn-excluir').addEventListener('click', () => {
            // Limpa campos
            aula.querySelectorAll('input, select').forEach(i => i.value = '');
            // Fecha a aula
            aula.classList.remove('expandido');
            // Esconde botões Salvar/Excluir
            btnActions.style.display = 'none';
            // Mostra botão Adicionar
            btnAdicionar.style.display = 'inline-block';
        });

        // Botão Salvar
        btnActions.querySelector('.btn-salvar').addEventListener('click', () => {
            // Fecha a aula
            aula.classList.remove('expandido');
            // Esconde botões Salvar/Excluir
            btnActions.style.display = 'none';
            // Mostra botão Adicionar
            btnAdicionar.style.display = 'inline-block';
        });
    }
});
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



        // Form handling and validation
        document.getElementById('dojoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            createDojo();
        });

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