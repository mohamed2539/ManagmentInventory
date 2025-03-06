# Material Management System

A comprehensive system for managing materials, suppliers, and inventory across multiple branches.

## Features

- Material management with unique codes
- Supplier management
- Branch management
- User management with roles
- Transaction tracking
- Audit logging
- Live search functionality
- Reporting system

## Requirements

- PHP >= 7.4
- MySQL >= 5.7
- Composer
- Web server (Apache/Nginx)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/NMaterailManegmentT.git
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Configure your database in `.env` file

5. Import database schema:
```bash
mysql -u root -p < config/database.sql
```

6. Set appropriate permissions:
```bash
chmod -R 755 .
chmod -R 777 storage/
```

## Directory Structure

```
NMaterailManegmentT/
├── app/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   └── core/
├── config/
├── public/
│   ├── assets/
│   └── index.php
├── storage/
├── vendor/
├── .env
├── .env.example
├── composer.json
└── README.md
```

## Usage

1. Access the application through your web browser:
```
http://localhost/NMaterailManegmentT/public/
```

2. Login with default admin credentials:
- Username: admin
- Password: admin123

3. Change the default password after first login.

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License. 