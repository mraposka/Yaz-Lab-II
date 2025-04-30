# Yaz-Lab-II Project

A web application built using CodeIgniter 3 PHP framework.

## 🚀 Features

- MVC Architecture
- Built with CodeIgniter 3
- RDS PostgreSQL database 

## 👋 Prerequisites

- PHP 8.0.30 Apache Web Server

## 🛠️ Installation

1. Clone this repository to your local machine:
```bash
git clone https://github.com/mraposka/Yaz-Lab-II
```

2. Extract the project files and rename the extracted folder to `yazlab`.

3. Place the `yazlab` folder in your web server's root directory (e.g., `htdocs` for XAMPP).

4. Configure your database settings in:
```
application/config/database.php
```
Update the `hostname` field with the URL provided by your project [administrator](mailto:kadircankinsiz@gmail.com).

5. Configure your base URL in:
```
application/config/config.php
```

6. Access the application through your web browser:
```
http://localhost/yazlab
```

## 💁 Project Structure

```
yazlab/
├── application/          # Application directory
│   ├── config/          # Configuration files
│   ├── controllers/     # Controllers
│   ├── models/         # Models
│   ├── views/          # Views
│   └── ...
├── system/              # CodeIgniter system files
├── .htaccess           # Apache configuration
├── composer.json       # Composer dependencies
└── index.php          # Entry point
```

## 🔧 Development

The project follows CodeIgniter's MVC pattern:
- Controllers: Handle user requests
- Models: Manage data and business logic
- Views: Present data to users

## 🥾 Testing

Run PHPUnit tests using:
```bash
composer test:coverage
```

## 📌 Project Management

Track the project's progress on **Trello**:  

[![Trello Board](https://img.shields.io/badge/Trello-Yaz--Lab--II-blue?logo=trello)](https://trello.com/invite/b/67c4b85607bb6c186736c6fc/ATTI600973164aff15eca1d597c0419f6303C9EF7483/yaz-lab-ii)


## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👥 Contributors

- [Abdulkadir Can Kinsiz]
- [Şevval Zeynep Ayar]
- [Sude Deniz Suvar]

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## 📞 Support

For support, please contact the project [administrator](mailto:kadircankinsiz@gmail.com).

