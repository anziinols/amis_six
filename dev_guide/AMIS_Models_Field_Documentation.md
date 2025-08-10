# AMIS Models Field Documentation

This document provides a comprehensive overview of all model fields in the AMIS (Agricultural Management Information System) application.

## Table of Contents
1. [User Management Models](#user-management-models)
2. [Administrative Models](#administrative-models)
3. [Document Management Models](#document-management-models)
4. [Commodity Management Models](#commodity-management-models)
5. [Meeting & Agreement Models](#meeting--agreement-models)
6. [Planning Models](#planning-models)
7. [Workplan Models](#workplan-models)
8. [SME Management Models](#sme-management-models)
9. [Common Field Patterns](#common-field-patterns)

---

## User Management Models

### UserModel
**Table:** `users`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| ucode | VARCHAR(200) | User code (required) |
| password | VARCHAR | Hashed password (required) |
| email | VARCHAR | Email address (required, unique) |
| phone | VARCHAR | Phone number |
| fname | VARCHAR(255) | First name (required) |
| lname | VARCHAR(255) | Last name (required) |
| gender | ENUM | Gender (male, female) |
| dobirth | DATE | Date of birth |
| place_birth | VARCHAR(255) | Place of birth |
| address | TEXT | Address |
| employee_number | VARCHAR(100) | Employee number |
| branch_id | INT | Foreign key to branches table |
| designation | VARCHAR(255) | Job designation |
| grade | VARCHAR(100) | Employee grade |
| report_to_id | INT | Foreign key to users table (supervisor) |
| is_evaluator | TINYINT | M&E evaluator flag (0,1) |
| is_supervisor | TINYINT | Supervisor flag (0,1) |
| commodity_id | INT | Foreign key to commodities table |
| role | ENUM | User role (admin, supervisor, user, guest, commodity) |
| joined_date | DATE | Date joined |
| id_photo_filepath | VARCHAR(255) | Profile photo path |
| user_status | TINYINT | Active status (0,1) |
| user_status_remarks | TEXT | Status change remarks |
| user_status_at | DATETIME | Status change timestamp |
| user_status_by | INT | User who changed status |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |

### DakoiiUserModel
**Table:** `dakoii_users`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| name | VARCHAR(255) | Full name (required) |
| username | VARCHAR(255) | Username (required, unique) |
| password | VARCHAR(255) | Hashed password (required) |
| role | VARCHAR(100) | User role (required) |
| dakoii_user_status | TINYINT | Active status |
| dakoii_user_status_remarks | TEXT | Status remarks |
| dakoii_user_status_at | DATETIME | Status change timestamp |
| dakoii_user_status_by | INT | User who changed status |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

---

## Administrative Models

### BranchesModel
**Table:** `branches`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| parent_id | INT | Foreign key to parent branch |
| abbrev | VARCHAR | Branch abbreviation |
| name | VARCHAR | Branch name |
| remarks | TEXT | Additional remarks |
| branch_status | TINYINT | Active status |
| branch_status_by | INT | User who changed status |
| branch_status_at | DATETIME | Status change timestamp |
| branch_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### GovStructureModel
**Table:** `gov_structure`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| parent_id | INT | Foreign key to parent structure |
| json_id | VARCHAR | JSON identifier |
| level | INT | Government level |
| code | VARCHAR | Structure code |
| name | VARCHAR | Structure name |
| flag_filepath | VARCHAR | Flag image path |
| map_center | VARCHAR | Map center coordinates |
| map_zoom | INT | Map zoom level |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |

### RegionModel
**Table:** `regions`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| name | VARCHAR(255) | Region name (required) |
| code | VARCHAR(10) | Region code |
| description | TEXT | Region description |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### RegionProvinceLinkModel
**Table:** `region_province_link`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| region_id | INT | Foreign key to regions table |
| province_id | INT | Foreign key to gov_structure table |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |

### OrgSettingsModel
**Table:** `org_settings`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| setting_key | VARCHAR | Setting identifier |
| setting_value | TEXT | Setting value |
| setting_type | VARCHAR | Data type |
| description | TEXT | Setting description |
| is_active | TINYINT | Active status |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |

---

## Document Management Models

### FolderModel
**Table:** `folders`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| branch_id | INT | Foreign key to branches table (required) |
| parent_folder_id | INT | Foreign key to parent folder |
| name | VARCHAR(255) | Folder name (required) |
| description | TEXT | Folder description |
| access | ENUM | Access level (private, internal, public) |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### DocumentModel
**Table:** `documents`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| branch_id | INT | Foreign key to branches table |
| folder_id | INT | Foreign key to folders table |
| classification | VARCHAR | Document classification |
| title | VARCHAR | Document title |
| description | TEXT | Document description |
| doc_date | DATE | Document date |
| authors | TEXT | Document authors |
| file_path | VARCHAR | File storage path |
| file_type | VARCHAR | File MIME type |
| file_size | INT | File size in bytes |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

---

## Commodity Management Models

### CommoditiesModel
**Table:** `commodities`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| commodity_code | VARCHAR(50) | Commodity code (required, unique) |
| commodity_name | VARCHAR(255) | Commodity name (required) |
| commodity_icon | VARCHAR | Icon file path |
| commodity_color_code | VARCHAR(10) | Color code for UI |
| created_by | VARCHAR(100) | User who created record |
| updated_by | VARCHAR(100) | User who last updated record |
| deleted_by | VARCHAR(100) | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |
| is_deleted | TINYINT | Soft delete flag |

### CommodityPricesModel
**Table:** `commodity_prices`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| commodity_id | INT | Foreign key to commodities table (required) |
| price_date | DATE | Price date (required) |
| market_type | ENUM | Market type (local, export, wholesale, retail) |
| price_per_unit | DECIMAL | Price per unit (required) |
| unit_of_measurement | VARCHAR | Unit of measurement |
| currency | VARCHAR | Currency code |
| location | VARCHAR | Market location |
| source | VARCHAR | Data source |
| notes | TEXT | Additional notes |
| created_by | VARCHAR(100) | User who created record |
| updated_by | VARCHAR(100) | User who last updated record |
| deleted_by | VARCHAR(100) | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |
| is_deleted | TINYINT | Soft delete flag |

### CommodityProductionModel
**Table:** `commodity_production`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| commodity_id | INT | Foreign key to commodities table (required) |
| date_from | DATE | Production start date (required) |
| date_to | DATE | Production end date (required) |
| item | VARCHAR(255) | Production item (required) |
| description | TEXT | Production description |
| unit_of_measurement | VARCHAR(50) | Unit of measurement |
| quantity | DECIMAL | Production quantity (required) |
| is_exported | TINYINT | Export flag (0,1) |
| created_by | VARCHAR(100) | User who created record |
| updated_by | VARCHAR(100) | User who last updated record |
| deleted_by | VARCHAR(100) | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |
| is_deleted | TINYINT | Soft delete flag |

---

## Meeting & Agreement Models

### MeetingsModel
**Table:** `meetings`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| branch_id | INT | Foreign key to branches table (required) |
| title | VARCHAR(255) | Meeting title (required) |
| agenda | TEXT | Meeting agenda |
| meeting_date | DATE | Meeting date (required) |
| start_time | TIME | Meeting start time |
| end_time | TIME | Meeting end time |
| location | VARCHAR(255) | Meeting location |
| participants | JSON | Meeting participants (JSON) |
| status | ENUM | Meeting status (scheduled, in_progress, completed, cancelled) |
| minutes | JSON | Meeting minutes (JSON) |
| attachments | JSON | Meeting attachments (JSON) |
| recurrence_rule | VARCHAR(255) | Recurrence pattern |
| remarks | TEXT | Additional remarks |
| access_type | ENUM | Access level (private, internal, public) |
| is_deleted | TINYINT | Soft delete flag |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### AgreementsModel
**Table:** `agreements`  
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| branch_id | INT | Foreign key to branches table (required) |
| title | VARCHAR(255) | Agreement title (required) |
| description | TEXT | Agreement description |
| agreement_type | VARCHAR(100) | Type of agreement |
| parties | JSON | Agreement parties (JSON) |
| effective_date | DATE | Effective date (required) |
| expiry_date | DATE | Expiry date |
| status | ENUM | Agreement status (draft, active, expired, terminated, archived) |
| terms | TEXT | Agreement terms |
| conditions | TEXT | Agreement conditions |
| attachments | JSON | Agreement attachments (JSON) |
| remarks | TEXT | Additional remarks |
| is_deleted | TINYINT | Soft delete flag |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

---

## Common Field Patterns

### Timestamp Fields
Most models include these standard timestamp fields:
- `created_at` (DATETIME) - Record creation timestamp
- `updated_at` (DATETIME) - Record update timestamp  
- `deleted_at` (DATETIME) - Soft delete timestamp (when useSoftDeletes is enabled)

### Audit Fields
Most models include these audit trail fields:
- `created_by` (INT) - User ID who created the record
- `updated_by` (INT) - User ID who last updated the record
- `deleted_by` (INT) - User ID who deleted the record

### Status Fields
Many models include status tracking fields:
- `[entity]_status` (TINYINT) - Active/inactive status
- `[entity]_status_by` (INT) - User who changed status
- `[entity]_status_at` (DATETIME) - Status change timestamp
- `[entity]_status_remarks` (TEXT) - Status change remarks

### JSON Fields
Several models use JSON fields for complex data:
- `participants` - Meeting participants
- `minutes` - Meeting minutes
- `attachments` - File attachments
- `parties` - Agreement parties
- `investments`, `kras`, `strategies`, `indicators` - MTDP planning data

### Soft Delete
Most models use soft delete functionality with:
- `deleted_at` (DATETIME) - Soft delete timestamp
- `is_deleted` (TINYINT) - Soft delete flag (some models)

---

## Planning Models

### MtdpModel
**Table:** `plans_mtdp`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| abbrev | VARCHAR(20) | MTDP abbreviation (required) |
| title | VARCHAR(255) | MTDP title (required) |
| date_from | DATE | Plan start date |
| date_to | DATE | Plan end date |
| remarks | TEXT | Additional remarks |
| mtdp_status | INT | Plan status (required) |
| mtdp_status_by | INT | User who changed status (required) |
| mtdp_status_at | DATETIME | Status change timestamp |
| mtdp_status_remarks | TEXT | Status change remarks |
| created_by | VARCHAR(255) | User who created record (required) |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### MtdpDipModel
**Table:** `plans_mtdp_dip`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| mtdp_id | INT | Foreign key to plans_mtdp table |
| spa_id | INT | Foreign key to plans_mtdp_spa table |
| dip_code | VARCHAR | DIP code |
| dip_title | VARCHAR | DIP title |
| dip_remarks | TEXT | DIP remarks |
| investments | JSON | Investment data (JSON) |
| kras | JSON | Key Result Areas (JSON) |
| strategies | JSON | Strategies data (JSON) |
| indicators | JSON | Indicators data (JSON) |
| dip_status | INT | DIP status |
| dip_status_by | INT | User who changed status |
| dip_status_at | DATETIME | Status change timestamp |
| dip_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### MtdpSpaModel
**Table:** `plans_mtdp_spa`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| mtdp_id | INT | Foreign key to plans_mtdp table |
| title | VARCHAR | SPA title |
| description | TEXT | SPA description |
| spa_status | INT | SPA status |
| spa_status_by | INT | User who changed status |
| spa_status_at | DATETIME | Status change timestamp |
| spa_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### MtdpSpecificAreaModel
**Table:** `plans_mtdp_specific_area`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| mtdp_id | INT | Foreign key to plans_mtdp table |
| spa_id | INT | Foreign key to plans_mtdp_spa table |
| title | VARCHAR | Specific area title |
| description | TEXT | Specific area description |
| sa_status | INT | Specific area status |
| sa_status_by | INT | User who changed status |
| sa_status_at | DATETIME | Status change timestamp |
| sa_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### MtdpInvestmentsModel
**Table:** `plans_mtdp_investments`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| mtdp_id | INT | Foreign key to plans_mtdp table |
| spa_id | INT | Foreign key to plans_mtdp_spa table |
| sa_id | INT | Foreign key to plans_mtdp_specific_area table |
| dip_id | INT | Foreign key to plans_mtdp_dip table |
| dip_link_dip_id | INT | Linked DIP ID |
| investment | TEXT | Investment description |
| year_one | DECIMAL | Year 1 investment amount |
| year_two | DECIMAL | Year 2 investment amount |
| year_three | DECIMAL | Year 3 investment amount |
| year_four | DECIMAL | Year 4 investment amount |
| year_five | DECIMAL | Year 5 investment amount |
| funding_sources | TEXT | Funding sources |
| investment_status | INT | Investment status |
| investment_status_by | INT | User who changed status |
| investment_status_at | DATETIME | Status change timestamp |
| investment_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### MtdpKraModel
**Table:** `plans_mtdp_kra`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| mtdp_id | INT | Foreign key to plans_mtdp table (required) |
| spa_id | INT | Foreign key to plans_mtdp_spa table (required) |
| dip_id | INT | Foreign key to plans_mtdp_dip table (required) |
| sa_id | INT | Foreign key to plans_mtdp_specific_area table |
| investment_id | INT | Foreign key to plans_mtdp_investments table |
| kpi | TEXT | Key Performance Indicator (required) |
| year_one | VARCHAR | Year 1 target |
| year_two | VARCHAR | Year 2 target |
| year_three | VARCHAR | Year 3 target |
| year_four | VARCHAR | Year 4 target |
| year_five | VARCHAR | Year 5 target |
| responsible_agencies | TEXT | Responsible agencies |
| kra_status | INT | KRA status (required) |
| kra_status_by | INT | User who changed status (required) |
| kra_status_at | DATETIME | Status change timestamp |
| kra_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### MtdpStrategiesModel
**Table:** `plans_mtdp_strategies`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| mtdp_id | INT | Foreign key to plans_mtdp table |
| spa_id | INT | Foreign key to plans_mtdp_spa table |
| dip_id | INT | Foreign key to plans_mtdp_dip table |
| sa_id | INT | Foreign key to plans_mtdp_specific_area table |
| investment_id | INT | Foreign key to plans_mtdp_investments table |
| kra_id | INT | Foreign key to plans_mtdp_kra table |
| strategy | TEXT | Strategy description |
| strategies_status | INT | Strategy status |
| strategies_status_by | INT | User who changed status |
| strategies_status_at | DATETIME | Status change timestamp |
| strategies_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### MtdpIndicatorsModel
**Table:** `plans_mtdp_indicators`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| mtdp_id | INT | Foreign key to plans_mtdp table (required) |
| spa_id | INT | Foreign key to plans_mtdp_spa table (required) |
| dip_id | INT | Foreign key to plans_mtdp_dip table (required) |
| sa_id | INT | Foreign key to plans_mtdp_specific_area table |
| investment_id | INT | Foreign key to plans_mtdp_investments table |
| kra_id | INT | Foreign key to plans_mtdp_kra table |
| strategies_id | INT | Foreign key to plans_mtdp_strategies table |
| indicator | TEXT | Indicator description (required) |
| source | VARCHAR | Data source |
| baseline | VARCHAR | Baseline value |
| year_one | VARCHAR | Year 1 target |
| year_two | VARCHAR | Year 2 target |
| year_three | VARCHAR | Year 3 target |
| year_four | VARCHAR | Year 4 target |
| year_five | VARCHAR | Year 5 target |
| indicators_status | INT | Indicator status (required) |
| indicators_status_by | INT | User who changed status (required) |
| indicators_status_at | DATETIME | Status change timestamp |
| indicators_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### CorporatePlanModel
**Table:** `plans_corporate_plan`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| parent_id | INT | Foreign key to parent plan |
| type | VARCHAR | Plan type |
| code | VARCHAR | Plan code |
| title | VARCHAR | Plan title |
| date_from | DATE | Plan start date |
| date_to | DATE | Plan end date |
| remarks | TEXT | Additional remarks |
| corp_plan_status | INT | Plan status |
| corp_plan_status_by | INT | User who changed status |
| corp_plan_status_at | DATETIME | Status change timestamp |
| corp_plan_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### NaspModel
**Table:** `plans_nasp`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| parent_id | INT | Foreign key to parent plan |
| type | VARCHAR | Plan type |
| code | VARCHAR | Plan code |
| title | VARCHAR | Plan title |
| date_from | DATE | Plan start date |
| date_to | DATE | Plan end date |
| remarks | TEXT | Additional remarks |
| nasp_status | INT | Plan status |
| nasp_status_by | INT | User who changed status |
| nasp_status_at | DATETIME | Status change timestamp |
| nasp_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

## Workplan Models

### WorkplanModel
**Table:** `workplans`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| branch_id | INT | Foreign key to branches table |
| title | VARCHAR | Workplan title |
| description | TEXT | Workplan description |
| supervisor_id | INT | Foreign key to users table |
| start_date | DATE | Workplan start date |
| end_date | DATE | Workplan end date |
| status | VARCHAR | Workplan status |
| objectives | TEXT | Workplan objectives |
| remarks | TEXT | Additional remarks |
| is_deleted | TINYINT | Soft delete flag |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### WorkplanActivityModel
**Table:** `workplan_activities`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| workplan_id | INT | Foreign key to workplans table |
| branch_id | INT | Foreign key to branches table |
| province_id | INT | Foreign key to gov_structure table |
| district_id | INT | Foreign key to gov_structure table |
| location | VARCHAR | Activity location |
| gps_coordinates | VARCHAR | GPS coordinates |
| title | VARCHAR | Activity title |
| description | TEXT | Activity description |
| activity_type | ENUM | Activity type (training, infrastructure, inputs, output) |
| q_one | DECIMAL | Quarter 1 budget |
| q_two | DECIMAL | Quarter 2 budget |
| q_three | DECIMAL | Quarter 3 budget |
| q_four | DECIMAL | Quarter 4 budget |
| supervisor_id | INT | Foreign key to users table |
| status | VARCHAR | Activity status |
| status_by | INT | User who changed status |
| status_at | DATETIME | Status change timestamp |
| status_remarks | TEXT | Status change remarks |
| total_cost | DECIMAL | Total activity cost |
| image_paths | JSON | Activity images (JSON) |
| trainers | JSON | Trainer information (JSON) |
| trainees | JSON | Trainee information (JSON) |
| unit | VARCHAR | Unit of measurement |
| quantity | DECIMAL | Activity quantity |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### WorkplanInfrastructureActivityModel
**Table:** `workplan_infrastructure_activities`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| workplan_id | INT | Foreign key to workplans table |
| activity_id | INT | Foreign key to workplan_activities table |
| infrastructure_type | VARCHAR | Type of infrastructure |
| specifications | TEXT | Infrastructure specifications |
| materials | JSON | Required materials (JSON) |
| labor_requirements | JSON | Labor requirements (JSON) |
| completion_percentage | DECIMAL | Completion percentage |
| quality_standards | TEXT | Quality standards |
| safety_measures | TEXT | Safety measures |
| environmental_impact | TEXT | Environmental impact assessment |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### WorkplanInputActivityModel
**Table:** `workplan_input_activities`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| workplan_id | INT | Foreign key to workplans table |
| activity_id | INT | Foreign key to workplan_activities table |
| input_type | VARCHAR | Type of input |
| input_description | TEXT | Input description |
| quantity_required | DECIMAL | Required quantity |
| unit_cost | DECIMAL | Cost per unit |
| supplier_info | JSON | Supplier information (JSON) |
| delivery_schedule | JSON | Delivery schedule (JSON) |
| quality_specifications | TEXT | Quality specifications |
| storage_requirements | TEXT | Storage requirements |
| distribution_plan | TEXT | Distribution plan |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### WorkplanOutputActivityModel
**Table:** `workplan_output_activities`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| workplan_id | INT | Foreign key to workplans table |
| activity_id | INT | Foreign key to workplan_activities table |
| output_type | VARCHAR | Type of output |
| output_description | TEXT | Output description |
| target_quantity | DECIMAL | Target quantity |
| achieved_quantity | DECIMAL | Achieved quantity |
| quality_metrics | JSON | Quality metrics (JSON) |
| beneficiaries | JSON | Beneficiary information (JSON) |
| impact_assessment | TEXT | Impact assessment |
| sustainability_measures | TEXT | Sustainability measures |
| lessons_learned | TEXT | Lessons learned |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### WorkplanTrainingActivityModel
**Table:** `workplan_training_activities`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| workplan_id | INT | Foreign key to workplans table |
| activity_id | INT | Foreign key to workplan_activities table |
| training_type | VARCHAR | Type of training |
| training_topic | VARCHAR | Training topic |
| curriculum | TEXT | Training curriculum |
| duration_days | INT | Training duration in days |
| max_participants | INT | Maximum participants |
| trainer_requirements | JSON | Trainer requirements (JSON) |
| venue_requirements | TEXT | Venue requirements |
| materials_needed | JSON | Training materials (JSON) |
| certification_type | VARCHAR | Certification type |
| evaluation_criteria | TEXT | Evaluation criteria |
| follow_up_plan | TEXT | Follow-up plan |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### Workplan Link Models

#### WorkplanCorporatePlanLinkModel
**Table:** `workplan_corporate_plan_link`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| workplan_id | INT | Foreign key to workplans table |
| corporate_plan_id | INT | Foreign key to plans_corporate_plan table |
| link_type | VARCHAR | Type of linkage |
| alignment_notes | TEXT | Alignment notes |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |

#### WorkplanMtdpLinkModel
**Table:** `workplan_mtdp_link`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| workplan_id | INT | Foreign key to workplans table |
| mtdp_id | INT | Foreign key to plans_mtdp table |
| spa_id | INT | Foreign key to plans_mtdp_spa table |
| dip_id | INT | Foreign key to plans_mtdp_dip table |
| link_type | VARCHAR | Type of linkage |
| alignment_notes | TEXT | Alignment notes |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |

#### WorkplanNaspLinkModel
**Table:** `workplan_nasp_link`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| workplan_id | INT | Foreign key to workplans table |
| nasp_id | INT | Foreign key to plans_nasp table |
| link_type | VARCHAR | Type of linkage |
| alignment_notes | TEXT | Alignment notes |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |

#### WorkplanOthersLinkModel
**Table:** `workplan_others_link`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| workplan_id | INT | Foreign key to workplans table |
| external_plan_name | VARCHAR | External plan name |
| external_plan_type | VARCHAR | External plan type |
| link_description | TEXT | Link description |
| alignment_notes | TEXT | Alignment notes |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |

## SME Management Models

### SmeModel
**Table:** `sme`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| sme_code | VARCHAR | SME code |
| business_name | VARCHAR | Business name |
| business_type | VARCHAR | Type of business |
| registration_number | VARCHAR | Business registration number |
| owner_name | VARCHAR | Owner name |
| contact_person | VARCHAR | Contact person |
| phone | VARCHAR | Phone number |
| email | VARCHAR | Email address |
| address | TEXT | Business address |
| province_id | INT | Foreign key to gov_structure table |
| district_id | INT | Foreign key to gov_structure table |
| llg_id | INT | Foreign key to gov_structure table |
| gps_coordinates | VARCHAR | GPS coordinates |
| business_description | TEXT | Business description |
| products_services | TEXT | Products and services |
| target_market | TEXT | Target market |
| annual_revenue | DECIMAL | Annual revenue |
| employee_count | INT | Number of employees |
| establishment_date | DATE | Business establishment date |
| certification_status | VARCHAR | Certification status |
| support_needed | TEXT | Support needed |
| sme_status | TINYINT | SME status |
| sme_status_by | INT | User who changed status |
| sme_status_at | DATETIME | Status change timestamp |
| sme_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### SmeStaffModel
**Table:** `sme_staff`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| sme_id | INT | Foreign key to sme table |
| staff_name | VARCHAR | Staff name |
| position | VARCHAR | Staff position |
| phone | VARCHAR | Phone number |
| email | VARCHAR | Email address |
| skills | TEXT | Staff skills |
| experience_years | INT | Years of experience |
| education_level | VARCHAR | Education level |
| training_needs | TEXT | Training needs |
| staff_status | TINYINT | Staff status |
| staff_status_by | INT | User who changed status |
| staff_status_at | DATETIME | Status change timestamp |
| staff_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

---

## Proposal & Vulnerability Models

### ProposalModel
**Table:** `proposal`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| workplan_id | INT | Foreign key to workplans table |
| activity_id | INT | Foreign key to workplan_activities table |
| branch_id | INT | Foreign key to branches table |
| province_id | INT | Foreign key to gov_structure table |
| district_id | INT | Foreign key to gov_structure table |
| title | VARCHAR | Proposal title |
| description | TEXT | Proposal description |
| objectives | TEXT | Proposal objectives |
| methodology | TEXT | Methodology |
| timeline | JSON | Project timeline (JSON) |
| budget_breakdown | JSON | Budget breakdown (JSON) |
| expected_outcomes | TEXT | Expected outcomes |
| risk_assessment | TEXT | Risk assessment |
| sustainability_plan | TEXT | Sustainability plan |
| monitoring_plan | TEXT | Monitoring plan |
| supervisor_id | INT | Foreign key to users table |
| action_officer_id | INT | Foreign key to users table |
| proposal_status | VARCHAR | Proposal status |
| proposal_status_by | INT | User who changed status |
| proposal_status_at | DATETIME | Status change timestamp |
| proposal_status_remarks | TEXT | Status change remarks |
| submission_date | DATE | Submission date |
| approval_date | DATE | Approval date |
| start_date | DATE | Project start date |
| end_date | DATE | Project end date |
| total_budget | DECIMAL | Total budget |
| approved_budget | DECIMAL | Approved budget |
| attachments | JSON | Proposal attachments (JSON) |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

### VulnerabilityModel
**Table:** `vulnerability`
**Primary Key:** `id`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Auto-increment primary key |
| province_id | INT | Foreign key to gov_structure table |
| district_id | INT | Foreign key to gov_structure table |
| vulnerability_type | VARCHAR | Type of vulnerability |
| vulnerability_category | VARCHAR | Vulnerability category |
| description | TEXT | Vulnerability description |
| severity_level | ENUM | Severity level (low, medium, high, critical) |
| affected_population | INT | Number of affected people |
| geographic_scope | VARCHAR | Geographic scope |
| seasonal_pattern | VARCHAR | Seasonal pattern |
| risk_factors | JSON | Risk factors (JSON) |
| impact_assessment | TEXT | Impact assessment |
| coping_mechanisms | TEXT | Existing coping mechanisms |
| intervention_needs | TEXT | Intervention needs |
| priority_ranking | INT | Priority ranking |
| data_source | VARCHAR | Data source |
| assessment_date | DATE | Assessment date |
| next_review_date | DATE | Next review date |
| vulnerability_status | TINYINT | Vulnerability status |
| vulnerability_status_by | INT | User who changed status |
| vulnerability_status_at | DATETIME | Status change timestamp |
| vulnerability_status_remarks | TEXT | Status change remarks |
| created_by | INT | User who created record |
| updated_by | INT | User who last updated record |
| deleted_by | INT | User who deleted record |
| created_at | DATETIME | Record creation timestamp |
| updated_at | DATETIME | Record update timestamp |
| deleted_at | DATETIME | Soft delete timestamp |

---

## Field Type Reference

### Common Data Types Used

| Type | Description | Example Fields |
|------|-------------|----------------|
| INT | Integer, often used for IDs and foreign keys | id, branch_id, user_id |
| VARCHAR(n) | Variable character string with max length | name, title, code |
| TEXT | Large text field for descriptions | description, remarks, notes |
| DATE | Date field (YYYY-MM-DD) | created_date, start_date, end_date |
| DATETIME | Date and time field | created_at, updated_at, deleted_at |
| DECIMAL | Decimal number for currency/measurements | price, budget, quantity |
| TINYINT | Small integer, often used for flags | status, is_active, is_deleted |
| JSON | JSON data type for complex structures | participants, attachments, metadata |
| ENUM | Enumerated values | status, type, category |

### Validation Patterns

| Pattern | Description | Example Fields |
|---------|-------------|----------------|
| required | Field is mandatory | title, name, email |
| max_length[n] | Maximum character length | name(255), code(50) |
| valid_email | Valid email format | email |
| valid_date | Valid date format | start_date, end_date |
| integer | Integer validation | id, count, quantity |
| decimal | Decimal number validation | price, budget, amount |
| in_list[...] | Must be one of specified values | status, type, category |
| is_unique | Must be unique in table | email, code |

---

## Summary

This documentation covers **38 models** across **10 functional areas**:

1. **User Management** (2 models): Users, Dakoii Users
2. **Administrative** (5 models): Branches, Government Structure, Regions, Region-Province Links, Organization Settings
3. **Document Management** (2 models): Folders, Documents
4. **Commodity Management** (3 models): Commodities, Commodity Prices, Commodity Production
5. **Meeting & Agreement** (2 models): Meetings, Agreements
6. **Planning** (8 models): MTDP, MTDP-DIP, MTDP-SPA, MTDP Specific Areas, MTDP Investments, MTDP KRA, MTDP Strategies, MTDP Indicators, Corporate Plans, NASP
7. **Workplan Management** (9 models): Workplans, Activities, Infrastructure Activities, Input Activities, Output Activities, Training Activities, and 4 Link models
8. **SME Management** (2 models): SME, SME Staff
9. **Proposal & Vulnerability** (2 models): Proposals, Vulnerability
10. **System** (1 model): Migrations (not documented as it's framework-generated)

### Total Field Count: **500+ fields** across all models

Each model follows consistent patterns for:
- **Audit trails** (created_by, updated_by, deleted_by)
- **Timestamps** (created_at, updated_at, deleted_at)
- **Status tracking** (status fields with related metadata)
- **Soft deletes** (deleted_at, is_deleted)
- **JSON fields** for complex data structures

---

*Generated on: 2025-08-10*
*AMIS Version: 5.0*
*Database Schema: Latest Structure*
