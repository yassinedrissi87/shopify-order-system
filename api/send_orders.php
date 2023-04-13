<?php
/**
 * @Author: Bernard Hanna
 * @Date:   2023-04-12 16:09:13
 * @Last Modified by:   Bernard Hanna
 * @Last Modified time: 2023-04-13 13:57:21
 */


//Set Order Endpoint
$orderendpoint = 'https://ipltester.myshopify.com/admin/api/2023-01/orders.json?status=any';
// Loop through each order
foreach ($orders as $order) {
  // Create the order data array
  $order_data = array(
        'order' => array(
            'customer' => array(
                'first_name' => $order['customer']['forename'],
                'last_name' => $order['customer']['surname'],
                'email' => $order['customer']['email'],
                'phone' => $order['customer']['telephone'],
                'verified_email' => true,
                'addresses' => array(
                    array(
                        'address1' => $order['customer']['address']['house_name_number'],
                        'address2' => $order['customer']['address']['line_1'],
                        'address3' => $order['customer']['address']['line_2'],
                        'city' => $order['customer']['address']['line_3'],
                        'phone' => $order['customer']['telephone'],
                        'zip' => $order['customer']['address']['postcode'],
                        'country' => 'CA'
                    )
                )
            ),
            'billing' => array(
                'first_name' => $order['customer']['forename'],
                'last_name' => $order['customer']['surname'],
                'address' => array(
                    'address1' => $order['customer']['address']['house_name_number'],
                    'address2' => $order['customer']['address']['line_1'],
                    'address3' => $order['customer']['address']['line_2'],
                    'city' => $order['customer']['address']['line_3'],
                    'phone' => $order['customer']['telephone'],
                    'zip' => $order['customer']['address']['postcode'],
                    'country' => 'CA'
                )
            ),
            'shipping_address' => array(
                'address1' => $order['customer']['address']['house_name_number'],
                'address2' => $order['customer']['address']['line_1'],
                'address3' => $order['customer']['address']['line_2'],
                'city' => $order['customer']['address']['line_3'],
                'phone' => $order['customer']['telephone'],
                'zip' => $order['customer']['address']['postcode'],
                'country' => 'CA'
            ),
            'line_items'=>array(
                array(
                  'title' => 'Example Product 1',
                  'price' => 0,
                  'quantity' => 1,
                  'variant_id' => 1234567890
               )
            )
      )
    );
    // convert order data to JSON
    $order_json = json_encode($order_data);

    // set cURL options
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $orderendpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_USERPWD, $api_key . ':' . $password);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $order_json);


    // execute cURL request and get response
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // check for cURL errors
    if (curl_errno($ch)) {
        $error_msg = "cURL error: " . curl_error($ch);
        curl_close($ch);

        // log error and stop execution
        error_log($error_msg);
        die("An error occurred while creating the order. Please try again later.");
    }

    // close cURL connection
    curl_close($ch);

    // check response status
    if ($http_code == 201) {
        echo "Order created successfully\n";
    } else {
        // log error and stop execution
        $error_msg = "Error creating order: " . $response;
        error_log($error_msg);
        die("An error occurred while creating the order. Please try again later.");
    }
        
  }