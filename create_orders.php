<?php
/**
 * @Author: Bernard Hanna
 * @Date:   2023-04-12 15:59:31
 * @Last Modified by:   Bernard Hanna
 * @Last Modified time: 2023-04-12 16:28:25
 */
foreach ($orders as $order) {
    // check if order exists before inserting
    $sql = "SELECT id FROM orders WHERE order_reference=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $order['order_reference']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        echo "Order with reference ".$order['order_reference']." already exists\n";
        continue;
    }
  
    // insert order details into orders table
    $sql = "INSERT INTO orders (
    order_reference, 
    customer_title, 
    customer_forename, 
    customer_surname, 
    customer_house_name_number, 
    customer_line_1, 
    customer_line_2, 
    customer_line_3, 
    customer_county, 
    customer_postcode, 
    customer_telephone, 
    customer_email, 
    order_line
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssssss",
      $order['order_reference'], 
      $order['customer']['title'], 
      $order['customer']['forename'], 
      $order['customer']['surname'], 
      $order['customer']['address']['house_name_number'], 
      $order['customer']['address']['line_1'], 
      $order['customer']['address']['line_2'], 
      $order['customer']['address']['line_3'], 
      $order['customer']['address']['county'], 
      $order['customer']['address']['postcode'], 
      $order['customer']['telephone'], 
      $order['customer']['email'], 
      $order['order_line']);
    if (mysqli_stmt_execute($stmt)) {
        $order_id = mysqli_insert_id($conn); // get the ID of the newly inserted order
        echo "Order inserted successfully\n";
    } else {
        echo "Error inserting order: " . mysqli_error($conn) . "\n";
    }
  
    // insert products into order_products table
    foreach ($order['products'] as $product) {
        $sql = "INSERT INTO order_products (
          order_id, 
          code, 
          qty) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $order_id, $product['code'], $product['qty']);
        if (mysqli_stmt_execute($stmt)) {
            echo "Product inserted successfully\n";
        } else {
            echo "Error inserting product: " . mysqli_error($conn) . "\n";
        }
    }
  }
  