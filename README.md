# JET REQUEST
Jet Request mendukung pengembangan proyek aplikasi Hascha Media atau EMS Platform. Namun, kesederhanaan perpustakaan memberikan fleksibilitas yang tinggi untuk digunakan di berbagai proyek aplikasi apa pun berbasis Laravel.

## Konfigurasi

Menyesuaikan pengaturan API melalui _environment_.
```
HOST_API_HTTP="http://"
HOST_API_DOMAIN="example.net"
HOST_API_ENDPOINT="api"
```

## Memulai Permintaan Sederhana
Membuat permintaan sederhana menggunakan kelas facade ``\Jet\Request\Client`` dan panggil _request()_ method.

```php
$response = Request::request();
$response->successful();
$response->statusCode();
$response->message();
$response->getResults();
```

Mengirimkan data untuk permintaan spesifik, misal saja untuk mengirimkan data kredensial pengguna baru:
```php
$response = Request::request(
    [
        'name' => 'Bob',
        'email' => 'example@bob.com',
        'password' => '12345678',
    ],
    'POST',
    function ($request) {
        $request->url('user/store')
        ->header('Authorization Bearer', 'xxx');
    }
);
```