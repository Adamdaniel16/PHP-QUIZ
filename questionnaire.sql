CREATE TABLE IF NOT EXISTS question(
            idq int(3) PRIMARY KEY,
            typeq TEXT CHECK(typeq IN ('text', 'radio', 'checkbox')),
            textq VARCHAR(42),
            answer VARCHAR(42),
            score int(3));

CREATE TABLE IF NOT EXISTS choices(
            idc VARCHAR(42) PRIMARY KEY,
            idq int(3),
            textc VARCHAR(42),
            FOREIGN KEY(idq) REFERENCES question(idq));

CREATE TABLE IF NOT EXISTS answers(
            ida VARCHAR(42) PRIMARY KEY,
            idq int(3),
            texta VARCHAR(42),
            FOREIGN KEY(idq) REFERENCES question(idq));