-- random
CREATE OR REPLACE FUNCTION random_between(low BIGINT, high BIGINT)
  RETURNS INT AS
$$
BEGIN
  RETURN floor(random() * (high - low + 1) + low);
END;
$$
language 'plpgsql'
STRICT;

DROP FUNCTION random_between(low INT, high INT);
DROP FUNCTION random_between(low BIGINT, high BIGINT);
