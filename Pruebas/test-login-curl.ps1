# Script de Prueba: Login Real con CURL
# Simula exactamente lo que hace el navegador

Write-Host "`n=== PRUEBA DE LOGIN CON CURL ===" -ForegroundColor Cyan

$apiUrl = "http://127.0.0.1:8000/api/login"
$origin = "http://127.0.0.1:8002"

# Datos de login
$body = @{
    login_id = "bkneedless@gmail.com"
    password = "+Joe1234"
} | ConvertTo-Json

Write-Host "`n📦 Request Body:" -ForegroundColor Yellow
Write-Host $body

# 1. Probar OPTIONS (preflight)
Write-Host "`n🔍 Paso 1: Petición OPTIONS (CORS Preflight)..." -ForegroundColor Cyan
try {
    $curlOptions = "curl.exe -v -X OPTIONS `"$apiUrl`" -H `"Origin: $origin`" -H `"Access-Control-Request-Method: POST`" -H `"Access-Control-Request-Headers: content-type`" 2>&1"
    $optionsResult = Invoke-Expression $curlOptions | Out-String
    
    if ($optionsResult -match "Access-Control-Allow-Origin") {
        Write-Host "✅ CORS Preflight OK - Headers permitidos" -ForegroundColor Green
        $optionsResult | Select-String "Access-Control" | ForEach-Object {
            Write-Host "   $_" -ForegroundColor Gray
        }
    } else {
        Write-Host "❌ CORS Preflight FALLÓ - No hay headers Access-Control" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Error en OPTIONS: $($_.Exception.Message)" -ForegroundColor Red
}

# 2. Probar POST (login real)
Write-Host "`n🔍 Paso 2: Petición POST (Login real)..." -ForegroundColor Cyan
try {
    # Crear archivo temporal con el body
    $tempFile = [System.IO.Path]::GetTempFileName()
    $body | Out-File -FilePath $tempFile -Encoding UTF8
    
    $curlPost = "curl.exe -v -X POST `"$apiUrl`" -H `"Origin: $origin`" -H `"Content-Type: application/json`" -H `"Accept: application/json`" --data-binary `"@$tempFile`" 2>&1"
    $postResult = Invoke-Expression $curlPost | Out-String
    
    Remove-Item $tempFile -ErrorAction SilentlyContinue
    
    # Buscar status code
    if ($postResult -match "HTTP/[\d.]+ (\d+)") {
        $statusCode = $matches[1]
        Write-Host "Status Code: $statusCode" -ForegroundColor $(if ($statusCode -eq "200") { "Green" } else { "Red" })
    }
    
    # Buscar headers CORS en respuesta
    if ($postResult -match "Access-Control-Allow-Origin") {
        Write-Host "✅ CORS Headers presentes en respuesta" -ForegroundColor Green
    } else {
        Write-Host "❌ NO hay CORS Headers en respuesta" -ForegroundColor Red
        Write-Host "   Esto causará que el navegador bloquee la respuesta" -ForegroundColor Yellow
    }
    
    # Mostrar respuesta JSON
    if ($postResult -match '\{.*\}') {
        Write-Host "`n📄 Respuesta JSON:" -ForegroundColor Yellow
        $jsonMatch = $matches[0]
        try {
            $json = $jsonMatch | ConvertFrom-Json
            $json | ConvertTo-Json -Depth 3 | Write-Host -ForegroundColor Gray
        } catch {
            Write-Host $jsonMatch -ForegroundColor Gray
        }
    }
    
    Write-Host "`n📋 Output completo guardado para análisis:" -ForegroundColor Cyan
    $logFile = "Pruebas\ultimo-curl-test.log"
    $postResult | Out-File -FilePath $logFile -Encoding UTF8
    Write-Host "   Ver: $logFile" -ForegroundColor Gray
    
} catch {
    Write-Host "❌ Error en POST: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n=== FIN DE PRUEBA ===`n" -ForegroundColor Cyan
