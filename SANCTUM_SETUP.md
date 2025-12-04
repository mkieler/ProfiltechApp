# Laravel Sanctum + Fortify Headless Setup

## Overview
This Laravel application is configured for headless authentication using Sanctum and Fortify to work with a Nuxt.js frontend at `http://localhost:3000`.

## Configuration Summary

### 1. Global Middleware (bootstrap/app.php)
- **Sanctum Stateful API**: Applied to both `web` and `api` middleware groups
- **CSRF Protection**: Sanctum's `EnsureFrontendRequestsAreStateful` middleware handles CSRF for stateful requests from localhost:3000
- **No CSRF Exceptions Needed**: Removed generic `api/*` exception since module routes use Sanctum's built-in CSRF handling

### 2. Fortify Configuration
Fortify is configured to use its **default actions** - no custom code to maintain!

- Registration, login, logout, password reset all work out of the box
- Fortify automatically uses `Modules\User\Models\User` as configured in `config/auth.php`
- Two-factor authentication is enabled
- Views are disabled for headless/API mode

### 3. Module Routes on API Middleware
All module routes run on the `api` middleware group (without /api prefix):

```php
// In module ServiceProviders
\Illuminate\Support\Facades\Route::middleware('api')
    ->group(__DIR__ . '/../Routes/web.php');
```

This means:
- All module routes use API middleware with Sanctum
- Routes: `/deliveries/routes`, `/orders`, `/users` (no /api prefix)
- Fortify auth routes: `/login`, `/register`, etc.
- `auth:sanctum` middleware is applied globally via bootstrap/app.php
- **Web routing is disabled** - everything runs on API

### 4. Environment Variables

```env
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000

SESSION_DRIVER=cookie
SESSION_DOMAIN=localhost
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax

SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:8000
```

### 4. CORS Configuration (config/cors.php)
Configured to accept requests from `http://localhost:3000` with credentials support.

## Available Endpoints

### Authentication (Fortify - No Auth Required)
- `POST /register` - Register new user
- `POST /login` - Login
- `POST /logout` - Logout
- `GET /sanctum/csrf-cookie` - Get CSRF cookie
- `POST /forgot-password` - Request password reset
- `POST /reset-password` - Reset password

### Protected Endpoints (Require auth:sanctum)
- `GET /api/user` - Get authenticated user (from api.php)
- `PUT /user/profile-information` - Update profile
- `PUT /user/password` - Update password
- `POST /user/two-factor-authentication` - Enable 2FA
- `GET /user/two-factor-qr-code` - Get 2FA QR code
- All module routes: `/deliveries/routes`, `/orders`, `/users`, etc.

## Nuxt.js Integration

### 1. Install Nuxt Auth Utils (Recommended)
```bash
npm install nuxt-auth-utils
```

### 2. Configure nuxt.config.ts
```typescript
export default defineNuxtConfig({
  modules: ['nuxt-auth-utils'],

  runtimeConfig: {
    public: {
      apiBase: 'http://localhost:8000'
    }
  }
})
```

### 3. Example Login Flow

```typescript
// composables/useAuth.ts
export const useAuth = () => {
  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase

  const login = async (email: string, password: string) => {
    // 1. Get CSRF cookie
    await $fetch(`${apiBase}/sanctum/csrf-cookie`, {
      credentials: 'include'
    })

    // 2. Login
    await $fetch(`${apiBase}/login`, {
      method: 'POST',
      credentials: 'include',
      body: { email, password }
    })
  }

  const getUser = async () => {
    return await $fetch(`${apiBase}/api/user`, {
      credentials: 'include'
    })
  }

  const logout = async () => {
    await $fetch(`${apiBase}/logout`, {
      method: 'POST',
      credentials: 'include'
    })
  }

  return { login, getUser, logout }
}
```

### 4. Making Authenticated Requests

**Important**: Always include `credentials: 'include'` in your fetch requests!

```typescript
// Example: Fetch deliveries (no /api prefix needed)
const deliveries = await $fetch('http://localhost:8000/deliveries/routes', {
  credentials: 'include'
})
```

## How It Works

1. **First Request**: Nuxt app calls `/sanctum/csrf-cookie` to get CSRF token (stored in cookie)
2. **Login**: Nuxt app sends credentials to `/login`, Laravel sets session cookie
3. **Subsequent Requests**: Nuxt includes cookies automatically with `credentials: 'include'`
4. **Sanctum Middleware**: Validates the session cookie and authenticates the user
5. **Module Routes**: Protected by `auth:sanctum` middleware, return 401 if not authenticated

## Testing the Setup

### 1. Test CSRF Cookie
```bash
curl -X GET http://localhost:8000/sanctum/csrf-cookie \
  -H "Origin: http://localhost:3000" \
  -c cookies.txt
```

### 2. Test Login
```bash
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -H "Origin: http://localhost:3000" \
  -b cookies.txt \
  -c cookies.txt \
  -d '{"email":"user@example.com","password":"password"}'
```

### 3. Test Protected Route
```bash
curl -X GET http://localhost:8000/deliveries/routes \
  -H "Origin: http://localhost:3000" \
  -b cookies.txt
```

## Customization

### Making Public Routes (Optional)

By default, all module routes are protected with `auth:sanctum`. If you need public routes:

```php
// In modules/YourModule/Routes/web.php

Route::prefix('your-module')->group(function () {
    // Public route (remove auth:sanctum from ServiceProvider first)
    Route::get('/public', 'publicMethod');

    // Or apply auth selectively
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/protected', 'protectedMethod');
    });
});
```

To make ALL routes in a module public, remove the `auth:sanctum` append from bootstrap/app.php or apply middleware selectively in ServiceProvider.

### Customizing Fortify Behavior (Optional)

If you need to customize how Fortify handles registration, login, etc., you can create custom actions:

1. Run `php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"`
2. Create actions in `modules/User/Actions/`
3. Register them in a FortifyServiceProvider

**But for most use cases, Fortify's default behavior is sufficient!**

## Troubleshooting

### 401 Unauthorized
- Ensure you're calling `/sanctum/csrf-cookie` first
- Verify `credentials: 'include'` is in all requests
- Check `SESSION_DOMAIN=localhost` in .env
- Clear browser cookies and try again

### CORS Errors
- Verify `FRONTEND_URL=http://localhost:3000` in .env
- Check config/cors.php includes your Nuxt URL
- Ensure Laravel is running on port 8000

### Session Not Persisting
- Check `SESSION_DRIVER=cookie` in .env
- Verify `SESSION_DOMAIN=localhost` (not .localhost)
- Use localhost, not 127.0.0.1

## Production Deployment

When deploying to production, update:

1. `.env`:
   ```env
   APP_URL=https://api.yourdomain.com
   FRONTEND_URL=https://yourdomain.com
   SESSION_DOMAIN=.yourdomain.com
   SESSION_SECURE_COOKIE=true
   SANCTUM_STATEFUL_DOMAINS=yourdomain.com,www.yourdomain.com
   ```

2. `config/cors.php`:
   ```php
   'allowed_origins' => [env('FRONTEND_URL', 'https://yourdomain.com')],
   ```
