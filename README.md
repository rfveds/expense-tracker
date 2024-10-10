## Expense Tracker

App that I am building to enhance my skills in backend development with PHP.

### Setup Instructions

1 . Clone the repository

```bash
git clone https://github.com/rfveds/expense-tracker.git
```

2. Change directory to the project folder

```bash
cd expense-tracker
```

3. Copy the `.env.example` file to `.env`

```bash
cp .env.example .env
```

4. Edit the `.env` file and set the environment variables
5. Setup the project with docker

```bash
make setup
```

### Technologies

- PHP 8.3
- Slim Framework 4
- Nginx
- Redis
- Doctrine ORM
- MySQL
- Docker
- PHPUnit
- JavaScript
- DataTables
- Bootstrap 5
- Chart.js

### Features

- [x] User Authentication
- [x] Form Validation with Factory Design Pattern
- [x] Session Based Authentication
- [x] CSRF Protection
- [x] XSS Protection
- [x] CRUD Actions
- [x] Ajax
- [x] DataTables & Pagination
- [x] Sorting & Filtering
- [x] Eager Loading with Doctrine to prevent N+1 Problem
- [x] Route Model Binding with Slim
- [x] Authorization with Doctrine Filters
- [x] Email Verification
- [x] Two-Factor Authentication
- [x] Caching with Redis
- [x] Rate Limiting
- [ ] Uploading Files to S3 Bucket

### To do

- [x] create category modal
- [x] when creating transaction, load categories with ajax
- [x] CategoryValidationRequest reformat
- [x] move file upload to separate service
- [x] delete receipts
- [x] import csv file and save as transaction
- [ ] import and store csv file
- [x] enable/disable 2fa in user profile
- [x] current route middleware
- [x] add data fixtures
- [ ] date picker for dashboard statistics
- [ ] better ui
- [ ] admin panel

### Bugs

- [ ] When editing transaction, current category is not selected