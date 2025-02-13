<strong>Yêu cầu:</strong> <br>
 -> PHP >= 8.1.<br>
 ->Composer >= 2.0 <br>
 -> MySQL <br>
 <strong> Bước 1: Clone dự án</strong> <br>
 - Clone dự án:  https://github.com/Tanminh30102001/test_project_jho.git <br>
 - cd test_project_jho<br>
 - git checkout dev <br>
 - git pull (Để lấy source mới nhất)<br>
 <strong> Bước 2: Cài đặt các dependencies</strong> <br>
 - composer install <br>
 <strong> Bước 3: Cấu hình môi trường</strong> <br>
 - cp .env.example .env<br>
 - Cấu hình DB theo DB của bạn <br>
 <strong> Bước 4: Generate key cho dự án</strong> <br>
 - php artisan key:generate <br>
 <strong> Bước 5:Chạy migration và seeder</strong> <br>
- php artisan migrate<br>
- php artisan db:seed<br>
 <strong> Bước 6:Chạy sever </strong> <br>
 - php artisan serve <br>

 


