<?php
/**
 * @Author: Bernard Hanna
 * @Date:   2023-04-12 15:58:06
 * @Last Modified by:   Bernard Hanna
 * @Last Modified time: 2023-04-13 13:58:48
 */

$host = 'localhost';
$username = '';
$password = '';
$dbname = 'shopifiy';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
