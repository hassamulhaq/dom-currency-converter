<?php
/*
Plugin Name: DOM Currency Converter
Plugin URI: https://hassam.dev/
Description: This plugin will change the USD symbol/value with AED symbol/value according to current rate of dollar. This plugin is for eagle booking.
Version: 0.0.1
Author: M Hassam
Author URI: https://hassam.dev/
License: GPL2
*/


/**
 * create log file
 *
 */
// Define the path to the log file
$log_file = plugin_dir_path(__FILE__) . 'logs.txt';
if (!file_exists($log_file)) {
    $log_file_path = plugin_dir_path( __FILE__ ) . 'logs.txt';
    $log_file_open = fopen($log_file_path, "w");
    fwrite($log_file_open, "---------------\n");
    // Close the file
    fclose($log_file_open);
    chmod($log_file_path, 0777);
}

function dom_currency_converter_activate() {
    // create a new log file in the plugin directory
    $log_file_path = plugin_dir_path( __FILE__ ) . 'logs.txt';
    $log_file_open = fopen($log_file_path, "w");
    // Add a string to the file
    fwrite($log_file_open, "---------------\n");
    // Close the file
    fclose($log_file_open);
    chmod($log_file_path, 0777);
    if (!chmod($log_file_path, 0777)) {
        error_log("Failed to assign permissions to file: [logs.txt]" . "\n", 3, $log_file_path);
    } else {
        error_log("Permissions assigned successfully to file: [logs.txt]" . "\n", 3, $log_file_path);
    }


    // Create a txt file, and from that file we read converted currency value
    error_log('Let\'s create new [openexchangeratesorg-aed.txt] file!' . "\n", 3, $log_file_path);
    $filepath = plugin_dir_path(__FILE__) . 'openexchangeratesorg-aed.txt';
    // Assign read and write permissions to the file
    $file = fopen($filepath, "w");
    // Add a string to the file
    fwrite($file, $rate_aed ?? 0);
    // Close the file
    fclose($file);
    chmod($filepath, 0777);
    if (!chmod($filepath, 0777)) {
        error_log("Failed to assign permissions to file: {$filepath}" . "\n", 3, $log_file_path);
    } else {
        error_log("Permissions assigned successfully to file: {$filepath}" . "\n", 3, $log_file_path);
    }

    // create cronjob
    if (!wp_next_scheduled('dom_currency_converter_cron')) {
        wp_schedule_event(time(), 'myplugin_minute', 'dom_currency_converter_cron');
    }
}
function dom_currency_converter_deactivate() {
    // Remove cron job on plugin deactivation
    wp_clear_scheduled_hook('dom_currency_converter_cron');

    // empty the contents of the file
    $file_path = plugin_dir_path( __FILE__ ) . 'logs.txt';
    $file = fopen( $file_path, 'a' );
    fwrite( $file, "\n--------------------\nplugin deactivate.\n" );
    fclose( $file );
}
register_activation_hook( __FILE__, 'dom_currency_converter_activate' );
register_deactivation_hook( __FILE__, 'dom_currency_converter_deactivate' );
// END log file


/**
 * create a cronjob
 *
 */
// Schedule a daily cron job
/*add_action('dom_currency_converter_wp', 'dom_currency_converter_schedule_cron');
function dom_currency_converter_schedule_cron()
{
    if (!wp_next_scheduled('dom_currency_converter_cron')) {
        wp_schedule_event(time(), 'myplugin_minute', 'dom_currency_converter_cron');
    }
}*/

// Define the function that will run when the cron job is triggered
add_action('dom_currency_converter_cron', 'dom_currency_converter_run_cron');
function dom_currency_converter_run_cron()
{
    $log_file_path = plugin_dir_path(__FILE__) . 'logs.txt';
    // Your code goes here
    error_log("cronjob triggered!" . "\n", 3, $log_file_path);
    fetchCurrencyFromApi();
}

// Add custom interval of 1 minute
add_filter('cron_schedules', 'dom_currency_converter_cron_interval');
function dom_currency_converter_cron_interval($schedules)
{
    $schedules['myplugin_minute'] = array(
        'interval' => 60,
        'display' => esc_html__('Every Minute')
    );

    return $schedules;
}


/**
 * email: developer.hassam@outlook.com
 *
 * https://openexchangerates.org
 * https://openexchangerates.org/account/app-ids
 *
 *
 * Code by hassam.dev
 */

// CRON job to check USD price on every morning
function fetchCurrencyFromApi($cronjob = null)
{
    $log_file_path = plugin_dir_path(__FILE__) . 'logs.txt';
    try {
        $disclaimer = '';
        $license = '';
        $timestamp = '';
        $base = '';
        $rates = [];
        $rate_aed = 0;
        error_log('fetchCurrencyFromApi triggered' . "\n", 3, $log_file_path);
        //if (!is_null($cronjob) && $cronjob == 'true') {
        $app_id = '';
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

        // Get the data:
        $json = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('Error: ' . curl_error($ch) . "\n", 3, $log_file_path);
        } else {
            // Decode JSON response:
            $oxr_latest = json_decode($json, true);
            //foreach ($oxr_latest as $item) {
            $disclaimer = $oxr_latest['disclaimer'] ?? '';
            $license = $oxr_latest['license'] ?? '';
            $timestamp = $oxr_latest['timestamp'] ?? '';
            $base = $oxr_latest['base'] ?? '';
            $rates = $oxr_latest['rates'] ?? [];
            $rate_aed = $rates['AED'] ?? 0;
            //}
            error_log('Price updated' . "\n", 3, $log_file_path);
        }
        curl_close($ch);
        //}

        // Create a text file
        $file_path = plugin_dir_path(__FILE__) . 'openexchangeratesorg-aed.txt';
        $file = fopen($file_path, "w");
        // Add a string to the file
        fwrite($file, $rate_aed);
        // Close the file
        fclose($file);

        // Assign read and write permissions to the file
        $filename = "openexchangeratesorg-aed.txt";
        $filepath = plugin_dir_path(__FILE__) . $filename;
        if (file_exists($filepath)) {
            error_log('The [openexchangeratesorg-aed.txt] file exists!' . "\n", 3, $log_file_path);
            chmod($file_path, 0777);
        } else {
            error_log('The [openexchangeratesorg-aed.txt] file does not exist!' . "\n", 3, $log_file_path);
        }

        if (!chmod($filepath, 0777)) {
            error_log("Failed to assign permissions to file: {$filepath}" . "\n", 3, $log_file_path);
        } else {
            error_log("Permissions assigned successfully to file: {$filepath}" . "\n", 3, $log_file_path);
        }
    } catch (Exception $e) {
        error_log("An error occurred: {$e->getMessage()}" . "\n", 3, $log_file_path);
    }
}

// Read from the file
//$readFile = fopen("openexchangeratesorg-aed.txt", "r");
//$fileData = fread($readFile, filesize("openexchangeratesorg-aed.txt"));
//fclose($readFile);

// create a new DOMDocument object
/*$fileData = file_get_contents('openexchangeratesorg-aed.txt');
$doc = new DOMDocument();
// create a new hidden input field
try {
    $input = $doc->createElement('input');
    $input->setAttribute('type', 'hidden');
    $input->setAttribute('id', 'openexchangeratesorg_usd_to_aed_value');
    $input->setAttribute('name', 'openexchangeratesorg_usd_to_aed_value');
    $input->setAttribute('value', "{$fileData}");
    // append the input field to the DOM
    $doc->appendChild($input);
    // get the HTML string for the DOM
    $html = $doc->saveHTML();
    // output the HTML string
    echo $html;
} catch (DOMException $e) {
    error_log("{$e->getMessage()}" . "\n", 3, $log_file);
}*/

function dom_currency_converter_enqueue_scripts()
{
    // Enqueue the JavaScript file
    wp_enqueue_script('dom-currency-converter-script', plugin_dir_url(__FILE__) . 'dom-currency-converter.js');
}

add_action('wp_enqueue_scripts', 'dom_currency_converter_enqueue_scripts');
