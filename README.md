### DOM Currency Converter

WordPress Plugin, it will replace currency symbol & value with openexchangerates.org API currencies. I also created a cronjob that runs every day and updates the converted value in the.txt file.



> This plugin will change the USD symbol/value with AED symbol/value according to current rate of dollar. This plugin is for eagle booking.
> But you can modify DOM classes according to your theme and use this plugin.

Place your API key at `$app_id`

`$symbols` accepts multiple currencies with comma separated.

```text
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
```