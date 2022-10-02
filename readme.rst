Teste utilizando CodeIgniter 3, Ratchet (WAMP) e ReactPHP.
====================


**Instalação do Ratchet e ReactPHP**

Insira o seguinte comando no terminal:

    composer require cboden/ratchet ^0.4

--------

**Startar o servidor WebSocket**

Insira o seguinte comando no terminal (na pasta raiz do projeto):

    php index.php servidor index

--------

**Observações**

- Necessário alterar o caminho do vendor/autoload no controller Servidor e nos demais controllers que utilizam o TcpConnector (neste exemplo, seria necessário alterá-lo na API Alterar_status).

- Está sendo utilizada a porta 8080 para o WebSocket e a porta 5555 para o TCP. Em caso de erro, talvez seja necessário alterá-las.
