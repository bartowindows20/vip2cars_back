-- ------------------------------------------------------
-- BBDD: Encuestas anónimas
-- ------------------------------------------------------

CREATE DATABASE IF NOT EXISTS vip2cars_surveys
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE vip2cars_surveys;

SET NAMES utf8mb4;

-- -------------------------
-- Encuestas
-- -------------------------
CREATE TABLE surveys (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT NULL,
  end_at DATETIME NULL,
  status ENUM('draft','published','closed') NOT NULL DEFAULT 'draft',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------
-- Preguntas
-- -------------------------
CREATE TABLE questions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  question_text TEXT NOT NULL,
  type ENUM('text','single','multiple') NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------
-- Preguntas dentro de una encuesta
-- PK compuesta para evitar duplicados
-- -------------------------
CREATE TABLE survey_questions (
  survey_id BIGINT UNSIGNED NOT NULL,
  question_id BIGINT UNSIGNED NOT NULL,
  section VARCHAR(100) NULL,
  position INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (survey_id, question_id),
  CONSTRAINT fk_sq_survey
    FOREIGN KEY (survey_id) REFERENCES surveys(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_sq_question
    FOREIGN KEY (question_id) REFERENCES questions(id)
    ON DELETE RESTRICT,
  INDEX idx_sq_order (survey_id, section, position)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------
-- Opciones
-- -------------------------
CREATE TABLE question_options (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  question_id BIGINT UNSIGNED NOT NULL,
  option_text VARCHAR(200) NOT NULL,
  position INT UNSIGNED NOT NULL DEFAULT 1,
  CONSTRAINT fk_qo_question
    FOREIGN KEY (question_id) REFERENCES questions(id)
    ON DELETE CASCADE,
  INDEX idx_qo_order (question_id, position)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------
-- Respuestas anónimas
-- ip_hash / user_agent_hash para anti-abuso sin guardar IP real
-- -------------------------
CREATE TABLE responses (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  survey_id BIGINT UNSIGNED NOT NULL,
  ip_hash CHAR(64) NULL,
  user_agent_hash CHAR(64) NULL,
  submitted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_responses_survey
    FOREIGN KEY (survey_id) REFERENCES surveys(id)
    ON DELETE CASCADE,
  INDEX idx_responses_survey (survey_id),
  INDEX idx_responses_submitted (survey_id, submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------
-- Respuestas por pregunta
-- text: answer_text lleno y question_option_id NULL
-- single: question_option_id lleno
-- multiple: varias filas (una por opción)
-- -------------------------
CREATE TABLE answers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  response_id BIGINT UNSIGNED NOT NULL,
  question_id BIGINT UNSIGNED NOT NULL,
  question_option_id BIGINT UNSIGNED NULL,
  answer_text TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_answers_response
    FOREIGN KEY (response_id) REFERENCES responses(id)
    ON DELETE CASCADE,

  CONSTRAINT fk_answers_question
    FOREIGN KEY (question_id) REFERENCES questions(id)
    ON DELETE RESTRICT,

  CONSTRAINT fk_answers_option
    FOREIGN KEY (question_option_id) REFERENCES question_options(id)
    ON DELETE SET NULL,

  -- evita repetir la misma opción en una misma respuesta/pregunta (útil para multiple)
  UNIQUE KEY uq_answer_unique_option (response_id, question_id, question_option_id),

  INDEX idx_answers_response (response_id),
  INDEX idx_answers_question (question_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;