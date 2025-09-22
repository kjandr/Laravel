<?php

namespace App\Data;

class ConfAppFields
{
    /**
     * Metadaten für APP Felder
     */

    // 1) Definiere hier in einem Array alle statischen Metadaten je Feld:
    public const METADATA = [
        "controller_id" => [ "type" => "int", "suffix" => "id", "min" => 0, "max" => 255 ]
    ];

    // 2) Und hier das Mapping: Original-Key → Alias-Name + welche Meta-Properties mitkommen
    public const FIELD_MAP = [
        "controller_id" => [ "alias" => "ContrId", "meta" => ["type","min","max","suffix"] ]
    ];
}