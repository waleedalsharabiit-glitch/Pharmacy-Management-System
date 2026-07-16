<?php
// تفعيل عرض الأخطاء أثناء فترة التطوير والتعلم (قم بإيقافها عند رفع الموقع للإنتاج)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = 'localhost';
$db_name  = 'pharmacy_db2';
$username = 'root';
$password = '';
$charset  = 'utf8mb4';

try{
    $conn=new PDO("mysql:host=$servername;dbname=$db_name;charset=$charset",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    }catch(PDOException $ex){
        die("فشل الاتصال بقاعدة البيانات: " . $ex->getMessage());

        }

?>