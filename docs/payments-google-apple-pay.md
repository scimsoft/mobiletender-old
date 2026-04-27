# Apple Pay & Google Pay (vía PayPal)

Esta integración añade **Apple Pay** y **Google Pay** a las páginas `/checkout/pay` y `/checkout/`, manteniendo PayPal Smart Buttons como fallback. Los tres métodos comparten el mismo backend (PayPal Orders v2 API), con lo que la captura, conciliación y reembolsos se gestionan desde tu cuenta PayPal Business.

## Resumen de la arquitectura

```
Frontend (Safari/Chrome)              Backend (Laravel)              PayPal
───────────────────────────────       ───────────────────────       ───────────
Apple Pay button                      POST /paypal/create-order     POST /v2/checkout/orders
Google Pay button             ───►    PayPalController              PayPalClient
PayPal Buttons                        Validates amount === ticket
                                      ◄── { id: ORDER_ID }
                              ◄───
Wallet flow (auth)
                                      POST /paypal/capture-order/   POST /v2/checkout/orders/{id}/capture
                              ───►    {id}                  ───►
                                      ◄── { status: COMPLETED }
                              ◄───
Redirect to onApproveUrl
(printOrderOnline | printOrderPagado)
```

## Cosas que **debes hacer tú** (no son código)

> **Importante:** todo se hace en `developer.paypal.com` (Developer Dashboard), NO en `paypal.com` (cuenta cliente). Logéate con la misma cuenta de PayPal Business.

### 1. Variables de entorno

Añade a `.env`:

```bash
PAYPAL_MODE=live                # o sandbox para pruebas
PAYPAL_CLIENT_ID=...            # ya lo tienes
PAYPAL_SECRET=...               # ⚠️ NUEVO — secret server-side de la app PayPal
```

El `PAYPAL_SECRET` se obtiene en `https://developer.paypal.com/dashboard/applications/live` (o `/sandbox` para pruebas) → tu app → **Show Secret**.

### 2. Habilitar Apple Pay y Google Pay en tu app del Developer Dashboard

1. Entra en `https://developer.paypal.com/dashboard/applications/live`.
2. Toggle **Live** (arriba) si vas a producción, o **Sandbox** para pruebas.
3. **Apps & Credentials** → selecciona la app que tiene tu `PAYPAL_CLIENT_ID`.
4. Baja a **Features** (o **Mobile and digital payments** / **Accept payments → Advanced Credit and Debit Card Payments** según el rollout).
5. Marca los checkboxes **Apple Pay** y **Google Pay** → **Save**.

> Si **no ves** estos checkboxes, tu cuenta aún no tiene activado "Expanded Checkout" / "Advanced Credit and Debit Card Payments". En la misma página verás un botón **Get Started** u **Onboard** para activarlo. Si la opción no aparece, abre un ticket de soporte de PayPal Business indicando que quieres activar **Expanded Checkout** para Apple Pay y Google Pay.

### 3. Verificación de dominio (Apple Pay únicamente)

Apple Pay no funcionará sin verificar el dominio. Google Pay **no** requiere este paso.

1. En la misma app → sección **Apple Pay** → **Manage** → **Add Domain**.
2. Introduce: `comer.playaalta.com`.
3. **Descarga el fichero** `apple-developer-merchantid-domain-association` (sin extensión).
4. Súbelo a tu servidor en `public/.well-known/apple-developer-merchantid-domain-association`.
5. Verifica que es accesible:

```bash
curl -I https://comer.playaalta.com/.well-known/apple-developer-merchantid-domain-association
```

Debe devolver **200 OK** y `Content-Type: application/octet-stream` (este último es **obligatorio** según Apple).

6. Vuelve al dashboard PayPal y pulsa **Register Domain**.

#### nginx — servir `.well-known/` con el Content-Type correcto

```nginx
location = /.well-known/apple-developer-merchantid-domain-association {
    types { } default_type application/octet-stream;
    try_files $uri =404;
}

location ^~ /.well-known/ {
    allow all;
    try_files $uri =404;
}
```

#### Apache (`public/.htaccess` o site config)

```apache
<Files "apple-developer-merchantid-domain-association">
    ForceType application/octet-stream
    Require all granted
</Files>

<FilesMatch "^\.well-known">
    Require all granted
</FilesMatch>
```

#### Restricciones que Apple impone al fichero

- **Sin redirects 3xx** — Apple no los sigue. Si redireccionas `http://` → `https://` desde un proxy, asegúrate de que esa ruta concreta no se vea afectada.
- Servido por **HTTPS** con certificado válido.
- Sin firewall / sin auth básica delante.
- Tamaño exacto del fichero como te lo dio PayPal — no lo edites.

### 4. HTTPS válido en producción

Apple Pay y Google Pay **no se muestran** sobre HTTP ni con certificados autofirmados/expirados. Tienes ya HTTPS, así que listo.

## Comportamiento esperado en cliente

| Dispositivo / navegador | Apple Pay | Google Pay | PayPal Buttons |
|---|---|---|---|
| Safari iOS / macOS con tarjeta en Wallet | ✅ visible | ❌ oculto | ✅ visible |
| Chrome Android con tarjeta | ❌ oculto | ✅ visible | ✅ visible |
| Chrome desktop con tarjeta en Google Wallet | ❌ oculto | ✅ visible | ✅ visible |
| Firefox / sin tarjetas guardadas | ❌ oculto | ❌ oculto | ✅ visible |

Los botones se ocultan automáticamente si el SDK reporta `isEligible: false`. La UI usa `<details>` para colapsar PayPal bajo "Otras formas de pagar" cuando hay wallet disponible.

## Pruebas

### Sandbox

1. Pon `PAYPAL_MODE=sandbox` y usa credenciales de la app sandbox.
2. Apple Pay sandbox: tarjetas de prueba en [https://developer.apple.com/apple-pay/sandbox-testing/](https://developer.apple.com/apple-pay/sandbox-testing/).
3. Google Pay TEST mode: cualquier tarjeta sirve, no se cobra.

### Producción

Empieza con un importe pequeño (1 €) y reembolsa desde el dashboard de PayPal.

## Endpoints añadidos

| Método | Ruta | Función |
|---|---|---|
| `POST` | `/paypal/create-order` | Body: `{ amount, context }` → `{ id, status }` |
| `POST` | `/paypal/capture-order/{orderId}` | → `{ status: "COMPLETED" }` |

Ambas con CSRF (`web` middleware). El `amount` se valida server-side contra `getSumNewTicketLines(ticketID) * 1.10` para impedir que un cliente manipule el importe.

## Ficheros relevantes

- `app/Services/PayPalClient.php` — OAuth + Orders API
- `app/Http/Controllers/PayPalController.php` — endpoints
- `resources/js/shop/paypal-checkout.js` — módulo unificado de wallets
- `resources/js/shop/pay-page.js`, `checkout-page.js` — bootstrappers
- `resources/views/order/pay.blade.php`, `checkout.blade.php` — contenedores UI
- `config/paypal.php`, `.env.example`
- `routes/web.php` — rutas POST de create/capture
