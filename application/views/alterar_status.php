<table id="table-cabecalho">
    <tbody>
        <tr>
            <td>
                <span id="titulo-cabecalho">Status Agente</span>
                <a id="btn-logout">Logout</a>
            </td>
        </tr>
    </tbody>
</table>

<hr id="hr-cabecalho">

<div class="mt-2">
    Bem-vindo(a), <?php echo $_SESSION['usuario_nome'] ?>.
</div>

<div class="d-inline-flex col-md-4 col-form-label mt-2">
    <form id="form-alterar-status" class="row">
        <div class="col-auto">
            <label for="status_id" class="col-form-label">Status:</label>
        </div>
        <div class="col-auto">
            <select id="status_id" name="status_id" class="form-select"></select>
        </div>
        <div class="col-auto">
            <button class="btn btn-secondary" id="btn-alterar-status">Alterar</button>
        </div>

    </form>
</div>
<div id="msg"></div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        var conn = new ab.Session('ws://192.168.15.6:8080',
            function() {
                //
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
                .then(response => response.json()) // retorna uma promise
                .then(result => {
                    window.location.href = window.location.href
                })
                .catch(err => {
                    console.error('Falha ao recuperar informações', err);
                });
        })

        const formAlterarStatus = document.querySelector('#form-alterar-status');
        formAlterarStatus.addEventListener('submit', (event) => {
            event.preventDefault();
            let data = new FormData(formAlterarStatus);
            var url = window.location.href + 'api/alterar_status';

            fetch(url, {
                    method: 'post',
                    body: data
                })
                .then(response => response.json())
                .then(result => {
                    document.getElementById('msg').innerHTML = result.mensagem;
                    //conn.send('Status_Agente');
                })
                .catch(err => {
                    console.error('Falha ao recuperar informações', err);
                });
        })

        function lista_status() {
            var url = window.location.href + 'api/listar_status';

            fetch(url)
                .then(response => response.json()) // retorna uma promise
                .then(result => {
                    var select = document.getElementById('status_id')
                    for (key in result.todos_status) {
                        value = result.todos_status[key]
                        let option = document.createElement('option');
                        option.value = value.id
                        option.innerHTML = value.status
                        if (option.value == result.status_agente[0].id) {
                            option.selected = true
                        }
                        select.appendChild(option);
                    }
                })
                .catch(err => {
                    console.error('Falha ao recuperar informações', err);
                });
        }

        lista_status()

    });
</script>