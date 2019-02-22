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