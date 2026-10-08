# CampusCart - Student Marketplace Full Project

CampusCart is a simple student-to-student marketplace built with PHP, MySQL, HTML, CSS and JavaScript.

## Main features
- Student registration and login using PHP sessions
- Upload product image, title, description, category and price
- Product image is stored in `uploads/`
- Browse/search products
- Product detail page
- Contact seller form
- Seller receives messages in their account
- Seller can edit/delete their own products
- MySQL database with CRUD using PDO prepared statements
- Form validation and basic string manipulation
- Responsive CSS Grid/Flexbox
- JavaScript DOM manipulation, events, array functions and Fetch API
- Separate practical demonstration pages for the Web Technology syllabus
- React and Node/Express mini demos are included

## Requirements
- XAMPP (Apache + MySQL) for the main PHP application
- Optional Node.js for the Node/Express practical
- Optional modern browser

## Main setup
1. Copy the entire `CampusCart_Full_Project` folder to:
   `C:\xampp\htdocs\CampusCart_Full_Project`
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin:
   `http://localhost/phpmyadmin`
4. Import:
   `database/campuscart.sql`
5. Open:
   `http://localhost/CampusCart_Full_Project/`
6. Register two student accounts.
7. Log in as Student A and add a product with an image.
8. Log in as Student B and contact Student A from the product page.
9. Log back in as Student A and open "Messages".

## MySQL configuration
Default `includes/db.php` uses:
- host: localhost
- database: campuscart
- user: root
- password: empty

If your MySQL root account has a password, edit `includes/db.php`.

## Practical pages
Open `/practicals/` from the main website. Each page demonstrates a syllabus item.

1. `01-html-basics.php` - headings, lists, image
2. `02-semantic.php` - semantic HTML
3. `03-css.php` - inline, internal, external CSS
4. `04-responsive.php` - Flexbox, Grid, media queries
5. `05-positioning.php` - CSS positioning/properties
6. `06-javascript.php` - JS file, events, array functions
7. `07-js-validation.php` - frontend functionality and validation
8. `08-react/index.html` - React SPA functional component + JSX
9. `09-react/index.html` - React props/state/hooks/events/conditional rendering
10. `10-fetch-json.php` - Fetch + JSON API
11. `11-dom.php` - DOM manipulation and events
12. `12-php.php` - PHP form, validation, strings, session
13. `13-php-mysql.php` - PHP + MySQL CRUD
14. `14-node/index.html` - Node/Express test page
16. `16-course-project.php` - project explanation

## Node/Express practical
From `practicals/14-node`:
```bash
npm install
npm start
```
Then open `http://localhost:3000`.

## Notes
This is an academic/demo project. For production deployment, add stronger authentication, CSRF protection, MIME/type validation, image size limits, authorization checks, and secure environment configuration.
