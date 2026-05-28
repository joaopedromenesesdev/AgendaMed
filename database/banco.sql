-- Comando estruturado para criação do banco de dados [cite: 83]
CREATE DATABASE IF NOT EXISTS agendamed;
USE agendamed;

-- Criação da tabela utilizando a coluna unificada 'data_hora' [cite: 84]
CREATE TABLE IF NOT EXISTS consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente VARCHAR(100) NOT NULL,
    medico VARCHAR(100) NOT NULL,
    data_hora DATETIME NOT NULL
);

-- Inserção dos 3 registros obrigatórios para testes iniciais [cite: 85]
INSERT INTO consultas (paciente, medico, data_hora) VALUES ('Mariana Silva', 'Dr. Marçal', '2026-06-01 14:00:00');
INSERT INTO consultas (paciente, medico, data_hora) VALUES ('Carlos Souza', 'Dra. Ana Costa', '2026-06-02 10:30:00');
INSERT INTO consultas (paciente, medico, data_hora) VALUES ('Beatriz Reis', 'Dr. Marçal', '2026-06-03 16:00:00');