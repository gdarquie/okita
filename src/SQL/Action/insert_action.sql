CREATE OR REPLACE FUNCTION insert_action(actionName text, current bigint, end_turn bigint, var_character_id INT)
  RETURNS VOID AS $$

  BEGIN
      INSERT INTO action (title, start_at, end_at, uuid, created_at, updated_at, character_id)
      SELECT actionName, current, end_turn, uuid_generate_v4(), NOW(),NOW(), var_character_id
      WHERE NOT EXISTS (
            SELECT id FROM action WHERE character_id = var_character_id AND start_at = current
          )
        ;
  END

  $$
LANGUAGE plpgsql;