# CampusCart Project Report

## Abstract
CampusCart is a campus-only marketplace concept where students can list products and other students can discover and contact them.

## User flow
Register -> Login -> Upload Product -> Browse -> Product Details -> Contact Seller -> Seller reads Message.

## Database
- users: student accounts
- products: listings and images
- messages: buyer-to-seller communication

## CRUD
Create: add product / send message
Read: product list / product detail / messages
Update: user information can be extended in this schema
Delete: seller deletes own product

## Security used in the academic version
- Password hashing with `password_hash`
- Password verification with `password_verify`
- PDO prepared statements
- HTML escaping with `htmlspecialchars`
- Session-based login
- File extension and size checks for uploaded images

## Future improvements
- College email verification
- Category filters
- Location/campus filtering
- Image MIME verification
- CSRF protection
- Pagination
- Admin moderation
- Report listing feature
