CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    roles_id BIGINT NOT NULL
);

ALTER TABLE students 
    DROP COLUMN email,
    DROP COLUMN password;