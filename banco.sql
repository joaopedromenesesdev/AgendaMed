-- Criar o banco de dados se ele não existir
CREATE DATABASE IF NOT EXISTS agendamed;
USE agendamed;

-- Criar a tabela de consultas estruturada
CREATE TABLE IF NOT EXISTS consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente VARCHAR(100) NOT NULL,
    medico VARCHAR(100) NOT NULL,
    data DATETIME NOT NULL
);