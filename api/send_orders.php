<?php
/**
 * @Author: Bernard Hanna
 * @Date:   2023-04-12 16:09:13
 * @Last Modified by:   Bernard Hanna
 * @Last Modified time: 2023-04-13 13:57:21
 */


//Set Order Endpoint
function checkCustomer($email,$phone){
    $phone=str_replace('+','',$phone);
    $phone=preg_replace('/\s+/', '', $phone);
    $customerendpoint = 'https://ipltester.myshopify.com/admin/api/2023-01/customers/search.json?query=(email:'.$email.') OR (phone:'.$phone.')';

    $ch_customer = curl_init();
    curl_setopt($ch_customer, CURLOPT_URL, $customerendpoint);
    curl_setopt($ch_customer, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch_customer, CURLOPT_USERPWD, 'da0ad3091908bd22bcd8f4ac6df794fd' . ':' . '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');
    curl_setopt($ch_customer, CURLOPT_RETURNTRANSFER, true);


    // execute cURL request and get response
    $response = curl_exec($ch_customer);
    $http_code = curl_getinfo($ch_customer, CURLINFO_HTTP_CODE);

    // check for cURL errors
    if (curl_errno($ch_customer)) {
        $error_msg = "cURL error: " . curl_error($ch_customer);
        curl_close($ch_customer);

        // log error and stop execution
        error_log($error_msg);
        die("An error occurred while creating the order. Please try again later.");
    }
    curl_close($ch_customer);
 
    if(count((json_decode($response))->customers)>0){
        return  ((json_decode($response))->customers)[0];
    }
    else{
        return "nothing found";
    }

    // close cURL connection
}





$orderendpoint = 'https://ipltester.myshopify.com/admin/api/2023-01/orders.json?status=any';
// Loop through each order
foreach ($orders as $order) {

   $customerCheck=checkCustomer($order['customer']['email'],$order['customer']['telephone']);

   if($customerCheck=="nothing found"){
    $customer=array(
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
            );

   }else{
    $customer=$customerCheck;
   };


  // Create the order data array
  $order_data = array(
        'order' => array(
            'customer' => $customer,
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
            'line_items'=>
            array_map(function ($a) { 
                $a['price']=0;
                $a['name']='test name';
                $a['title']='test title';

                
                return $a; }, $order['products'])
            
            
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
        echo($error_msg);

        die("An error occurred while creating the order. Please try again later.");
    }
        
    
  }
