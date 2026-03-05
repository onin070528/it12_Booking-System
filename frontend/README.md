# Frontend

All frontend code lives here, separated from the backend PHP logic.

## Structure

```
frontend/
├── views/          # Blade templates (.blade.php)
│   ├── admin/      # Admin panel views
│   ├── auth/       # Authentication views (login, register, etc.)
│   ├── booking/    # Booking creation views
│   ├── components/ # Reusable Blade components
│   ├── emails/     # Email templates
│   ├── landing page/  # Public landing page
│   ├── layouts/    # User layout partials (header, sidebar)
│   ├── layouts1/   # App/Guest layout wrappers
│   ├── messages/   # Chat/messaging views
│   ├── notifications/ # Notification views
│   ├── payments/   # Payment views
│   └── profile/    # User profile views
├── js/             # JavaScript source files
│   ├── app.js      # Main entry point (Alpine.js, FullCalendar)
│   ├── bootstrap.js # Axios setup
│   └── landing.js  # Landing page scripts
└── css/            # CSS source files
    ├── app.css     # Main stylesheet (Tailwind)
    └── landing.css # Landing page styles
```

## Notes

- Static assets (images, public JS) remain in `public/` since they must be web-accessible
- Compiled assets are output to `public/build/` by Vite
- View path is configured in `config/view.php`
- Vite entry points are configured in `vite.config.js`
