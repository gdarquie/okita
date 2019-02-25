<?php

namespace App\Component\SQL;


class HelperSQL
{
    public function getRandomBetween()
    {
        $sql = <<<EOT
        CREATE OR REPLACE FUNCTION random_between(low BIGINT, high BIGINT)
          RETURNS INT AS
        $$
        BEGIN
          RETURN floor(random() * (high - low + 1) + low);
        END;
        $$
        language 'plpgsql'
        STRICT;
        EOT;

        return $sql;
    }
}