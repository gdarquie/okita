<?php
/**
 * Created by PhpStorm.
 * User: gaotian
 * Date: 2019-02-25
 * Time: 00:49
 */

namespace App\SQL\Character;


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