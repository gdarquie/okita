<?php

namespace App\Component\SQL;

class CharacterSQL
{
    /**
     * @return string
     */
    public function getdefineDates()
    {
        $sql =  <<<EOT
        CREATE OR REPLACE  FUNCTION define_dates()
          RETURNS BIGINT[] AS $$
            DECLARE
              birth BIGINT;
              death BIGINT;
              lifespan BIGINT;
              liferest BIGINT;
              age BIGINT;
              random BIGINT;
        
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
        
              birth := (0 - (age*31536000));
        
              liferest := random_between(age, 100);
              liferest := (liferest*31536000);
        
              -- death date
              death := (birth+liferest);
        
              -- specify a time in the year
              birth := (random_between(0,31535999)+birth);
              death := (random_between(0,31535999)+death);
        
            RETURN ARRAY[birth, death];
        
            END
          $$
        LANGUAGE plpgsql;
        EOT;

        return $sql;

    }

    /**
     * @return string
     */
    public function getDefineGender()
    {
        $sql = <<<EOT
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
        EOT;

        return $sql;
    }

    /**
     * @return string
     */
    public function getdefineSex()
    {
        $sql = <<<EOT
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
        EOT;

        return $sql;
    }

    /**
     * @return string
     */
    public function generateCharacters()
    {
        $sql = <<<EOT
        CREATE OR REPLACE FUNCTION generate_characters(count integer)
          RETURNS VOID AS
          $$
          DECLARE
            counter INTEGER := 0;
            dates BIGINT ARRAY[2];
            character_id INTEGER;
            routines_id INTEGER[];
            nb_routines INTEGER;
            random_number INTEGER;
            routine_id INTEGER;
          BEGIN
            WHILE counter < count
              LOOP
                -- insert a character
                dates := (SELECT define_dates());
                INSERT INTO character (name, sex, gender, birth_date, death_date, uuid, created_at, updated_at) VALUES (generate_name(), define_sex(), define_gender(), dates[1], dates[2], uuid_generate_v4(),NOW(), NOW());
        
                -- insert relation with routine
                character_id := (SELECT id FROM character ORDER BY id DESC LIMIT 1);
        
                -- select random routine
                routines_id := (SELECT array_agg(id) FROM routine);
                nb_routines := (SELECT array_length(routines_id, 1));
                random_number := (SELECT random_between(1, nb_routines));
                routine_id := (SELECT routines_id[random_number]);
        
                -- create routine
                INSERT INTO character_routine (character_id, routine_id)
                  VALUES (character_id, routine_id);
        
                counter := counter + 1;
              END LOOP;
          END;
          $$ LANGUAGE plpgsql;
        EOT;

        return $sql;
    }

    /**
     * @return string
     */
    public function generate_name()
    {
        $sql = <<<EOT
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
        EOT;

        return $sql;
    }

    /**
     * @return string
     */
    public function getBirthdayByDecades()
    {
        $sql = <<<EOT
        CREATE OR REPLACE FUNCTION get_birthdate_by_decades(var_year_min INT, var_year_max INT)
          RETURNS TEXT[] AS
          $$
          DECLARE
            var_result TEXT ARRAY;
            var_total_decades BIGINT;
            var_counter BIGINT;
            var_year_in_sec BIGINT;
          BEGIN
            var_counter := 0;
            var_total_decades := (SELECT CEIL(ABS(ROUND((var_year_max-var_year_min)/10)) )); -- total of decades round up
            var_year_in_sec := (3600*24*365);
            WHILE var_counter < var_total_decades
            LOOP
              RAISE NOTICE 'Min = %', var_year_in_sec*(var_year_min+(var_counter));
              RAISE NOTICE 'Max = %', var_year_in_sec*(var_year_min+(var_counter+1)*10);
              var_result[var_counter] := (
                SELECT json_build_object(
                  count(id),
                  ABS(var_year_in_sec*(var_year_min+(var_counter*10)))
                  )
                FROM character
                    WHERE birth_date
                      BETWEEN var_year_in_sec*(var_year_min+(var_counter*10)) AND var_year_in_sec*(var_year_min+(var_counter+1)*10)
                 )
              ;
              var_counter = var_counter +1;
            END LOOP;
            RETURN var_result;
          END
          $$ LANGUAGE plpgsql;
        EOT;
        return $sql;
    }
}