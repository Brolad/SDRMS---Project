Automated Student Disciplinary Record Management System (SDRMS) - Documentation Group 2

# Group Members:

1. Ajibola Oluwasegun Jacobs -  eL/24/0212
2. Olufotebi Ifeoluwa Ezekiel - eL/25/0097
3. Ogunniyi Boluwatife Inioluwa - eL/24/0233
4. Ojo Iyabo - eL/25/0176
5. Francis Inioluwa Daniella - eL/24/0244
6. Uwem Victoria Justine - eL/24/0306
7. Olakanmi Mayowa Emmanuel - eL/25/0228
8. Shaibu Amade Samuel - eL/24/0190
9. Ajayi Emmanuel AnuOluwapo - eL/25/0108

# 1. System Overview

The **Student Disciplinary Record Management System (SDRMS)** is a web-based tool that helps universities track and manage student behavior digitally. It replaces paper files with a central database where faculty can report issues and students can check their disciplinary status.

The system uses a **Deductive Point Model**, where student behavior is tracked using points to ensure fairness and quick action when needed.

# 2. Problem Statement

Manual disciplinary processes in universities face several key challenges:

- **Data Fragmentation:** Records are stored in different departments, making it hard to get a complete picture of a student's behavior.
- **Lack of Transparency:** Students often don't know their disciplinary standing until they face serious consequences.
- **Slow Intervention:** Delayed reporting means behavioral patterns aren't caught early enough.
- **Inconsistency:** Without a centralized system, similar offenses may result in different punishments.

# 3. Refined Features & Dashboard Modules

The system has a sleek command-center dashboard with these key modules:

## 3.1 Staff Command Center

- **Live Registry Search:** A JavaScript-powered filter allows staff to search the student directory by Name or Matric Number instantly without page refreshes.
- **Institutional Intelligence Cards:** Real-time statistics displayed at the top of the dashboard, including:
    - **Total Enrolled Students:** Direct count from the database.
    - **System Average Score:** Calculated via SQL `AVG()` to monitor school-wide behavior.
    - **Critical Status Count:** Highlights the number of students currently in the "Warning" or "Expelled" zones.
- **Personal Administrative Log:** A dedicated "Recent Activity" section that tracks the last 5 actions taken specifically by the logged-in staff member, ensuring personal accountability.

## 3.2 Student Portal

- **Behavioral Ledger:** A detailed view of the student's behavioral rating and history, allowing them to see exactly when and why points were adjusted (This portal is **view-only** for the students to check their records).
- **Dynamic Status Labels:** A logic-based system that assigns labels based on point thresholds:
    - **Model Student:** 90+ Points
    - **Good Standing:** 60 - 89 Points
    - **Warning Phase:** 40 - 59 Points
    - **Probational:** 1 - 39 Points
    - **Expelled:** 0 Points (triggers a red, pulsing notification and grayscale profile locking)

# **4. Behavioral Logic**

The system utilizes a Deductive Point Model where students begin with a baseline of 100
points. Updates are processed through a Net-Change Calculation engine:

| Infraction Category | Severity | Point Deduction | Example |
| --- | --- | --- | --- |
| Category C | Minor | -10 Points | Dress code violation, Curfew violation |
| Category B | Major | -30 Points | Theft, Disrespect to faculty |
| Category A | Critical | -50 to -100 Points | Examination malpractice, Assault |
| Commendation | Positive | Variable (+) | Exceptional community service, academic integrity |

**Net-Change Logic:** Staff can add and subtract points in one form, and the system automatically calculates the final change on the student’s record.

**Expulsion Threshold:** Students start with **100 Points**. If their balance drops to **0 or below**, the system triggers their status to show expelled.

For this prototype we will be using Plain Text Passwords, but in a real university system, you would use `password_hash()` for security.

## 4I) Conceptual Model (ERD)

This is the high-level view diagram:

![ChatGPT Image May 5, 2026, 04_46_13 PM.png](attachment:318e67f9-7411-43d5-9698-27624a1214e2:ChatGPT_Image_May_5_2026_04_46_13_PM.png)

## 4II) The Logical Model (Table Structures)

This is where we define the tables and how they link:

![ChatGPT Image May 5, 2026, 05_20_53 PM.png](attachment:008a4f19-8dc7-4b2f-a8f5-bd07398f6a4b:ChatGPT_Image_May_5_2026_05_20_53_PM.png)

## 4III) UI Wireframing (The Visual Layout)

These are "Mockups" (sketches) of the interface:

![ChatGPT Image May 5, 2026, 04_57_42 PM.png](attachment:1ecb7041-1d8c-43f6-98c4-efc3b413c1f4:0b8b6429-f679-4955-9aa8-960be9bba889.png)

# 5. Technical Stack

- **Frontend:** HTML5, JavaScript, CSS3 (Tailwind CSS) - for a responsive user interface.
- **Backend:** PHP for server-side logic and session management.
- **Database:** Database: MySQL for relational data storage.
- **Environment:** XAMPP / WAMP (Apache Server).

# 6. Visual Design & User Experience

The system uses a custom corporate design style to create a premium, institutional
look and feel.

- **Typography:** Plus Jakarta Sans — a clean, modern font that improves readability and gives the system a professional software feel.
- **Aesthetic (Glass & Slate):** A glassmorphism style using backdrop blur, deep navy (Slate-900) accents, and a subtle radial dot-grid background to create a premium institutional look.
- **Visual Feedback:** Status colors shift from green to red as behavior scores drop, and warning states use subtle pulsing animations to clearly show when a student is in a high-risk disciplinary zone.

# 7. Database Architecture

The logical model consists of three primary tables linked by foreign keys:

- **Students:** Stores core student details like name and matric number, plus the behavior score (default **100 points**) and current disciplinary status.
- **Staff:** Stores staff profiles (e.g., name/ID), department, and role/permission level used for access control and accountability.
- **Infractions:** Stores disciplinary events (what happened, when, category/severity, and points deducted/added) and links each record to the affected student and the staff member who authorized it.
