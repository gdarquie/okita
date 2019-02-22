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