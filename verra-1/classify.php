<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Domain\Factory\RuleFactory;
use App\Domain\Model\Word;
use App\Domain\Model\WordCollection;

if ($argc < 2) {
    echo "Usage: php classify.php <rules-json-file>\n";
    exit(1);
}

// CORRECCIÓN: Leemos el primer argumento del array de la consola ($argv[1])
$rulesFile = (string) $argv[1];

if (!file_exists($rulesFile)) {
    echo "Error: Configuration file '{$rulesFile}' not found.\n";
    exit(1);
}

$jsonContent = file_get_contents($rulesFile);
if ($jsonContent === false) {
    echo "Error: Could not read configuration file.\n";
    exit(1);
}

/** @var array<string, mixed> $config */
$config = json_decode($jsonContent, true) ?? [];
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error: Invalid JSON format in configuration file.\n";
    exit(1);
}

try {
    $engineRule = RuleFactory::create($config);
} catch (Exception $e) {
    echo "Error building rules: " . $e->getMessage() . "\n";
    exit(1);
}

$wordsFile = __DIR__ . '/wordlist.txt';
if (!file_exists($wordsFile)) {
    echo "Error: Database file 'words.txt' not found.\n";
    exit(1);
}

$rawLines = file($wordsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($rawLines === false) {
    echo "Error: Could not read words database.\n";
    exit(1);
}

$collection = new WordCollection();
foreach ($rawLines as $line) {
    $collection->add(new Word(trim($line)));
}

$filteredCollection = $collection->filterBy($engineRule);

foreach ($filteredCollection->all() as $word) {
    echo $word->getValue() . "\n";
}
