Teste utilizando CodeIgniter 3, PHP 5.4, CentOS 7, Ratchet (WAMP), ReactPHP e ZMQ.
====================


**Instalação do Ratchet**

    composer require cboden/ratchet:*

--------

**Instalação do ZMQ**
Instalação da extensão:
sudo yum install gcc make autoconf pkg-config
sudo yum install -y epel-release
sudo yum install -y zeromq-devel
pecl install zmq-1.1.3

Aparecerá a seguinte mensagem:
Build process completed successfully
Installing '/usr/lib64/php/modules/zmq.so'
install ok: channel://pecl.php.net/zmq-1.1.3
configuration option "php_ini" is not set to php.ini location
You should add "extension=zmq.so" to php.ini

Após fazer a alteração do php.ini, instalar o react/zmq:
composer require react/zmq

--------

**Desativar o firewalld**

	systemctl disable firewalld
	
**Parar o firewalld**

	systemctl stop firewalld
	
--------

**Configurar porta 5555**

	yum provides /usr/sbin/semanage
	
	yum install policycoreutils-python-utils
	
	yum install policycoreutils-python
	
	semanage port -a -t http_port_t -p tcp 5555

--------

**Startar o servidor WebSocket**

Insira o seguinte comando no terminal (na pasta raiz do projeto):

    php index.php servidor index

--------

**Observações**

- Necessário alterar o caminho do vendor/autoload no controller Servidor e nos demais controllers que utilizam o ZMQContext (neste exemplo, seria necessário alterá-lo na API Alterar_status).

- Está sendo utilizada a porta 8080 para o WebSocket e a porta 5555 para o TCP. Em caso de erro, talvez seja necessário alterá-las.
