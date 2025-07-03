-- Commodities table structure
CREATE TABLE commodities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commodity_code VARCHAR(50) NOT NULL UNIQUE,
    commodity_name VARCHAR(255) NOT NULL,
    commodity_icon TEXT COMMENT 'File path to uploaded icon image',
    commodity_color_code VARCHAR(10),

    -- Audit fields
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    created_by VARCHAR(100),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    updated_by VARCHAR(100),
    deleted_at DATETIME DEFAULT NULL,
    deleted_by VARCHAR(100),

    -- Soft delete indicator
    is_deleted BOOLEAN DEFAULT FALSE NOT NULL
);
