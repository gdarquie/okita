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
    public function getGenerateActionForDay()
    {
        $sql = <<< EOT
        CREATE OR REPLACE FUNCTION generate_actions_for_day(day INT)
          RETURNS VOID AS $$
        DECLARE
          temprow RECORD;
        BEGIN
           FOR temprow IN
                SELECT id, birth_date, death_date FROM character
            LOOP
                PERFORM generate_actions_for_day_and_character(day, temprow.id);
            END LOOP;
        
        END
        $$
        LANGUAGE plpgsql;
        EOT;

        return $sql;
    }

    /**
     * @return string
     */
    public function generate_actions_for_day_and_character()
    {
        $sql = <<< EOT
        CREATE OR REPLACE FUNCTION generate_actions_for_day_and_character(day INT, v_character_id INT) RETURNS VOID AS $$

        DECLARE
          start_day BIGINT;
          end_day BIGINT;
          v_routine RECORD;
          v_habit RECORD;
          v_count INTEGER;
          v_nb_habits INTEGER;
        
        BEGIN
          start_day := (day*(3600*24));
        
          -- commencer par récupérer la dernière action?
          -- ???
        
          -- get routine from character
          v_routine = (SELECT r
            FROM routine r
            INNER JOIN character_routine cr on r.id = cr.routine_id
            WHERE cr.character_id = v_character_id LIMIT 1);
        
          v_nb_habits := (SELECT COUNT(*) FROM habit WHERE routine_id = v_routine.id);
          v_count := 1;
        
          -- get all habits
          -- for each habit, insert action
           FOR v_habit IN
              SELECT name, start, "end" as v_end FROM habit WHERE routine_id = v_routine.id
            LOOP
            PERFORM insert_action(v_habit.name, (start_day + v_habit.start), (start_day + v_habit.v_end), v_character_id);
           v_count := v_count + 1;
            END LOOP;
        
        END;
        $$ LANGUAGE plpgsql;
        EOT;

        return $sql;
    }

    /**
     * @return string
     */
    public function generateActionFromDayToDay()
    {
        $sql = <<< EOT
        CREATE OR REPLACE FUNCTION generateActionFromDayToDay(v_start integer, v_end integer)
        RETURNS VOID AS $$
        DECLARE
          v_count integer;
        BEGIN
          v_count := v_start;
          WHILE v_count <= v_end LOOP
            PERFORM generate_actions_for_day(v_count);
            v_count := v_count +1;
          END LOOP;
        END
        $$
        LANGUAGE plpgsql;
        EOT;

        return $sql;

    }
}