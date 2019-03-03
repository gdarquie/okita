<?php

namespace App\Component\SQL;


class ActionSQL
{
    //todo : à remplacer par les fonctions de routines

    /**
     * @return string
     */
    public function getInsertAction()
    {
        $sql = <<< EOT
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
        EOT;

        return $sql;
    }

    /**
     * @return string
     */
    public function getGenerateAction()
    {
        $sql = <<< EOT
        CREATE OR REPLACE FUNCTION generate_actions(start bigint, finish bigint)
          RETURNS VOID AS $$
        DECLARE
          duration BIGINT;
        BEGIN
          duration := ROUND((finish-start) / 10);
          LOOP
        --     RAISE NOTICE 'Value: %', count;
            PERFORM generate_turn_actions(start);
            start := start+10;
        
            IF start > finish THEN
                EXIT;  -- exit loop
            END IF;
          END LOOP;
        END
        $$
        LANGUAGE plpgsql;
        EOT;

        return $sql;
    }
}