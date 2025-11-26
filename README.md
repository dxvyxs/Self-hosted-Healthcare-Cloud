A secure, role-based healthcare file storage system that provides different access levels for medical professionals. The system implements Zero Trust Network Access (ZTNA) principles and offers a secure platform for managing medical files.

Key Features:

- Role-Based Access Control (RBAC)
  - Doctor, Nurse, Receptionist, and Admin roles
  - Different dashboards and permissions for each role
  - Secure session management

- Secure Authentication
  - Password hashing using PHP's password_hash() and password_verify()
  - Session-based authentication
  - Automatic role-based redirection

- File Management
  - Secure file upload system
  - Categorized storage (General, Confidential, Health Data)
  - Role-specific file visibility
  - Download functionality

- Security Features
  - ZTNA implementation (IP-based access control)
  - Input validation and sanitization
  - Prepared statements to prevent SQL injection
  - Secure file upload handling

User Roles & Access:

Doctor (doctordesk.php)
- Advanced dashboard with file categorization
- View all uploaded files with category tags
- Download files directly
- Modern, professional UI

Nurse (nursedesk.php)
- Clean, medical-themed interface
- File upload and viewing capabilities
- Simple file management

Receptionist (receptionistdesk.php)
- Basic file management interface
- Upload and view personal files
- Streamlined dashboard

Admin (index.php)
- Administrative overview
- Access to all system files
- User management capabilities

Technical Architecture:

Database Structure
- Users Table:  (stores user credentials with hashed passwords)
- Role-specific Tables: doctor, nurse, receptionist (store uploaded file metadata)
- File Storage: Local uploads/ directory

Security Implementation
- ZTNA Guard: IP-based access restriction (ztnaguard.php)
- RBAC System: Role-based permissions (rbac.php)
- Session Management: Secure session handling across all pages
- Input Validation: Comprehensive form validation and SQL injection prevention

Setup & Installation:

1. Database Configuration
   - Create database mycloud
   - Update credentials in all PHP files:
     $conn = new mysqli("localhost", "phpuser", "<password>", "mycloud");
  
2. User Setup
   - Run hash.php to create initial users with hashed passwords
   - Default users: doctor, nurse, receptionist

3. Directory Permissions
   - Ensure uploads/ directory exists and is writable

Security Notes:

- Implements Zero Trust principles with IP-based access control
- Uses prepared statements to prevent SQL injection
- Passwords are securely hashed using PHP's password functions
- Session-based authentication with proper validation
- File uploads are restricted by user role

Access Flow:

1. User logs in through login.php
2. System validates credentials against table
3. User redirected to role-specific dashboard based on role field
4. Files are stored in role-specific database tables
5. ZTNA guard restricts access to authorized IP ranges only

This system provides a secure, scalable solution for healthcare file management with proper access controls and audit trails.
