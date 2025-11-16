# Football Field Reservation System

A comprehensive web application for managing football field reservations, tournaments, and promotional campaigns.

##  Overview

This application provides a complete solution for managing football field reservations with an intuitive interface for both clients and administrators. The system supports multiple field sizes, time slots, promotional campaigns, and tournament management with team participation.

##  Features

### Reservation Management
- **Booking Form** with the following options:
  - Date selection (date picker)
  - Time slots (16h-17h, 17h-18h, 18h-19h, etc.)
  - Field size selection (Mini foot, Medium field, Large field)
  - Field type (Natural grass, Artificial grass, Hard surface)
  - Additional services (Ball, Referee, Jerseys, Shower, Arbiter, etc.)
  - Client information (Name, First name, Email, Phone)
  - Special requests (Text area for comments)

- **Reservation Database** to store bookings and user information
- **Modification Support** - Reservations can be modified up to 48 hours before the match
- **Asynchronous Field Availability Display**
- **Automatic Invoice Generation** - PDF invoice generated immediately after reservation and available for download

### Tournament Management
- Create and manage tournaments
- Team registration and participation
- Tournament bracket management
- Match scheduling
- Results tracking

### Promotion System
- Create promotional campaigns for specific fields
- **Automated Email Notifications** sent to relevant clients when promotions are available
- Target specific customer segments
- Discount management

### User Roles
- **Client Interface**: Browse, book, and manage reservations
- **Admin Dashboard**: Full system management and analytics
- **Manager Portal**: Field and reservation oversight

##  Technology Stack

### Backend
- **PHP** - Server-side scripting
- **MVC Architecture** - Model-View-Controller design pattern
- **PDO** - Database abstraction layer
- **JWT (JSON Web Tokens)** - Secure authentication system

### Frontend
- **HTML5/CSS3** - Structure and styling
- **JavaScript** - Client-side interactivity
- **AJAX** - Asynchronous data loading

### Database
- **MySQL** - Relational database management

### Security
- JWT-based authentication (more secure than traditional sessions)
- Password hashing
- CSRF protection
- Input validation and sanitization

##  Project Structure

```
RESERVATION-TERRAINS/
├── App/
│   ├── App.php                 # Application bootstrap
│   ├── Routes.php              # Route definitions
│   └── config/                 # Configuration files
│       ├── config.php
│       ├── dbconfig.php
│       ├── load.php
│       ├── mailconfig.php
│       ├── siteconfig.php
│       └── tokenconfig.php
│
├── controllers/                # MVC Controllers
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── BookController.php
│   ├── ClientController.php
│   ├── DashboardController.php
│   ├── GerantController.php
│   ├── HomeController.php
│   ├── NewsletterController.php
│   ├── TerrainController.php
│   └── TournoiController.php
│
├── models/                     # Data models
│   ├── BookModel.php
│   ├── NewsletterModel.php
│   ├── OptionSupplementaireModel.php
│   ├── PromotionModel.php
│   ├── ReservationModel.php
│   ├── TerrainModel.php
│   ├── TournoiModel.php
│   └── UserModel.php
│
├── views/                      # View templates
│   ├── Admin/                  # Admin dashboard views
│   ├── Auth/                   # Authentication views
│   ├── Client/                 # Client interface views
│   ├── Errors/                 # Error pages
│   ├── Gerant/                 # Manager portal views
│   ├── Home/                   # Public homepage
│   ├── Newsletter/             # Newsletter management
│   ├── Terrain/                # Field management views
│   └── Tournoi/                # Tournament views
│
├── core/                       # Core application classes
│   ├── BaseController.php
│   └── BaseModel.php
│
├── database/                   # Database scripts
│   ├── PDODatabase.php
│   └── script.sql
│
├── public/                     # Public assets
│   ├── css/
│   ├── js/
│   └── images/
│
├── uploads/                    # User uploaded files
├── utils/                      # Utility functions
├── vendor/                     # Composer dependencies
│
├── .env                        # Environment variables
├── .env.example                # Environment template
├── composer.json               # PHP dependencies
├── composer.lock
└── README.md                   # This file
```

##  Installation

### Prerequisites
- PHP >= 7.4
- MySQL >= 5.7
- Composer
- Web server (Apache)

### Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd RESERVATION-TERRAINS
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```
   
   **Required packages:**
   ```bash
   # Install PHPMailer for email functionality
   composer require phpmailer/phpmailer
   
   # Install TCPDF for PDF invoice generation
   composer require tecnickcom/tcpdf
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` with your database credentials and configurations (see Configuration section below).

4. **Import database**
   ```bash
   mysql -u username -p database_name < database/script.sql
   ```

5. **Configure web server**
   - Point document root to `/public` directory
   - Enable URL rewriting (mod_rewrite for Apache)

##  Configuration

Create a `.env` file in the root directory with the following configuration:

```properties
# Site Configuration
SITE_URL=http://localhost/reservation-terrains/public
SITE_NAME=FootBooking
MAIL_FROM=your-email@example.com
SESSION_LIFETIME=3600
TOKEN_EXPIRY=86400
TIMEZONE=Africa/Casablanca
DEBUG_MODE=true

# RECAPTCHA configuration
RECAPTCHA_SITE_KEY=your_recaptcha_site_key_here
RECAPTCHA_SECRET_KEY=your_recaptcha_secret_key_here

# DB Config
DB_HOST=localhost
DB_NAME=gestion_terrains
DB_USER=root
DB_PASS=

# Mail Config
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@footbooking.com
MAIL_FROM_NAME=FootBooking

# JWT Settings
JWT_SECRET_KEY=generate_your_own_secret_key_here
JWT_EXPIRY_MINUTES=3600
```

**Important Security Notes:**
- **Never commit the `.env` file to version control**
- Replace all placeholder values with your actual credentials
- Generate a strong, unique JWT_SECRET_KEY (use random 64-character string)
- For Gmail: Use App Password instead of your actual password
- Update RECAPTCHA keys with your own from [Google reCAPTCHA](https://www.google.com/recaptcha)
- Set DEBUG_MODE=false in production environment
- Use strong database passwords in production

##  Usage

### For Clients
1. **Register/Login** to the system
2. **Browse available fields** and time slots
3. **Create a reservation** by filling out the booking form
4. **Manage bookings** - View, modify (up to 48h before), or cancel
5. **Join tournaments** with your team
6. **Receive promotional emails** for special offers

### For Administrators
1. **Login to admin dashboard**
2. **Manage fields** - Add, edit, or remove fields
3. **View all reservations** with filtering options
4. **Create promotions** and send targeted emails
5. **Organize tournaments** and manage participation
6. **Generate reports** and invoices

### For Managers (Gerant)
1. **Access manager portal**
2. **Oversee field availability**
3. **Manage field-specific reservations**
4. **Handle field options and services**



##  Security

### JWT Authentication
- Tokens are issued upon successful login
- All protected routes require valid JWT in Authorization header
- Tokens expire after configured time period
- More secure than traditional session-based authentication

### Best Practices Implemented
- Password hashing using bcrypt
- SQL injection prevention via PDO prepared statements
- XSS protection through output encoding
- CSRF token validation for forms
- Input validation and sanitization
- Secure headers configuration

##  Team

**Development Team:**
- **ESSEBAIY Aya** - Project Manager
- **NYIRENDA Amos** - Backend Lead
- **ELMESSAOUDI Fatima** - Frontend Lead
- **CHOUHE Jihane** - Developer
- **BENKRIMEN Wiam** - Developer

