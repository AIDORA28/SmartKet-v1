# Script de Prueba - Login API
# Simula una petición de login desde el frontend

Write-Host "`n=== PRUEBA DE LOGIN API ===" -ForegroundColor Cyan
Write-Host "Probando endpoint: POST http://127.0.0.1:8000/api/login`n" -ForegroundColor Yellow

# Datos de login (ajusta según tu usuario de prueba)
$loginData = @{
    login_id = "admin@smartket.com"  # o el email que tengas
    password = "password"
} | ConvertTo-Json

# Headers que envía el frontend
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
    "Origin" = "http://127.0.0.1:8002"
}

Write-Host "📦 Datos enviados:" -ForegroundColor Green
Write-Host $loginData

Write-Host "`n🔍 Probando petición OPTIONS (preflight)..." -ForegroundColor Cyan
try {
    $optionsResponse = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/login" `
        -Method OPTIONS `
        -Headers @{
            "Origin" = "http://127.0.0.1:8002"
            "Access-Control-Request-Method" = "POST"
            "Access-Control-Request-Headers" = "content-type"
        } `
        -UseBasicParsing

    Write-Host "✅ OPTIONS exitoso - Status: $($optionsResponse.StatusCode)" -ForegroundColor Green
    Write-Host "Headers CORS recibidos:" -ForegroundColor Yellow
    $optionsResponse.Headers.GetEnumerator() | Where-Object { $_.Key -like "*Access-Control*" } | ForEach-Object {
        Write-Host "  $($_.Key): $($_.Value)" -ForegroundColor Gray
    }
} catch {
    Write-Host "❌ OPTIONS falló: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host $_.Exception -ForegroundColor DarkRed
}

Write-Host "`n🔍 Probando petición POST (login real)..." -ForegroundColor Cyan
try {
    $response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/login" `
        -Method POST `
        -Headers $headers `
        -Body $loginData `
        -UseBasicParsing

    Write-Host "✅ LOGIN EXITOSO!" -ForegroundColor Green
    Write-Host "`n📄 Respuesta del servidor:" -ForegroundColor Yellow
    $response | ConvertTo-Json -Depth 3
    
} catch {
    Write-Host "❌ LOGIN FALLÓ" -ForegroundColor Red
    Write-Host "Status Code: $($_.Exception.Response.StatusCode.value__)" -ForegroundColor Red
    Write-Host "Mensaje: $($_.Exception.Message)" -ForegroundColor Red
    
    if ($_.ErrorDetails) {
        Write-Host "`n📄 Detalles del error:" -ForegroundColor Yellow
        Write-Host $_.ErrorDetails.Message
    }
}

Write-Host "`n=== FIN DE PRUEBA ===`n" -ForegroundColor Cyan
