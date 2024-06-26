# Agouti

Agouti is an online library management application that allows users to interact with the library through a web platform. The project uses HTML, CSS, Bootstrap, JavaScript, PHP, and MySQL.

## Description

Agouti offers two main interfaces:

- **User Interface**: Allows users to register, view available books, borrow books, and manage their profiles.
- **Administrator Interface**: Allows administrators to manage categories, authors, books, readers, and loans.

## Project Structure

### User Section
- `index.php`: Main page.
- `signup.php`: Registration page.
- `check_availability.php`: Script to check username availability.
- `user-forgot-password.php`: User password recovery page.
- `adminlogin.php`: Administrator login page.
- `dashboard.php`: User dashboard.
- `my-profile.php`: User profile page.
- `issued-books.php`: Page for the books borrowed by the user.
- `change-password.php`: Password change page.

### Administrator Section
- `admin/dashboard.php`: Administrator dashboard.
- `admin/add-category.php`: Page to add a new category.
- `admin/manage-categories.php`: Page to manage categories.
- `admin/edit-category.php`: Page to edit a category.
- `admin/add-author.php`: Page to add a new author.
- `admin/manage-authors.php`: Page to manage authors.
- `admin/edit-author.php`: Page to edit an author.
- `admin/add-book.php`: Page to add a new book.
- `admin/manage-books.php`: Page to manage books.
- `admin/edit-book.php`: Page to edit a book.
- `admin/add-issue-book.php`: Page to add a new loan.
- `admin/manage-issued-books.php`: Page to manage loans.
- `admin/edit-issue-book.php`: Page to edit a loan.
- `admin/reg-readers.php`: Page to register new readers.
- `change-password.php`: Administrator password change page.
