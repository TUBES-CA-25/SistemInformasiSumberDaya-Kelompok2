$files = Get-ChildItem -Path "app\views\admin" -Filter "*.php" -Recurse | Where-Object { $_.FullName -notlike "*templates*" }

$oldFunction = @"
function navigate(route) {
    if (window.location.port === '8000') {
        window.location.href = '/index.php?route=' + route;
    } else {
        window.location.href = '/' + route;
    }
}
"@

$newFunction = @"
function navigate(route) {
    const basePath = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2') 
        ? '/SistemInformasiSumberDaya-Kelompok2/public/'
        : '/';
    
    if (route === '') {
        window.location.href = basePath;
    } else {
        window.location.href = basePath + '?route=' + route;
    }
}
"@

foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw
    if ($content -match [regex]::Escape($oldFunction)) {
        $content = $content -replace [regex]::Escape($oldFunction), $newFunction
        Set-Content -Path $file.FullName -Value $content -NoNewline
        Write-Host "Updated: $($file.Name)"
    }
}

Write-Host "Done! Updated $($files.Count) files."
