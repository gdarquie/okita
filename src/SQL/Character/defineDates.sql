CREATE OR REPLACE  FUNCTION define_dates()
  RETURNS BIGINT[] AS $$
    DECLARE
      birth BIGINT;
      death BIGINT;
      lifespan BIGINT;
      liferest BIGINT;
      age BIGINT;
      random BIGINT;

    BEGIN
      random := random_between(1,1000);

      -- under 15 years
      IF random <= 179 THEN
        age := random_between(1,14);

      -- 15-19 years
      ELSIF random > 179 AND random <= 241 THEN
        age := random_between(15,19);

      -- 20-24 years
      ELSIF random > 241 AND random <= 297 THEN
        age := random_between(20,24);

      -- 25-29 years
      ELSIF random > 297 AND random <= 354 THEN
        age := random_between(25,29);

      -- 30-34 years
      ELSIF random > 354  AND random <= 416 THEN
        age := random_between(30,34);

      -- 35-39 years
      ELSIF random > 416 AND random <= 479 THEN
        age := random_between(35,39);

      -- 40-44 years
      ELSIF random > 479 AND random <= 540 THEN
        age := random_between(40,44);

      -- 45-49 years
      ELSIF random > 540 AND random <= 608 THEN
        age := random_between(45,49);

      -- 50 -54 years
      ELSIF random > 608 AND random <= 675 THEN
        age := random_between(50,54);

      -- 55-59 years
      ELSIF random > 675 AND random <= 740  THEN
        age := random_between(55,59);

      -- 60-64 years
      ELSIF random > 740 AND random <= 801 THEN
        age := random_between(60,64);

      -- 65-69 years
      ELSIF random > 801 AND random <= 860 THEN
        age := random_between(65,69);

      -- 70-74 years
      ELSIF random > 860 AND random <= 899 THEN
        age := random_between(70,74);

      -- 75-79 years
      ELSIF random > 899 AND random <= 950 THEN
        age := random_between(75,79);

      -- 80-84 years
      ELSIF random > 950 AND random <= 985 THEN
        age := random_between(80,84);

      -- 85-89 years
      ELSIF random > 985 AND random <= 995 THEN
        age := random_between(85,89);

      -- 90-94 years
      ELSIF random > 995 AND random <= 999 THEN
        age := random_between(90,94);

      -- more than 94 years
      ELSE
        age := random_between(95,100);
      END IF;

      birth := (0 - (age*31536000));

      liferest := random_between(age, 100);
      liferest := (liferest*31536000);

      -- death date
      death := (birth+liferest);

      -- specify a time in the year
      birth := (random_between(0,31535999)+birth);
      death := (random_between(0,31535999)+death);

    RETURN ARRAY[birth, death];

    END
  $$
LANGUAGE plpgsql;