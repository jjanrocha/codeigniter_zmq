<table class="table" style="height: 100%;">
    <tbody>
        <tr style="vertical-align: middle;">
            <td align="center">
                <div class="card text-dark bg-light" style="width: 50%; text-align: left;">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form id="form-login" method="POST">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuário">
                                    <span id="usuario-feedback" class="invalid-feedback"></span>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-secondary">Logar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const formLogin = document.querySelector('#form-login');
        formLogin.addEventListener('submit', (event) => {
            event.preventDefault();
            var url = window.location.href + 'api/login'
            let data = new FormData(formLogin);

            fetch(url, {
                    method: 'post',
                    body: data
                })
                .then(response => response.json()) //retorna uma promise
                .then(result => {
                    if (result.errors) {
                        for (key in result.errors) {
                            value = result.errors[key]
                            document.getElementById(key).classList.add('is-invalid');
                            document.getElementById(key + '-feedback').innerHTML = value;
                        }
                    } else {
                        window.location.href = window.location.href
                    }
                })
                .catch(err => {
                    console.error('Falha ao recuperar informações', err);
                });
        });

    });

    document.querySelectorAll('input').forEach(item => {
        item.addEventListener('keyup', event => {
            var input = item.getAttribute('id')
            document.getElementById(input).classList.remove('is-invalid');
        })
    })
</script>