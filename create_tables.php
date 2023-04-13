<?php
/**
 * @Author: Bernard Hanna
 * @Date:   2023-04-12 15:58:36
 * @Last Modified by:   Bernard Hanna
 * @Last Modified time: 2023-04-12 16:18:07
 */
// create orders table
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_reference VARCHAR(50) NOT NULL,
    customer_title VARCHAR(50) NOT NULL,
    customer_forename VARCHAR(50) NOT NULL,
    customer_surname VARCHAR(50) NOT NULL,
    customer_house_name_number VARCHAR(50) NOT NULL,
    customer_line_1 VARCHAR(50) NOT NULL,
    customer_line_2 VARCHAR(50) NOT NULL,
    customer_line_3 VARCHAR(50) NOT NULL,
    customer_county VARCHAR(50) NOT NULL,
    customer_postcode VARCHAR(10) NOT NULL,
    customer_telephone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    order_line VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )";
  
  $stmt = mysqli_prepare($conn, $sql);
  if ($stmt !== false) {
      if (mysqli_stmt_execute($stmt)) {
          echo "Orders table created successfully\n";
      } else {
          echo "Error creating orders table: " . mysqli_stmt_error($stmt) . "\n";
      }
      mysqli_stmt_close($stmt);
  } else {
      echo "Error preparing orders table creation statement: " . mysqli_error($conn) . "\n";
  }
  
  // create order_products table
  $sql = "CREATE TABLE IF NOT EXISTS order_products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT(6) UNSIGNED NOT NULL,
    code VARCHAR(50) NOT NULL,
    qty INT(6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
  )";
  
  $stmt = mysqli_prepare($conn, $sql);
  if ($stmt !== false) {
      if (mysqli_stmt_execute($stmt)) {
          echo "Order products table created successfully\n";
      } else {
          echo "Error creating order products table: " . mysqli_stmt_error($stmt) . "\n";
      }
      mysqli_stmt_close($stmt);
  } else {
      echo "Error preparing order products table creation statement: " . mysqli_error($conn) . "\n";
  }