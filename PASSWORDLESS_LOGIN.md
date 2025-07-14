# Passwordless Login System

This system allows users to log in without passwords using email or SMS verification links.

## Features

- **Email-based login**: Users can enter their email address and receive a secure login link
- **SMS-based login**: Users can enter their phone number and receive a login link via SMS
- **Auto-registration**: New users are automatically created when they use passwordless login
- **Name collection**: First-time users are prompted to provide their name
- **Secure tokens**: Login links expire after 15 minutes and can only be used once
- **Remember me**: Users stay logged in across browser sessions

## How it works

1. User visits `/passwordless-login`
2. User chooses email or SMS and enters their identifier
3. System generates a secure token and sends a login link
4. User clicks the link to log in
5. If user doesn't exist, they're asked to provide their name
6. User is logged in and redirected to dashboard

## Routes

- `GET /passwordless-login` - Show the passwordless login form
- `POST /passwordless-login` - Send the login link
- `GET /login/{token}` - Process the login link
- `GET /complete-signup/{token}` - Collect name for new users
- `POST /complete-signup/{token}` - Complete the signup process

## Database Tables

### `passwordless_login_tokens`
- `token` - Unique secure token (64 characters)
- `identifier` - Email address or phone number
- `type` - 'email' or 'sms'
- `expires_at` - When the token expires (15 minutes)
- `used_at` - When the token was used (null if not used)
- `ip_address` - IP address of the requester
- `user_agent` - User agent of the requester

### `users` (modified)
- `email` - Now nullable to support SMS-only users
- `password` - Now nullable for passwordless users
- `sms` - Phone number for SMS-based login

## Configuration

### Email
The system uses Laravel's mail configuration. For development, emails are logged to `storage/logs/laravel.log`.

### SMS
SMS sending is currently logged. To enable actual SMS sending, implement one of these services:
- Twilio
- AWS SNS
- Vonage (formerly Nexmo)
- Other SMS providers

Update the `sendSmsLink()` method in `PasswordlessLoginController` to integrate with your chosen service.

## Cleanup

Run the cleanup command to remove expired tokens:

```bash
# Remove tokens older than 24 hours (default)
php artisan passwordless:cleanup

# Remove tokens older than 48 hours
php artisan passwordless:cleanup --hours=48
```

Consider adding this to your scheduled tasks in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('passwordless:cleanup')->daily();
}
```

## Security Considerations

- Tokens are cryptographically secure (64 random characters)
- Tokens expire after 15 minutes
- Tokens can only be used once
- IP address and user agent are logged for security auditing
- Old tokens are automatically cleaned up when new ones are generated

## Testing

### Method 1: Using Log Driver (Easiest for Development)

1. Set `MAIL_MAILER=log` in your `.env` file
2. Clear config: `php artisan config:clear`
3. Visit `/passwordless-login` and submit the form
4. Check `storage/logs/laravel.log` for the login link
5. Copy and visit the link to complete login

### Method 2: Using MailHog (Local SMTP Testing)

1. Install MailHog: `brew install mailhog` (macOS) or download from GitHub
2. Start MailHog: `mailhog`
3. Set your `.env` mail configuration:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=127.0.0.1
   MAIL_PORT=1025
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   ```
4. Visit `/passwordless-login` and submit the form
5. Check MailHog web interface at http://127.0.0.1:8025
6. Click the login link in the email

### Method 3: Direct Testing with Tinker

```bash
php artisan tinker

# Generate a token and get the login URL
$token = App\Models\PasswordlessLoginToken::generateToken('test@example.com', 'email');
$loginUrl = route('passwordless.login', ['token' => $token->token]);
echo $loginUrl;

# Visit the URL in your browser
```

## Troubleshooting

### "Send login link doesn't work"

1. **Check mail configuration**: Make sure your mail driver is properly configured
2. **Check logs**: Look in `storage/logs/laravel.log` for any errors
3. **Check queue**: If using queued notifications, make sure `php artisan queue:work` is running
4. **Test mail directly**: Use tinker to test notification sending
5. **Check CSRF**: Make sure the form includes the CSRF token (automatic in Inertia)

## Production Setup

Before going to production:

1. Set up proper email sending (SMTP, Mailgun, etc.)
2. Implement SMS sending if using SMS login
3. Set up the cleanup command as a scheduled task
4. Consider rate limiting on the login link sending endpoint
5. Monitor logs for suspicious activity
