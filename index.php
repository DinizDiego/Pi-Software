<?php
    include('class/classes.php');
    
    $Kits = new Kit();
    $Usuario = new Usuario();
    $Docente = new Docente();
    $Entrada_saida = new Entrada_saida();
        
    //Cadastrar kit
    if(isset($_POST['btnCadastrar'])){
        $Kits->cadastrarKit($_POST);        
    }

    //Cadastrar Docente        
    if(isset($_POST['btnCadastrarDocente'])){
        $Docente->cadastrar($_POST);
    }

    if(isset($_POST['btnRetirar'])){
        $Entrada_saida->retirar($_POST);
    }

    if(isset($_POST['btnDevolver'])){
        $Entrada_saida->devolver($_POST);
    }
    
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Kits</title>
    <link rel="stylesheet" href="css/Kits.css">
    <link rel="stylesheet" href="css/modal.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="funcoes.js" defer></script>
</head>
<body>

<section class="barra-status">
    <div class="status-box">
        <div class="disponivel"></div>
        <span>Disponíveis</span>
        <span class="quantidade" id="quantidade-disponiveis"><?php echo $Kits->contarKitsDisponiveis()?></span>
    </div>
    <div class="status-box">
        <div class="indisponivel"></div>
        <span>Indisponíveis</span>
        <span class="quantidade" id="quantidade-indisponiveis"><?php echo $Kits->contarKitsIndisponiveis()?></span>
    </div>
</section>


   

<section class="cadastro-kit">
    <button class="botao-cadastrar" id="btnMenu">Menu</button>
    <div id="menu" class="menu oculto">
        <button class="botao-fechar" id="btnFecharMenu">&times;</button>
        <ul>
            <li><a  id="btnAtualizarDadosModal" style="display: none;">Atualizar Dados</a></li>
            <li><a  id="btnCadastrarKitModal">Cadastrar Kit</a></li>
            <li><a  id="btnCadastrarDocenteModal">Cadastrar Docente</a></li>
            <li><a  id="btnLog" href="log.php">Registro</a></li>
            <li><a  href="sair.php">Sair</a></li>
        </ul>
    </div>
</section>



<div id="modalCadastrarKit" class="modal" role="dialog" aria-labelledby="text-cadastrar-kit" aria-modal="true">
    <div class="modal-content">
        <span class="close" id="closeCadastrarKit" aria-label="Fechar">&times;</span>
        <h2 id="text-cadastrar-kit">Cadastrar Kit</h2>
        <form method="POST" action="?" id="cadastrar-kit-form">
            <div class="input-group">
                <label for="kitNome">Número da sala do Kit:</label>
                <input type="text" id="kitNome" name="n_sala" class="modal-input" placeholder="Número do Kit" required>
            </div>
            <div class="input-group">
                <label for="kitNome">Nº Registro Kit:</label>
                <input type="text" id="codigo_barras_kit" name="codigo_barras_kit" class="modal-input" placeholder="Nº Registro Kit:" required>
            </div>
            <div class="input-group">
                <label for="kitDescricao">Descrição:</label>
                <input type="text" id="kitDescricao" name="descricao" class="modal-input" placeholder="Descrição" required>
            </div>
            <button type="submit" name="btnCadastrar" class="modal-button">Cadastrar</button>
        </form>
    </div>
</div>


<div id="modalCadastrarDocente" class="modal">
    <div class="modal-content">
        <span class="close" id="closeCadastrarDocente">&times;</span>
        <h2>Cadastrar Docente</h2>
        <form id="cadastrar-docente-form" method="POST" action="?">
            <div class="input-group">
                <label for="nomeDocente">Nome:</label>
                <input type="text" id="nomeDocente" name="nome" class="modal-input" placeholder="Nome" required>
            </div>
            <div class="input-group">
                <label for="dataNascimento">Data de Nascimento:</label>
                <input type="date" id="dataNascimento" name="data_nascimento" class="modal-input" required>
            </div>
            <div class="input-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" class="modal-input" placeholder="Telefone" required>
            </div>
            <div class="input-group">
                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" class="modal-input" placeholder="CEP" required>
            </div>
            <div class="input-group">
                <label for="turno">Turno:</label>
                <select id="turno" name="turno" class="modal-input" required>
                    <option value="manhã">Manhã/Tarde</option>
                    <option value="tarde">Tarde/Noite</option>
                </select>
            </div>
            <div class="input-group">
                <label for="codigoBarras">Código de Barras:</label>
                <input type="text" id="codigoBarras" name="codigo_barras" class="modal-input" placeholder="Código de Barras" required>
            </div>
            <div class="input-group">
                <label for="cpfDocente">CPF:</label>
                <input type="text" id="cpfDocente" name="cpf" class="modal-input" placeholder="CPF" required>
            </div>
            <button type="submit" name="btnCadastrarDocente" class="modal-button">Cadastrar</button>
        </form>
    </div>
</div>



<div id="modalAtualizarDados" class="modal">
    <div class="modal-content">
        <span class="close" id="closeAtualizarDados">&times;</span>
        <h2>Atualizar Dados</h2>
        <form id="atualizar-dados-form">
            <!-- Campos do formulário aqui -->
            <div class="input-group">
                <label for="campo1">Campo 1:</label>
                <input type="text" id="campo1" class="modal-input" placeholder="Campo 1" required>
            </div>
            <button type="submit" class="modal-button">Atualizar</button>
        </form>
    </div>
</div>


<section class="secao-busca">
    <input type="text" id="buscar_kit" class="input-busca" placeholder="Buscar..." aria-label="Campo de busca">
</section>

<section class="tabela">
    <table>
        <thead>
            <tr>
                <th>Kits</th>
                <th>Responsável</th>
                <th>Situação</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($Kits->listar() as $kit): ?>
                <tr>
                    <td><?php echo $kit->n_sala; ?></td>
                    <td><?php echo $Kits->obterDocenteEmUso($kit->id_kit)?></td>
                    <td><?php Helper::mostrarSituacao($kit->situacao)?></td>
                    <td><button type="button" class="btn btn-secondary" 
                                data-bs-toggle="modal" data-bs-target="#observacaoModal"
                                data-id="<?php echo $kit->id_kit; ?>"> <!-- Passando o id_kit -->
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>    
        </tbody>
    </table>
</section>

<!-- Modal do Bootstrap 5 -->
<div class="modal fade" id="observacaoModal" tabindex="-1" aria-labelledby="observacaoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="observacaoModalLabel">Observação</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <textarea id="observacaoTexto" class="form-control" rows="5" readonly>
                
        </textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>


    <div class="botao-status">
        <button class="status-box2" id="btnRetirar" type="button">Retirar</button>
        <button class="status-box2" id="btnDevolver" type="button">Devolver</button>
    </div>

<!-- Modal para Retirar Kit -->
<div id="modalRetirar" class="modal" role="dialog" aria-labelledby="text-retirar" aria-modal="true">
    <div class="modal-content">
        <span class="close" id="closeRetirar" aria-label="Fechar">&times;</span>
        <h2 id="text-retirar">Retirar Kit</h2>
        <form id="retirar-form" method="post">
            <div class="input-group">
                <label for="telefone">Nº Registro Docente:</label>
                <input type="text" id="telefone" class="modal-input" name="codigo_barras" placeholder="Nº Registro Docente" required>
            </div>
            <div class="input-group">
                <label for="nome">Nº Registro Kit:</label>
                <input type="text" id="nome" class="modal-input" name="codigo_barras_kit" placeholder="Nº Registro Kit" required>
            </div>
            <div class="input-group">
                <label for="observacoes">Observações:</label>
                <input type="text" id="observacao_saida" name="observacao_saida" class="modal-input" placeholder="Observações" required>
            </div>
            <button type="submit" name="btnRetirar" class="modal-button">Confirmar Retirada</button>
        </form>
    </div>
</div>

<!-- Modal para Devolver Kit -->
<div id="modalDevolver" class="modal" role="dialog" aria-labelledby="text-devolver" aria-modal="true">
    <div class="modal-content">
        <span class="close" id="closeDevolver" aria-label="Fechar">&times;</span>
        <h2 id="text-devolver">Devolver Kit</h2>
        <form id="devolver-form" method="post">
            <div class="input-group">
                <label for="telefoneDevolver">Nº Registro Docente:</label>
                <input type="text" id="telefoneDevolver" class="modal-input" name="codigo_barras" placeholder="Nº Registro Docente" required>
            </div>
            <div class="input-group">
                <label for="nomeDevolver">Nº Registro Kit:</label>
                <input type="text" id="nomeDevolver" class="modal-input" name="codigo_barras_kit" placeholder="Nº Registro Kit" required>
            </div>
            <div class="input-group">
                <label for="observacao_entrada">Observações:</label>
                <input type="text" id="observacao_entrada" name="observacao_entrada" class="modal-input" placeholder="Observações">
            </div>
            <button type="submit" name="btnDevolver" class="modal-button">Confirmar Devolução</button>
        </form>
    </div>
</div>

<footer class="rodape">
    <p>Imagens meramente ilustrativas. Copyright 2024 © Axo Solution's Todos os direitos reservados.</p>
</footer>

</body>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
    // Verifica se o parâmetro de status está presente na URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('sc') && urlParams.get('sc') === 'true') {
        alert('Cadastro realizado com sucesso!');
        
        // Limpa todos os parâmetros da URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
        
        // Função de filtro para a tabela
        $('#buscar_kit').on('input', function() {
            var valor = $(this).val().toLowerCase(); // Obtém o valor do input e converte para minúsculas
            $('table tbody tr').filter(function() {
                // Mostra as linhas que contêm o valor e esconde as que não contêm
                $(this).toggle($(this).find('td').first().text().toLowerCase().indexOf(valor) > -1);
            });
        });
    });
</script>

</html>
