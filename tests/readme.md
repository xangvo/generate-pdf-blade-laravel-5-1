1.php artisan make:test UserTest
2.php artisan make:test InteractWithDatabaseTest



Phải tạo file :
1. DB test riêng
2. .env.testing để tạo DB riêng biệt nhé ( mục đích là copy và bỏ nó vào khi chạy thôi) , để không ảnh hưởng DB chính.

3. Đọc cái này:
http://larabrain.com/tips/configuring-a-test-database-using-laravel-and-phpunit
