<?php

namespace App\Component\SQL;


class RoutineSQL
{
    /**
     * @return string
     */
    public function getBuildRoutine()
    {
        $sql = <<<EOT
        CREATE OR REPLACE FUNCTION build_routine(v_name VARCHAR(255), v_habits JSON)
        RETURNS VOID AS $$
        
        DECLARE
          v_routine RECORD;
          v_length INT;
          v_count INT;
        BEGIN
        
            -- create routine
            INSERT INTO routine (name, created_at, updated_at, uuid) VALUES (v_name, NOW(), NOW(), uuid_generate_v4());
        
            -- get routine into v_routine
            SELECT * INTO v_routine FROM routine WHERE name = v_name;
        
            -- validation system : must last 24h & no time superposition
            -- todo
        
            -- prepare loop for habits creation
            v_count :=0;
        --     v_habits := (SELECT json_array_elements_text(v_habits));
            v_length := (SELECT json_array_length(v_habits));
        
            -- create habits
             WHILE v_count < v_length LOOP
                    PERFORM * FROM create_habit(v_routine.id, (v_habits::json->v_count::int->0), (v_habits::json->v_count::int->1), (v_habits::json->v_count::int->2));
        
                v_count:= v_count+1;
             END LOOP;
        
        END
        
        $$ LANGUAGE plpgsql;
        EOT;

        return $sql;
    }

    /**
     * @return string
     */
    public function getCreateHabit()
    {
        $sql = <<<EOT
            CREATE OR REPLACE FUNCTION create_habit(v_routine integer, v_name varchar(255), begining integer, ending integer)
            RETURNS VOID AS $$
            BEGIN
              INSERT INTO habit (name, routine_id, created_at, updated_at, start, "end", uuid)
              VALUES (v_name, v_routine, NOW(), NOW(), begining, ending, uuid_generate_v4());
            END
            $$ LANGUAGE plpgsql;
        EOT;

        return $sql;

    }
}