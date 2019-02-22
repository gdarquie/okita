-- able to generate 1M character in less than 1 min
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