CREATE OR REPLACE FUNCTION build_routine.sql(v_name VARCHAR(255), v_habits TEXT[])
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

    -- validation system : must last 24h & no time supperposition

    -- create habits
    WHILE v_count <= v_length LOOP
        PERFORM * FROM create_habit(v_routine.id, (v_habits[v_count][1]::VARCHAR(255)), (v_habits[v_count][2]::int), (v_habits[v_count][3]::int));
        v_count:= v_count+1;
    END LOOP;

END

$$ LANGUAGE plpgsql;