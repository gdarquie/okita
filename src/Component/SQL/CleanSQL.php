<?php

namespace App\Component\SQL;


class CleanSQL
{
    public function getClean()
    {
        $sql = <<< EOT
            CREATE OR REPLACE FUNCTION clean()
            RETURNS VOID AS $$
            BEGIN
              DELETE FROM character CASCADE;
              DELETE FROM habit CASCADE;
              DELETE FROM routine CASCADE;
              DELETE FROM action CASCADE;
            END
            $$ LANGUAGE plpgsql;
        EOT;

        return $sql;

    }
    

    
}