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
        CREATE OR REPLACE FUNCTION build_routine(v_name VARCHAR(255), v_habits TEXT[])
        RETURNS VOID AS $$
        
        DECLARE
          v_routine RECORD;
          v_length INT;
          v_count INT;
        BEGIN
        
            -- create routine
            INSERT INTO routine (name, created_at, updated_at, uuid) VALUES (v_name, NOW(), NOW(), uuid_generate_v4());
        
            -- get routine into v_routine
            SELECT * INTO v_routine FROM routine ORDER BY created_at DESC LIMIT 1;
        
            -- validation system : must last 24h & no time superposition
            -- todo
        
            -- prepare loop for habits creation
            v_count :=1;
            v_length := (SELECT array_length(v_habits, 1));
        
            -- create habits
             WHILE v_count <= v_length LOOP
        
               PERFORM * FROM create_habit(v_routine.id, (v_habits[v_count][1]::VARCHAR(255)), (v_habits[v_count][2]::int), (v_habits[v_count][3]::int));
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