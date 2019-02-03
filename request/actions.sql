

CREATE TABLE action (id SERIAL NOT NULL, character_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, start_at INT NOT NULL, end_at INT NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(character_id, start_at,title));

SELECT * FROM character;
SELECT * FROM action ORDER BY id DESC LIMIT 3 ;

CREATE OR REPLACE FUNCTION insertAction(actionName text, current bigint, end_turn bigint)
  RETURNS VOID AS $$
  BEGIN
      INSERT INTO action (title, start_at, end_at, uuid, created_at, updated_at, character_id)
      SELECT actionName, current, end_turn, uuid_generate_v4(), NOW(),NOW(), temprow.id
      WHERE NOT EXISTS (
            SELECT id FROM action WHERE character_id = temprow.id AND title = actionName
          );
  END
  $$
LANGUAGE plpgsql;

-- Generate actions by turns for all characters
CREATE OR REPLACE FUNCTION generateTurnActions(start bigint, finish bigint)
  RETURNS VOID AS
$$
DECLARE
  current BIGINT;
  end_turn BIGINT;
  temprow RECORD;

BEGIN
  -- get old character birthdate and set in start
--   start := (SELECT MIN(birth_date) FROM character);
--   finish := (SELECT MAX(death_date) FROM character);
  current := start;
  end_turn := current + 60;

  -- find all characters born this turn
  FOR temprow IN
        SELECT id FROM character WHERE birth_date = current
    LOOP
      SELECT insertAction('naissance', current, end_turn);
    END LOOP;

  -- find all characters dying during this turn
  FOR temprow IN
        SELECT id FROM character WHERE death_date = current
    LOOP
      SELECT insertAction('mort', current, end_turn);
    END LOOP;

    -- find all others characters living at this time
    FOR temprow IN
        SELECT id FROM character WHERE birth_date > current AND death_date < current
    LOOP
       SELECT insertAction('agir', current, end_turn);
    END LOOP;

END
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION generateAction(start bigint, finish bigint)
  RETURNS VOID AS $$
BEGIN

  -- TODO : faire une boucle pour tous les tours pendant la durée finish - start, à chaque itértion, on appelle generateTurnActions($time)
END
$$
LANGUAGE plpgsql;

SELECT generateActions(0, 86400); -- pour une journée


SELECT COUNT(*) FROM character;
SELECT * FROM action;
SELECT COUNT(*) FROM action;

TRUNCATE TABLE action;