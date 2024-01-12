CREATE TABLE IF NOT EXISTS question(
            nameq VARCHAR(42) PRIMARY KEY,
            typeq TEXT CHECK(typeq IN ('text', 'radio', 'checkbox')),
            textq VARCHAR(42),
            answer VARCHAR(42),
            score int(3));

CREATE TABLE IF NOT EXISTS choices(
            idc VARCHAR(42) PRIMARY KEY,
            nameq VARCHAR(42),
            textc VARCHAR(42),
            FOREIGN KEY(nameq) REFERENCES question(nameq));

CREATE TABLE IF NOT EXISTS answers(
            ida VARCHAR(42) PRIMARY KEY,
            nameq VARCHAR(42),
            texta VARCHAR(42),
            FOREIGN KEY(nameq) REFERENCES question(nameq));