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