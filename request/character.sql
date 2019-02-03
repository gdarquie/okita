-- to be able to generate 1000M character in less than 1 min
CREATE OR REPLACE FUNCTION generate_characters(count integer)
  RETURNS VOID AS
  $$
  DECLARE
    counter integer := 0;
    dates bigint ARRAY[2];
  BEGIN
    WHILE counter < count
      LOOP
        dates := (SELECT define_dates());
        INSERT INTO character (name, sex, gender, birth_date, death_date, uuid, created_at, updated_at) VALUES (generate_name(), define_sex(), define_gender(), dates[1], dates[2], uuid_generate_v4(),NOW(), NOW());
        counter := counter + 1;
      END LOOP;
  END;
  $$ LANGUAGE plpgsql;

-- define name
CREATE OR REPLACE FUNCTION generate_name()
  RETURNS VARCHAR AS
  $$
  DECLARE
    name VARCHAR;
    result VARCHAR;
    random INTEGER;
    syllables_count INTEGER;
    counter INTEGER := 0;
    syllables TEXT[];
    syllables_size INTEGER;
  BEGIN
    syllables := '{ sa, so, si, ca, co, ce, ta, to, mi, ma, mu, ta, la, lai, re, ata, nu, no, mo, lo, do, da, du, di, de }';

    syllables_size := array_length(syllables, 1);

    random := random_between(1,10000);
    IF random < 1000 THEN
      syllables_count := 1;
      -- 1 syllabe
    ELSIF random > 1000 AND random < 7500 THEN
      syllables_count := 2;
      -- 2 syllabes
    ELSIF random > 7500 AND random < 9900 THEN
      syllables_count := 3;
      -- 3 syllabes
    ELSIF random > 9900 AND random < 9998 THEN
      syllables_count := 4;
      -- 4 syllabes
    ELSIF random > 9998 AND random < 9999 THEN
      syllables_count := 5;
      -- 5 syllabes
    ELSE
      syllables_count := 6;
      -- 6 syllabes
    END IF;

    WHILE counter < syllables_count
      LOOP
        IF name IS NULL THEN
          name := syllables[random_between(1,syllables_size)];
        ELSE
          name := name || syllables[random_between(1,syllables_size)];
        END IF;
        counter := counter + 1;
      END LOOP;
    result := INITCAP(name);

    RETURN result;
  END
  $$ LANGUAGE plpgsql;

SELECT generate_characters(10000);
SELECT name, COUNT(*) as nb FROM character GROUP BY name ORDER BY nb DESC;

-- define sex

CREATE OR REPLACE FUNCTION define_sex()
  RETURNS VARCHAR AS
  $$
  DECLARE
    sex VARCHAR;
    random INT;
  BEGIN
    random := (SELECT random() * 100 + 1);
    IF random > 50 THEN
      sex := 'F';
    ELSE
      sex := 'M';
    END IF;
    RETURN sex;
  END
  $$ LANGUAGE plpgsql;


-- define gender

CREATE OR REPLACE FUNCTION define_gender()
  RETURNS VARCHAR AS
  $$
  DECLARE
    gender VARCHAR;
  BEGIN
    gender := 'NA';
    RETURN gender;
  END
  $$ LANGUAGE plpgsql;

-- generateDates()
CREATE OR REPLACE  FUNCTION define_dates()
  RETURNS BIGINT[] AS $$
    DECLARE
      birth BIGINT;
      death BIGINT;
      lifespan BIGINT;
      liferest BIGINT;
      age INT;
      random INT;

    BEGIN
      random := random_between(1,1000);

      -- under 15 years
      IF random <= 179 THEN
        age := random_between(1,14);

      -- 15-19 years
      ELSIF random > 179 AND random <= 241 THEN
        age := random_between(15,19);

      -- 20-24 years
      ELSIF random > 241 AND random <= 297 THEN
        age := random_between(20,24);

      -- 25-29 years
      ELSIF random > 297 AND random <= 354 THEN
        age := random_between(25,29);

      -- 30-34 years
      ELSIF random > 354  AND random <= 416 THEN
        age := random_between(30,34);

      -- 35-39 years
      ELSIF random > 416 AND random <= 479 THEN
        age := random_between(35,39);

      -- 40-44 years
      ELSIF random > 479 AND random <= 540 THEN
        age := random_between(40,44);

      -- 45-49 years
      ELSIF random > 540 AND random <= 608 THEN
        age := random_between(45,49);

      -- 50 -54 years
      ELSIF random > 608 AND random <= 675 THEN
        age := random_between(50,54);

      -- 55-59 years
      ELSIF random > 675 AND random <= 740  THEN
        age := random_between(55,59);

      -- 60-64 years
      ELSIF random > 740 AND random <= 801 THEN
        age := random_between(60,64);

      -- 65-69 years
      ELSIF random > 801 AND random <= 860 THEN
        age := random_between(65,69);

      -- 70-74 years
      ELSIF random > 860 AND random <= 899 THEN
        age := random_between(70,74);

      -- 75-79 years
      ELSIF random > 899 AND random <= 950 THEN
        age := random_between(75,79);

      -- 80-84 years
      ELSIF random > 950 AND random <= 985 THEN
        age := random_between(80,84);

      -- 85-89 years
      ELSIF random > 985 AND random <= 995 THEN
        age := random_between(85,89);

      -- 90-94 years
      ELSIF random > 995 AND random <= 999 THEN
        age := random_between(90,94);

      -- more than 94 years
      ELSE
        age := random_between(95,100);
      END IF;

      birth := age*31536000;
      birth := (0 - birth);

      -- span life
      lifespan := random_between(1,100);
      lifespan := (lifespan*31536000);

      IF lifespan < (age*31536000) THEN
        liferest := (100-age);
        lifespan := random_between(1,liferest);
        lifespan := (lifespan*31536000);
      END IF;

    -- death date
    death := lifespan;

    RETURN ARRAY[birth, death];

    END
  $$
LANGUAGE plpgsql;

SELECT define_dates();
-- todo : declare bigint!



-- misceallenaous

EXPLAIN ANALYSE
SELECT generate_characters(1000000);
SELECT generate_characters(100000);
SELECT generate_characters(10);


-- SELECT
SELECT COUNT(*) FROM character;

SELECT sex, COUNT(*) FROM character GROUP BY sex;
SELECT birth_date, COUNT(*) FROM character GROUP BY birth_date;
SELECT name, COUNT(*) FROM character GROUP BY name;

-- clean

DROP TABLE action;

DROP TABLE character CASCADE;
DROP FUNCTION generate_characters(integer);

CREATE TABLE character (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, sex VARCHAR(1) NOT NULL, gender VARCHAR(2) DEFAULT NULL, birth_date BIGINT NOT NULL, death_date BIGINT NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id));

SELECT floor(random() * 10 + 1)::int;

TRUNCATE character;


SELECT * FROM character WHERE name ILIKE 'atalaire';
SELECT * FROM character WHERE name ILIKE ' ';

-- TODO : faire le système de génération des dates

