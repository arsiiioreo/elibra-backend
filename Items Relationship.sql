-- =========================================================
--  MATERIAL TYPES (reference table)
-- =========================================================
CREATE TABLE material_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO material_types (name) VALUES
('Book'),
('Thesis'),
('Dissertation'),
('Audio'),
('Serial'),
('Periodical'),
('Electronic'),
('Vertical File'),
('Newspaper Clipping');

-- =========================================================
--  MATERIALS (main table for all entries)
-- =========================================================
CREATE TABLE materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    year_published YEAR,
    publisher VARCHAR(255),
    language VARCHAR(100),
    isbn_issn VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_material_type FOREIGN KEY (type_id)
        REFERENCES material_types(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
--  BOOKS
-- =========================================================
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    edition VARCHAR(50),
    place_published VARCHAR(255),
    pages INT,
    CONSTRAINT fk_book_material FOREIGN KEY (material_id)
        REFERENCES materials(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
--  THESIS
-- =========================================================
CREATE TABLE theses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    degree VARCHAR(100),
    institution VARCHAR(255),
    advisor VARCHAR(255),
    repository VARCHAR(255),
    CONSTRAINT fk_thesis_material FOREIGN KEY (material_id)
        REFERENCES materials(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
--  DISSERTATION
-- =========================================================
CREATE TABLE dissertations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    degree VARCHAR(100),
    institution VARCHAR(255),
    advisor VARCHAR(255),
    repository VARCHAR(255),
    CONSTRAINT fk_dissertation_material FOREIGN KEY (material_id)
        REFERENCES materials(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
--  AUDIO
-- =========================================================
CREATE TABLE audio_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    format VARCHAR(50),
    duration VARCHAR(50),
    producer VARCHAR(255),
    CONSTRAINT fk_audio_material FOREIGN KEY (material_id)
        REFERENCES materials(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
--  SERIAL (journal/series)
-- =========================================================
CREATE TABLE serials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    volume VARCHAR(50),
    issue VARCHAR(50),
    pages VARCHAR(50),
    doi VARCHAR(100),
    CONSTRAINT fk_serial_material FOREIGN KEY (material_id)
        REFERENCES materials(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
--  PERIODICAL
-- =========================================================
CREATE TABLE periodicals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    volume VARCHAR(50),
    issue VARCHAR(50),
    date_published DATE,
    pages VARCHAR(50),
    CONSTRAINT fk_periodical_material FOREIGN KEY (material_id)
        REFERENCES materials(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
--  ELECTRONIC
-- =========================================================
CREATE TABLE electronic_resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    url TEXT,
    access_date DATE,
    website_name VARCHAR(255),
    CONSTRAINT fk_electronic_material FOREIGN KEY (material_id)
        REFERENCES materials(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
--  VERTICAL FILE
-- =========================================================
CREATE TABLE vertical_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    organization VARCHAR(255),
    location VARCHAR(255),
    notes TEXT,
    CONSTRAINT fk_vertical_material FOREIGN KEY (material_id)
        REFERENCES materials(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
--  NEWSPAPER CLIPPING
-- =========================================================
CREATE TABLE newspaper_clippings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    newspaper_name VARCHAR(255),
    date_published DATE,
    page_number VARCHAR(50),
    CONSTRAINT fk_newspaper_material FOREIGN KEY (material_id)
        REFERENCES materials(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
