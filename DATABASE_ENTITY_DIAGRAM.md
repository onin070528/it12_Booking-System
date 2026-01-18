# Booking Management System - Database Entity Relationship Diagram

## 📊 Visual Entity Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                    BOOKING MANAGEMENT SYSTEM                                         │
│                                   Entity Relationship Diagram                                        │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

                                        ┌──────────────────────┐
                                        │        USERS         │
                                        ├──────────────────────┤
                                        │ PK: user_id          │
                                        │    first_name        │
                                        │    last_name         │
                                        │    middle_initial    │
                                        │    name              │
                                        │    email             │
                                        │    password          │
                                        │    phone             │
                                        │    role              │
                                        │    account_status    │
                                        │    approved_at       │
                                        │ FK: approved_by ─────┼──┐ (self-reference)
                                        │    rejection_reason  │  │
                                        │    email_verified_at │  │
                                        │    remember_token    │  │
                                        │    archived_at       │  │
                                        │    created_at        │  │
                                        │    updated_at        │  │
                                        └──────────┬───────────┘  │
                                                   │              │
                                                   │◄─────────────┘
                                                   │
            ┌──────────────────────────────────────┼──────────────────────────────────────┐
            │                                      │                                       │
            │                                      │                                       │
            ▼                                      ▼                                       ▼
┌───────────────────────┐              ┌───────────────────────┐              ┌───────────────────────┐
│      BOOKINGS         │              │     NOTIFICATIONS     │              │       MESSAGES        │
├───────────────────────┤              ├───────────────────────┤              ├───────────────────────┤
│ PK: booking_id        │              │ PK: notification_id   │              │ PK: message_id        │
│ FK: user_id ──────────┼──────────────│ FK: user_id ──────────┼──────────────│ FK: sender_id ────────┤
│    event_type         │              │    type               │              │ FK: receiver_id ──────┤
│    event_date         │              │    notifiable_type    │              │    message            │
│    event_time         │              │    notifiable_id      │              │    read               │
│    location           │              │    message            │              │    read_at            │
│    description        │              │    read               │              │    created_at         │
│    event_details      │              │    data               │              │    updated_at         │
│    total_amount       │              │    created_at         │              └───────────────────────┘
│    status             │              │    updated_at         │
│    communication_method│             └───────────────────────┘
│    meetup_date        │
│    meetup_time        │
│    admin_viewed_at    │
│    archived_at        │
│    created_at         │
│    updated_at         │
└───────────┬───────────┘
            │
            │
            ├────────────────────────────────────────┐
            │                                        │
            ▼                                        ▼
┌───────────────────────┐              ┌─────────────────────────────┐
│       PAYMENTS        │              │    BOOKING_INVENTORY        │
├───────────────────────┤              │      (Pivot Table)          │
│ PK: payment_id        │              ├─────────────────────────────┤
│ FK: booking_id ───────┤              │ PK: booking_inventory_id    │
│ FK: user_id ──────────┤              │ FK: booking_id ─────────────┤
│    amount             │              │ FK: inventory_id ───────────┼─────────┐
│    currency           │              │    quantity_assigned        │         │
│    status             │              │    quantity_returned        │         │
│    payment_method     │              │    quantity_damaged         │         │
│    description        │              │    damage_notes             │         │
│    reference_number   │              │    status                   │         │
│    payment_screenshot │              │    assigned_at              │         │
│    paid_at            │              │    returned_at              │         │
│    created_at         │              │    created_at               │         │
│    updated_at         │              │    updated_at               │         │
└───────────────────────┘              └─────────────────────────────┘         │
                                                                               │
                                                                               │
                                                   ┌───────────────────────────┘
                                                   │
                                                   ▼
                                       ┌───────────────────────┐
                                       │      INVENTORIES      │
                                       ├───────────────────────┤
                                       │ PK: inventory_id      │
                                       │    item_name          │
                                       │    category           │
                                       │    unit               │
                                       │    stock              │
                                       │    status             │
                                       │    archived_at        │
                                       │    created_at         │
                                       │    updated_at         │
                                       └───────────────────────┘


                    ┌───────────────────────┐
                    │        EVENTS         │
                    │    (Standalone)       │
                    ├───────────────────────┤
                    │ PK: event_id          │
                    │    title              │
                    │    description        │
                    │    start              │
                    │    end                │
                    │    created_at         │
                    │    updated_at         │
                    └───────────────────────┘
```

---

## 📋 Entity Details

### 1. USERS (users)

| Column              | Type      | Constraints                 | Description                       |
| ------------------- | --------- | --------------------------- | --------------------------------- |
| `user_id`           | BIGINT    | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier            |
| `first_name`        | VARCHAR   | NULLABLE                    | User's first name                 |
| `last_name`         | VARCHAR   | NULLABLE                    | User's last name                  |
| `middle_initial`    | VARCHAR   | NULLABLE                    | User's middle initial             |
| `name`              | VARCHAR   | NOT NULL                    | Full name (legacy)                |
| `email`             | VARCHAR   | UNIQUE, NOT NULL            | Email address                     |
| `password`          | VARCHAR   | NOT NULL                    | Hashed password                   |
| `phone`             | VARCHAR   | NULLABLE                    | Phone number                      |
| `role`              | ENUM      | DEFAULT 'user'              | 'admin' or 'user'                 |
| `account_status`    | ENUM      | DEFAULT 'pending'           | 'pending', 'approved', 'rejected' |
| `approved_at`       | TIMESTAMP | NULLABLE                    | When account was approved         |
| `approved_by`       | BIGINT    | FK → users.user_id          | Admin who approved                |
| `rejection_reason`  | TEXT      | NULLABLE                    | Reason for rejection              |
| `email_verified_at` | TIMESTAMP | NULLABLE                    | Email verification date           |
| `remember_token`    | VARCHAR   | NULLABLE                    | Session remember token            |
| `archived_at`       | TIMESTAMP | NULLABLE                    | Soft delete timestamp             |
| `created_at`        | TIMESTAMP | AUTO                        | Creation timestamp                |
| `updated_at`        | TIMESTAMP | AUTO                        | Last update timestamp             |

---

### 2. BOOKINGS (bookings)

| Column                 | Type          | Constraints                 | Description                                                                                                 |
| ---------------------- | ------------- | --------------------------- | ----------------------------------------------------------------------------------------------------------- |
| `booking_id`           | BIGINT        | PRIMARY KEY, AUTO_INCREMENT | Unique booking identifier                                                                                   |
| `user_id`              | BIGINT        | FK → users.user_id          | Customer who made booking                                                                                   |
| `event_type`           | VARCHAR       | NOT NULL                    | Type of event                                                                                               |
| `event_date`           | DATE          | NOT NULL                    | Date of the event                                                                                           |
| `event_time`           | TIME          | NOT NULL                    | Time of the event                                                                                           |
| `location`             | VARCHAR       | NOT NULL                    | Event location                                                                                              |
| `description`          | TEXT          | NULLABLE                    | Event description                                                                                           |
| `event_details`        | JSON          | NULLABLE                    | Additional event details                                                                                    |
| `total_amount`         | DECIMAL(10,2) | NOT NULL                    | Total booking cost                                                                                          |
| `status`               | ENUM          | DEFAULT 'pending'           | 'pending', 'approved', 'rejected', 'pending_payment', 'partial_payment', 'design', 'completed', 'cancelled' |
| `communication_method` | VARCHAR       | NULLABLE                    | Preferred communication                                                                                     |
| `meetup_date`          | DATE          | NULLABLE                    | Scheduled meetup date                                                                                       |
| `meetup_time`          | TIME          | NULLABLE                    | Scheduled meetup time                                                                                       |
| `admin_viewed_at`      | TIMESTAMP     | NULLABLE                    | When admin viewed                                                                                           |
| `archived_at`          | TIMESTAMP     | NULLABLE                    | Soft delete timestamp                                                                                       |
| `created_at`           | TIMESTAMP     | AUTO                        | Creation timestamp                                                                                          |
| `updated_at`           | TIMESTAMP     | AUTO                        | Last update timestamp                                                                                       |

---

### 3. PAYMENTS (payments)

| Column               | Type          | Constraints                 | Description                                                |
| -------------------- | ------------- | --------------------------- | ---------------------------------------------------------- |
| `payment_id`         | BIGINT        | PRIMARY KEY, AUTO_INCREMENT | Unique payment identifier                                  |
| `booking_id`         | BIGINT        | FK → bookings.booking_id    | Associated booking                                         |
| `user_id`            | BIGINT        | FK → users.user_id          | User who made payment                                      |
| `amount`             | DECIMAL(10,2) | NOT NULL                    | Payment amount                                             |
| `currency`           | VARCHAR       | DEFAULT 'PHP'               | Currency code                                              |
| `status`             | ENUM          | DEFAULT 'pending'           | 'pending', 'paid', 'partial_payment', 'failed', 'refunded' |
| `payment_method`     | VARCHAR       | NULLABLE                    | Payment method used                                        |
| `description`        | TEXT          | NULLABLE                    | Payment description                                        |
| `reference_number`   | VARCHAR       | NULLABLE                    | External reference                                         |
| `payment_screenshot` | VARCHAR       | NULLABLE                    | Proof of payment image                                     |
| `paid_at`            | TIMESTAMP     | NULLABLE                    | When payment was made                                      |
| `created_at`         | TIMESTAMP     | AUTO                        | Creation timestamp                                         |
| `updated_at`         | TIMESTAMP     | AUTO                        | Last update timestamp                                      |

---

### 4. INVENTORIES (inventories)

| Column         | Type          | Constraints                 | Description                                    |
| -------------- | ------------- | --------------------------- | ---------------------------------------------- |
| `inventory_id` | BIGINT        | PRIMARY KEY, AUTO_INCREMENT | Unique inventory identifier                    |
| `item_name`    | VARCHAR       | NOT NULL                    | Name of inventory item                         |
| `category`     | VARCHAR       | NOT NULL                    | Item category                                  |
| `unit`         | VARCHAR       | DEFAULT 'pcs'               | Unit of measurement (pcs, meters, yards, etc.) |
| `stock`        | DECIMAL(10,2) | DEFAULT 0                   | Current stock quantity                         |
| `status`       | ENUM          | NULLABLE                    | 'In Stock', 'Low Stock', 'Out of Stock'        |
| `archived_at`  | TIMESTAMP     | NULLABLE                    | Soft delete timestamp                          |
| `created_at`   | TIMESTAMP     | AUTO                        | Creation timestamp                             |
| `updated_at`   | TIMESTAMP     | AUTO                        | Last update timestamp                          |

---

### 5. BOOKING_INVENTORY (booking_inventory) - Pivot Table

| Column                 | Type          | Constraints                   | Description                                                       |
| ---------------------- | ------------- | ----------------------------- | ----------------------------------------------------------------- |
| `booking_inventory_id` | BIGINT        | PRIMARY KEY, AUTO_INCREMENT   | Unique assignment identifier                                      |
| `booking_id`           | BIGINT        | FK → bookings.booking_id      | Associated booking                                                |
| `inventory_id`         | BIGINT        | FK → inventories.inventory_id | Assigned inventory item                                           |
| `quantity_assigned`    | DECIMAL(10,2) | DEFAULT 0                     | Quantity assigned to booking                                      |
| `quantity_returned`    | DECIMAL(10,2) | DEFAULT 0                     | Quantity returned                                                 |
| `quantity_damaged`     | DECIMAL(10,2) | DEFAULT 0                     | Quantity damaged                                                  |
| `damage_notes`         | TEXT          | NULLABLE                      | Notes about damage                                                |
| `status`               | ENUM          | DEFAULT 'assigned'            | 'assigned', 'in_use', 'partially_returned', 'returned', 'damaged' |
| `assigned_at`          | TIMESTAMP     | NULLABLE                      | When assigned                                                     |
| `returned_at`          | TIMESTAMP     | NULLABLE                      | When returned                                                     |
| `created_at`           | TIMESTAMP     | AUTO                          | Creation timestamp                                                |
| `updated_at`           | TIMESTAMP     | AUTO                          | Last update timestamp                                             |

---

### 6. NOTIFICATIONS (notifications)

| Column            | Type      | Constraints                 | Description                    |
| ----------------- | --------- | --------------------------- | ------------------------------ |
| `notification_id` | BIGINT    | PRIMARY KEY, AUTO_INCREMENT | Unique notification identifier |
| `user_id`         | BIGINT    | FK → users.user_id          | User to notify                 |
| `type`            | VARCHAR   | NOT NULL                    | Notification type              |
| `notifiable_type` | VARCHAR   | NULLABLE                    | Polymorphic type               |
| `notifiable_id`   | BIGINT    | NULLABLE                    | Polymorphic ID                 |
| `message`         | TEXT      | NOT NULL                    | Notification message           |
| `read`            | BOOLEAN   | DEFAULT false               | Read status                    |
| `data`            | JSON      | NULLABLE                    | Additional data                |
| `created_at`      | TIMESTAMP | AUTO                        | Creation timestamp             |
| `updated_at`      | TIMESTAMP | AUTO                        | Last update timestamp          |

---

### 7. MESSAGES (messages)

| Column        | Type      | Constraints                 | Description               |
| ------------- | --------- | --------------------------- | ------------------------- |
| `message_id`  | BIGINT    | PRIMARY KEY, AUTO_INCREMENT | Unique message identifier |
| `sender_id`   | BIGINT    | FK → users.user_id          | User who sent message     |
| `receiver_id` | BIGINT    | FK → users.user_id          | User who receives message |
| `message`     | TEXT      | NOT NULL                    | Message content           |
| `read`        | BOOLEAN   | DEFAULT false               | Read status               |
| `read_at`     | TIMESTAMP | NULLABLE                    | When message was read     |
| `created_at`  | TIMESTAMP | AUTO                        | Creation timestamp        |
| `updated_at`  | TIMESTAMP | AUTO                        | Last update timestamp     |

---

### 8. EVENTS (events) - Standalone Calendar Events

| Column        | Type      | Constraints                 | Description             |
| ------------- | --------- | --------------------------- | ----------------------- |
| `event_id`    | BIGINT    | PRIMARY KEY, AUTO_INCREMENT | Unique event identifier |
| `title`       | VARCHAR   | NOT NULL                    | Event title             |
| `description` | TEXT      | NULLABLE                    | Event description       |
| `start`       | DATE      | NOT NULL                    | Event start date        |
| `end`         | DATE      | NOT NULL                    | Event end date          |
| `created_at`  | TIMESTAMP | AUTO                        | Creation timestamp      |
| `updated_at`  | TIMESTAMP | AUTO                        | Last update timestamp   |

---

## 🔗 Relationships Summary

### One-to-Many (1:N) Relationships

| Parent Entity   | Child Entity        | Foreign Key    | Description                                        |
| --------------- | ------------------- | -------------- | -------------------------------------------------- |
| **Users**       | Bookings            | `user_id`      | A user can have many bookings                      |
| **Users**       | Payments            | `user_id`      | A user can make many payments                      |
| **Users**       | Notifications       | `user_id`      | A user can have many notifications                 |
| **Users**       | Messages (sent)     | `sender_id`    | A user can send many messages                      |
| **Users**       | Messages (received) | `receiver_id`  | A user can receive many messages                   |
| **Users**       | Users               | `approved_by`  | An admin can approve many users (self-ref)         |
| **Bookings**    | Payments            | `booking_id`   | A booking can have many payments                   |
| **Bookings**    | BookingInventory    | `booking_id`   | A booking can have many inventory assignments      |
| **Inventories** | BookingInventory    | `inventory_id` | An inventory item can be assigned to many bookings |

### Many-to-Many (M:N) Relationships

| Entity A     | Entity B        | Pivot Table         | Description                                                                   |
| ------------ | --------------- | ------------------- | ----------------------------------------------------------------------------- |
| **Bookings** | **Inventories** | `booking_inventory` | Bookings use multiple inventory items; items can be used in multiple bookings |

---

## 📊 Cardinality Diagram

```
┌────────────┐         ┌────────────┐
│   USERS    │ 1 ─── N │  BOOKINGS  │
└────────────┘         └────────────┘
      │                      │
      │ 1                    │ 1
      │                      │
      N                      N
┌────────────┐         ┌────────────┐
│  MESSAGES  │         │  PAYMENTS  │
└────────────┘         └────────────┘

┌────────────┐         ┌────────────┐
│   USERS    │ 1 ─── N │NOTIFICATIONS│
└────────────┘         └────────────┘

┌────────────┐         ┌──────────────────┐         ┌────────────┐
│  BOOKINGS  │ 1 ─── N │ BOOKING_INVENTORY │ N ─── 1 │ INVENTORIES│
└────────────┘         └──────────────────┘         └────────────┘
                              (M:N Pivot)

┌────────────┐
│   EVENTS   │  (Standalone - No FK relationships)
└────────────┘

┌────────────┐
│   USERS    │ 1 ─── N │ USERS │ (Self-reference: approved_by)
└────────────┘
```

---

## 🎯 Entity Status Enums

### User Roles

-   `admin` - System administrator
-   `user` - Regular customer

### Account Status

-   `pending` - Awaiting admin approval
-   `approved` - Account active
-   `rejected` - Account denied

### Booking Status

-   `pending` - New booking, awaiting review
-   `approved` - Booking confirmed
-   `rejected` - Booking declined
-   `pending_payment` - Awaiting payment
-   `partial_payment` - Partial payment received
-   `design` - In design phase
-   `completed` - Event completed
-   `cancelled` - Booking cancelled

### Payment Status

-   `pending` - Payment initiated
-   `paid` - Full payment received
-   `partial_payment` - Partial payment
-   `failed` - Payment failed
-   `refunded` - Payment refunded

### Inventory Assignment Status

-   `assigned` - Item assigned to booking
-   `in_use` - Item currently in use
-   `partially_returned` - Some items returned
-   `returned` - All items returned
-   `damaged` - Items damaged

### Inventory Stock Status

-   `In Stock` - Adequate stock level
-   `Low Stock` - Stock below threshold
-   `Out of Stock` - No stock available

---

## 📁 Laravel System Tables (Auto-generated)

| Table                   | Purpose                   |
| ----------------------- | ------------------------- |
| `cache`                 | Application cache storage |
| `cache_locks`           | Cache lock management     |
| `sessions`              | User session storage      |
| `jobs`                  | Queue job storage         |
| `job_batches`           | Batch job management      |
| `failed_jobs`           | Failed job logging        |
| `password_reset_tokens` | Password reset tokens     |

---

## 🔧 Indexes & Performance

### Primary Keys (All custom named)

-   `users.user_id`
-   `bookings.booking_id`
-   `payments.payment_id`
-   `inventories.inventory_id`
-   `booking_inventory.booking_inventory_id`
-   `notifications.notification_id`
-   `messages.message_id`
-   `events.event_id`

### Recommended Indexes

-   `users.email` (UNIQUE)
-   `bookings.user_id`
-   `bookings.status`
-   `bookings.event_date`
-   `payments.booking_id`
-   `payments.user_id`
-   `booking_inventory.booking_id`
-   `booking_inventory.inventory_id`
-   `notifications.user_id`
-   `messages.sender_id`
-   `messages.receiver_id`

---

_Generated for Booking Management System - Laravel Application_
