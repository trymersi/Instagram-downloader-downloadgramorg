<?php
ini_set('memory_limit', '1024M');
include "dom.php";
require_once('vendor/autoload.php');

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Chrome\ChromeOptions; 


$downloadDir = 'downloads/';

$capabilities = DesiredCapabilities::chrome();
$options = new \Facebook\WebDriver\Chrome\ChromeOptions();
$prefs = [
    'download.default_directory' => $downloadDir,
    'download.prompt_for_download' => false,
    'download.directory_upgrade' => true,
    'safebrowsing.enabled' => true,
];

$options->setExperimentalOption('prefs', $prefs);
$capabilities->setCapability(\Facebook\WebDriver\Chrome\ChromeOptions::CAPABILITY, $options);


$formUrl = 'https://downloadgram.org/';
$driver = RemoteWebDriver::create('http://127.0.0.1:9515/', $capabilities);


$driver->get($formUrl);

sleep(3);

$urlInput = $driver->findElement(WebDriverBy::id('url'));

$urlInput->sendKeys('https://www.instagram.com/reel/C7S5WTLJ7Xu/?utm_source=ig_web_copy_link');

$submitButton = $driver->findElement(WebDriverBy::id('submit'));

$submitButton->click();

sleep(5);

$closeButton = $driver->findElement(WebDriverBy::cssSelector('button[data-dismiss="modal"]'));

$closeButton->click();

sleep(1);


$downloadDiv = $driver->findElement(WebDriverBy::id('downloadhere'));

$downloadLinkElement = $downloadDiv->findElement(WebDriverBy::cssSelector('a[download][href]'));
$downloadLink = $downloadLinkElement->getAttribute('href');

$driver->quit();

$fileName = rand(1111111111,9999999999).".mp4";
$fullPath = $downloadDir . $fileName;

$ch = curl_init($downloadLink);
$fp = fopen($fullPath, 'wb');

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);
