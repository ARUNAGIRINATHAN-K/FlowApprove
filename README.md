<div align="center">

<img alt="flowapprove-banner" src="flowapprove-banner.svg" />

*A modern, platform-agnostic system for seamless approval workflows*

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://javascript.com)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://html.spec.whatwg.org)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://www.w3.org/Style/CSS)

[![License](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](CONTRIBUTING.md)
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg?style=flat-square)](https://github.com/yourusername/workflow-system/graphs/commit-activity)

<!-- [Features](#-features) • [Installation](#-installation) • [Documentation](#-documentation) • [Demo](#-demo) • [Contributing](#-contributing) --->

</div>

---

##  Overview

A powerful, open-source alternative to **Monday.com**, **KissFlow**, **Jira Service Desk**, and **Zoho Creator** — built for companies that want full control over their approval workflows without vendor lock-in.

###  Why Choose This System?

- ✅ **100% Open Source** - Deploy anywhere, modify freely
- ✅ **Self-Hosted or Cloud** - Your data, your infrastructure
- ✅ **Zero Licensing Costs** - No per-user fees or hidden charges
- ✅ **Fully Customizable** - Adapt to any business process
- ✅ **Multi-Level Approvals** - Complex workflows made simple
- ✅ **Built with Standard Tech** - PHP, MySQL, Bootstrap

---

##  Features

###  Core Capabilities

| Module | Description |
|--------|-------------|
| **📝 Request Management** | Submit leave, purchase orders, asset requests, IT support, and custom forms |
| **⚡ Workflow Engine** | Multi-level approval chains with dynamic routing and auto-approval rules |
| **🔔 Smart Notifications** | Email, SMS (Twilio), Slack, Teams, and WhatsApp alerts |
| **📊 Analytics Dashboard** | Real-time insights, bottleneck detection, and role-based views |
| **🔐 RBAC System** | Employee, Manager, Admin, and custom roles with granular permissions |
| **📜 Audit Logging** | Complete action history with timestamps and user tracking |

###  Visual Workflow Designer

Create approval chains effortlessly:

```
📋 Form Submission → 👤 Manager Review → 👔 HR Approval → ✅ Auto-Close
```

###  Role-Based Dashboards

**Employee View**: Track your requests  
**Manager View**: Approve pending items  
**Admin View**: Configure workflows and manage users

---

##  Tech Stack

<div align="center">

| Layer | Technologies |
|-------|-------------|
| **Frontend** | HTML5, CSS3, Bootstrap 5, Vanilla JavaScript |
| **Backend** | PHP (Core/OOP) |
| **Database** | MySQL |
| **Notifications** | PHPMailer, Twilio (optional) |
| **Charts** | Chart.js (optional) |

</div>

---

##  Quick Start

### Prerequisites

```bash
PHP >= 7.4
MySQL >= 5.7
Apache/Nginx Web Server
```

### Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/workflow-system.git

# Navigate to project directory
cd workflow-system

# Import database
mysql -u root -p workflow_db < database/schema.sql

# Configure database connection
cp config/database.example.php config/database.php
nano config/database.php

# Start development server
php -S localhost:8000
```

Visit `http://localhost:8000` and login with default credentials:
- **Admin**: admin@company.com / admin123
- **Manager**: manager@company.com / manager123

---

##  Database Architecture

### Key Tables

#### `users`
Stores user accounts with roles and departments

#### `requests`
Central table for all approval requests with status tracking

#### `approval_levels`
Defines workflow stages and approver assignments

#### `approval_actions`
Logs every approval/rejection with comments and timestamps

#### `audit_logs`
Complete action history for compliance and debugging

[View Full Schema](database/db.sql)

---

##  Usage Examples

### Submitting a Request

```php
// Employee submits leave request
$request = [
    'type' => 'leave',
    'title' => 'Annual Leave',
    'description' => '5 days vacation',
    'start_date' => '2025-12-01',
    'end_date' => '2025-12-05'
];
```

### Configuring Workflows

```php
// Admin creates 3-level approval chain
Level 1: Direct Manager
Level 2: Department Head  
Level 3: HR Director
```

### Setting Notifications

```php
// Trigger email on status change
send_notification([
    'recipient' => $approver_email,
    'subject' => 'New Request Awaiting Approval',
    'template' => 'approval_needed'
]);
```

---

## 📊 Module Breakdown

<details>
<summary><b> Authentication Module</b></summary>

- Session-based login system
- Password hashing with bcrypt
- Role-based access control
- Password reset via email OTP
</details>

<details>
<summary><b> Request Submission Module</b></summary>

- Dynamic form builder
- File attachment support
- Auto-routing to approvers
- Request categorization
</details>

<details>
<summary><b> Workflow Engine</b></summary>

- Multi-level approval chains
- Conditional routing logic
- SLA tracking and alerts
- Auto-approval rules
</details>

<details>
<summary><b> Notification System</b></summary>

- Email (PHPMailer)
- SMS (Twilio integration)
- In-app notifications
- Webhook support for Slack/Teams
</details>

<details>
<summary><b> Dashboard & Reporting</b></summary>

- Real-time request status
- Approval bottleneck analysis
- Custom date range filtering
- Export to CSV/PDF
</details>

<details>
<summary><b> Audit Logging</b></summary>

- Immutable action logs
- User activity tracking
- Compliance reporting
- Tamper-proof timestamps
</details>

---

<!--
## 🎨 Screenshots

| Employee Dashboard | Manager Approvals | Admin Configuration |
|-------------------|-------------------|---------------------|
| ![Employee](https://via.placeholder.com/300x200/4A90E2/FFFFFF?text=Employee+View) | ![Manager](https://via.placeholder.com/300x200/50C878/FFFFFF?text=Manager+View) | ![Admin](https://via.placeholder.com/300x200/FF6B6B/FFFFFF?text=Admin+Panel) |

--->

---

## 🤝 Contributing

We welcome contributions! Here's how you can help:

1.  Fork the repository
2.  Create a feature branch (`git checkout -b feature/AmazingFeature`)
3.  Commit changes (`git commit -m 'Add AmazingFeature'`)
4.  Push to branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

---

##  Roadmap

- [ ] Mobile app (React Native)
- [ ] REST API with JWT authentication
- [ ] Docker deployment support
- [ ] LDAP/Active Directory integration
- [ ] Advanced analytics with ML insights
- [ ] Multi-language support

---

<div align="center">

**⭐ Star this repo if you find it useful!**

Made by the [ARUNAGIRINATHAN](https://github.com/ARUNAGIRINATHAN-K)

[Report Bug](https://github.com/ARUNAGIRINATHAN-K/FlowApprove/issues) • [Request Feature](https://github.com/ARUNAGIRINATHAN-K/FlowApprove/issues) • [Join Discussion](https://github.com/ARUNAGIRINATHAN-K/FlowApprove/discussions)

</div>
