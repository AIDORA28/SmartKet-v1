# Script de Prueba - Verificar CORS Headers
# Verifica que el servidor Laravel esté enviando los headers CORS correctos

Write-Host "`n=== PRUEBA DE HEADERS CORS ===" -ForegroundColor Cyan

$apiUrl = "http://127.0.0.1:8000"
$origins = @(
    "http://127.0.0.1:8002",
    "http://localhost:8002",
    "http://localhost:5173"
)

foreach ($origin in $origins) {
    Write-Host "`n🔍 Probando origen: $origin" -ForegroundColor Yellow
    
    try {
        # Probar endpoint /api/login
        $response = Invoke-WebRequest -Uri "$apiUrl/api/login" `
            -Method OPTIONS `
            -Headers @{
                "Origin" = $origin
                "Access-Control-Request-Method" = "POST"
                "Access-Control-Request-Headers" = "content-type,authorization"
            } `
            -UseBasicParsing `
            -ErrorAction Stop
        
        Write-Host "  Status: $($response.StatusCode)" -ForegroundColor Green
        
        # Verificar headers CORS importantes
        $corsHeaders = @(
            "Access-Control-Allow-Origin",
            "Access-Control-Allow-Methods",
            "Access-Control-Allow-Headers",
            "Access-Control-Allow-Credentials"
        )
        
        $allHeadersPresent = $true
        foreach ($header in $corsHeaders) {
            $value = $response.Headers[$header]
            if ($value) {
                Write-Host "  ✅ $header : $value" -ForegroundColor Green
            } else {
                Write-Host "  ❌ $header : FALTANTE" -ForegroundColor Red
                $allHeadersPresent = $false
            }
        }
        
        if ($allHeadersPresent) {
            Write-Host "  ✅ Todos los headers CORS presentes" -ForegroundColor Green
        } else {
            Write-Host "  ⚠️  Faltan headers CORS" -ForegroundColor Yellow
        }
        
    } catch {
        Write-Host "  ❌ Error: $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host "`n=== FIN DE PRUEBA ===`n" -ForegroundColor Cyan
