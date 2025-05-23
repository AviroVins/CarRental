<?php

// Ścieżka do pliku composer.lock
$lockFile = __DIR__ . '/composer.lock';

// Ścieżka do pliku .bat do zapisu
$batFile = __DIR__ . '/install_modules.bat';

// Sprawdź czy composer.lock istnieje
if (!file_exists($lockFile)) {
    die("Brak pliku composer.lock w katalogu: $lockFile\n");
}

// Wczytaj i sparsuj JSON
$lockData = json_decode(file_get_contents($lockFile), true);
if (!$lockData) {
    die("Błąd parsowania composer.lock\n");
}

// Pobierz listę pakietów (packages i packages-dev)
$packages = array_merge(
    $lockData['packages'] ?? [],
    $lockData['packages-dev'] ?? []
);

// Przygotuj polecenia composer require
$requireLines = [];
foreach ($packages as $pkg) {
    $name = $pkg['name'];
    $version = $pkg['version'];
    // composer require expects wersje w formacie bez 'v' na początku (opcjonalnie)
    $versionClean = ltrim($version, 'v');
    $requireLines[] = "composer require {$name}:{$versionClean}";
}

// Dodaj podane rozszerzenia VS Code i komendy laravelowe:
$extraCommands = <<<BAT

:: Instalacja rozszerzeń VS Code
call code --install-extension ritwickdey.liveserver --force
call code --install-extension zignd.html-css-class-completion --force
call code --install-extension onecentlin.laravel-extension-pack --force
call code --install-extension xdebug.php-debug --force
call code --install-extension bmewburn.vscode-intelephense-client --force
call code --install-extension humao.rest-client --force
call code --install-extension pkief.material-icon-theme --force
call code --install-extension damms005.devdb --force

pause

:: Laravel commands
php artisan breeze:install blade yes Pest

npm install && npm run dev

php artisan migrate

pause

BAT;

// Złóż cały plik
$content = "@echo off\n\n";
$content .= implode("\n", $requireLines) . "\n";
$content .= $extraCommands;

// Zapisz do pliku
file_put_contents($batFile, $content);

echo "Gotowe! Plik instalacyjny zapisany w: $batFile\n";
