<table id="table-cabecalho">
    <tbody>
        <tr>
            <td class="align-middle">
                <span id="titulo-cabecalho">Monitoramento</span>
                <a id="btn-logout">Logout</a>
            </td>
        </tr>
    </tbody>
</table>

<hr id="hr-cabecalho">

<div class="mt-2">
    Bem-vindo(a), <?php echo $_SESSION['usuario_nome'] ?>.
    <div style="float:right">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoAgente">
            Novo Agente
        </button>
    </div>
</div>

<table id="table-agentes" class="table mt-2">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Status</th>
            <th>Tipo de Usuário</th>
        </tr>
    </thead>
    <tbody id="lista-agentes"></tbody>
</table>

<span id="msg"></span>

<div class="modal fade" id="modalNovoAgente">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Agente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="POST" id="form-novo-agente">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-form-label" for="nome-agente">Nome:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nome-agente" name="nome-agente">
                            <span id="nome-agente-feedback" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label class="col-form-label" for="usuario-agente">Usuário:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="usuario-agente" name="usuario-agente">
                            <span id="usuario-agente-feedback" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label class="col-form-label" for="tipo-usuario-id">Tipo de Usuário:</label>
                        </div>
                        <div class="col-md-8">
                            <select class="form-select" name="tipo-usuario-id" id="tipo-usuario-id">
                                <option value="">Selecione</option>
                                <option value="1">Agente</option>
                                <option value="2">Supervisor</option>
                            </select>
                            <span id="tipo-usuario-id-feedback" class="invalid-feedback"></span>
                        </div>
                    </div>
                </form>
                <span id="msgModalNovoAgente"></span>
                <span id="msgModalNovoAgente-feedback"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-novo-agente">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        var conn = new ab.Session('ws://192.168.15.6:8080',
            function() {
                conn.subscribe('PainelAgente', function(topic, data) {
                    if (data.subcategory == 'Update') {
                        //Percorrer tabela já renderizada e alterar apenas o usuário que foi atualizado
                        var nomeAgente = data.nomeAgente;
                        var novoStatusID = data.novoStatusID;
                        var novo_status_text = ''

                        switch (novoStatusID) {
                            case '1':
                                novo_status_text = 'Indisponível';
                                break;
                            case '2':
                                novo_status_text = 'Disponível';
                                break;
                            case '3':
                                novo_status_text = 'Ocupado';
                                break;
                            case '4':
                                novo_status_text = 'Pausa';
                                break;
                            default:
                                alert(`O agente ${nomeAgente} tentou alterar seu status, porém com erro.`)
                        }

                        var tbody, tr, td, i, txtValue;
                        tbody = document.getElementById("lista-agentes");
                        tr = tbody.getElementsByTagName("tr");

                        for (i = 0; i < tr.length; i++) {
                            td = tr[i].getElementsByTagName("td")[1];
                            if (td) {
                                txtValue = td.innerText;
                                if (txtValue == nomeAgente) {
                                    tr[i].getElementsByTagName("td")[2].innerHTML = novo_status_text
                                }
                            }
                        }

                        document.getElementById('msg').innerHTML = `O agente ${data.nomeAgente} alterou o status para ${novo_status_text}.`
                    }

                    if (data.subcategory == 'Insert') {
                        //Fazer nova requisição AJAX para popular tabela
                        listar_agentes()
                        document.getElementById('msg').innerHTML = `O supervisor ${data.nomeSupervisor} cadastrou o agente ${data.nomeAgenteCadastrado}.`
                    }
                });
            },
            function() {
                console.warn('WebSocket connection closed');
            }, {
                'skipSubprotocolCheck': true
            }
        );

        document.getElementById('btn-logout').addEventListener('click', () => {
            var url = window.location.href + 'api/logout';

            fetch(url)
                .then(response => response.json())
                .then(result => {
                    window.location.href = window.location.href
                })
                .catch(err => {
                    console.error('Falha ao recuperar informações', err);
                });
        })

        function listar_agentes() {
            var url = window.location.href + 'api/listar_agentes';

            fetch(url)
                .then(response => response.json())
                .then(result => {
                    var row_agentes = '';
                    for (key in result) {
                        value = result[key]
                        row_agentes += `
                        <tr>
                        <td>${value.id}</td>
                        <td>${value.nome}</td>
                        <td>${value.status}</td>
                        <td>${value.tipo_usuario}</td>
                        </tr>
                        `
                    }
                    document.getElementById('lista-agentes').innerHTML = row_agentes
                })
                .catch(err => {
                    console.error('Falha ao recuperar informações', err);
                });
        }

        listar_agentes()

        document.querySelector("#btn-novo-agente").addEventListener('click', () => {
            var url = window.location.href + 'api/cadastrar_agente';
            const formNovoAgente = document.querySelector("#form-novo-agente");
            let data = new FormData(formNovoAgente);

            fetch(url, {
                    method: 'post',
                    body: data
                })
                .then(response => response.json())
                .then(result => {
                    console.log(result)
                    if (result.errors) {
                        for (key in result.errors) {
                            value = result.errors[key]
                            document.getElementById(key).classList.add('is-invalid');
                            document.getElementById(key + '-feedback').innerHTML = value;
                        }
                    } else if (result.success) {
                        document.getElementById('msg').innerHTML = result.success;
                        formNovoAgente.reset();
                        let modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalNovoAgente'))
                        modal.hide();
                    }
                })
                .catch(err => {
                    console.error('Falha ao recuperar informações', err);
                });
        })

        document.querySelectorAll('input').forEach(item => {
            item.addEventListener('keyup', event => {
                var input = item.getAttribute('id')
                document.getElementById(input).classList.remove('is-invalid');
            })
        })

        document.querySelectorAll('select').forEach(item => {
            item.addEventListener('click', event => {
                var input = item.getAttribute('id')
                document.getElementById(input).classList.remove('is-invalid');
            })
        })
    });
</script>