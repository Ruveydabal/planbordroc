CREATE TABLE students (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    roles_id BIGINT NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE roles (
    id BIGSERIAL PRIMARY KEY,
    roleStudent BOOLEAN,
    roleTeacher BOOLEAN,
    roleAdmin BOOLEAN
);

CREATE TABLE classroom (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE subClassroom (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    classroom_id BIGINT NOT NULL
);

CREATE TABLE presenceStudent (
    id BIGSERIAL PRIMARY KEY,
    students_id BIGINT NOT NULL,
    subClassroom_id BIGINT NOT NULL,
    time TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Create updated_at trigger
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

DROP TRIGGER IF EXISTS update_student_updated_at ON student;

CREATE TRIGGER update_student_updated_at
    BEFORE UPDATE ON student
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();