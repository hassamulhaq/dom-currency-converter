### DOM Currency Converter

WordPress Plugin, it will replace currency symbol & value with openexchangerates.org API currencies. I also created a cronjob that runs every day and updates the converted value in the.txt file.



Place your API key at `$app_id`

`$symbols` accepts multiple currencies with comma separated.
```php
//if (!is_null($cronjob) && $cronjob == 'true') {
$app_id = 'b143e2e376c14??????????????????';
$symbols = 'AED';
$base = 'USD';
$show_alternative = false;
$prettyprint = false;
$convert_amount = 1; // 1 USD is equal to AED?

$oxr_url = "https://openexchangerates.org/api/latest.json?app_id={$app_id}&base={$base}&symbols={$symbols}&prettyprint={$prettyprint}&show_alternative={$show_alternative}";

// Open CURL session:
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $oxr_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
...
...
...
```

#   d o m - c u r r e n c y - c o n v e r t e r  
 