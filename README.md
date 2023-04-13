# shopify-order-system
1. Fix the error Error creating order: {"errors":{"customer":["Phone has already been taken"]} when creating an order
-- 1a. This may then throw a similar error for the email address which also have the fix applied.
2. Line Items is not dymacically populated with the info from mySQL/ Json, please ensure the data is sent when creating an order
- as we dont currently have product titles just use the 'code'
- use the code for the variant title also
- We dont have price just yet so this can just be set to zero

````
'line_items'=>array(
      array(
        'title' => 'Example Product 1',
        'price' => 0,
        'quantity' => 1,
        'variant_id' => 1234567890
      )
  )
```
