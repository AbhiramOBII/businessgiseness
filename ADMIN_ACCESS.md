# Business Giseness Admin Panel Access

## Default Admin User

A default admin user has been created for you:

- **Email**: `admin@businessgiseness.com`
- **Password**: `BusinessGiseness@2024`
- **Name**: Abhiram Chandramohan

## How to Access Admin Panel

1. **Start the development server**:
   ```bash
   php artisan serve
   ```

2. **Visit the login page**:
   ```
   http://localhost:8000/login
   ```

3. **Login with admin credentials**:
   - Email: `admin@businessgiseness.com`
   - Password: `BusinessGiseness@2024`

4. **Access admin dashboard**:
   - Click the "Admin" link in the navigation bar
   - Or visit directly: `http://localhost:8000/admin/dashboard`

## Admin Panel Features

### Dashboard (`/admin/dashboard`)
- Website statistics overview
- Recent user activity
- Quick action buttons
- System status indicators

### Content Management (`/admin/content`)
- Manage website pages (Home, About)
- Edit content sections
- Preview changes
- Coming soon: Visual editor, image management, SEO tools

## Creating Additional Admin Users

### Method 1: Using Artisan Command (Recommended)
```bash
php artisan admin:create-user
```

This will prompt you for:
- Admin name
- Email address
- Password

### Method 2: Using Command Line Options
```bash
php artisan admin:create-user --name="John Doe" --email="john@example.com" --password="SecurePassword123"
```

### Method 3: Using Database Seeder
```bash
php artisan db:seed --class=AdminUserSeeder
```

## Security Notes

1. **Change the default password** after first login
2. **Use strong passwords** for all admin accounts
3. **Limit admin access** to trusted users only
4. **Regular security updates** recommended

## Troubleshooting

### Cannot Access Admin Panel
- Ensure you're logged in with a valid user account
- Check that the server is running (`php artisan serve`)
- Clear cache if needed: `php artisan cache:clear`

### Forgot Admin Password
1. Reset via the "Forgot Password" link on login page
2. Or create a new admin user using the Artisan command

### Database Issues
- Run migrations: `php artisan migrate`
- Seed admin user: `php artisan db:seed --class=AdminUserSeeder`

## Support

For technical support or questions about the admin panel, contact the development team.
