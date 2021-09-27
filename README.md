# Symfony Sipariş Yönetimi Projesi

Symfony 4.0 ile geliştirilmiş, jwt ile oturum işlemlerinin bulunduğu basit bir sipariş yönetim projesi.

## Kurulum

Öncelikle projeyi klonlayın.

<code>git clone https://github.com/furkandura/symfony_order_manager.git</code>

Sonra klasör dizinine gidin.

<code>cd symfony_order_manager</code>

Bağımlılıkları yükleyin.

<code>composer install</code>

JWT için anahtarlar oluşturun.

<code>php bin/console lexik:jwt:generate-keypair</code>

Sırasıyla veritabanı migrate komutlarını çalıştırın.

<code>php bin/console doctrine:database:create</code>

<code>php bin/console doctrine:schema:create</code>

Projeyi ayağa kaldırın.

<code>php bin/console server:run</code>

## Test
Veritabanı bağlantısını ve projeyi ayağa kaldırma işlemini sağladıktan sonra sırasıyla şu test komutlarını çalıştırın.

<code>php ./bin/phpunit tests/Controller/UserControllerTest.php</code>

<code>php ./bin/phpunit tests/Controller/OrderControllerTest.php</code>


## Api Dökümanı

Postman collection dosyası <code>docs/Postman_collection.json</code> dosya dizininde bulunmaktadır.
Api döküman bilgilerini kolayca import edebilirsiniz.
