<?php
$loader = require_once __DIR__ . '/vendor/autoload.php';
$recognizer = new TdTrung\OSRecognizer\OSRecognizer();

print("Platform: {$recognizer->getPlatform()}");
print("\n");
print("Release: {$recognizer->getRelease()}");;
