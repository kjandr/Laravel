<?php
// 1) Definiere hier in einem Array alle statischen Metadaten je Feld:
$METADATA_APP = [
    "controller_id" => [ "type" => "int", "suffix" => "id", "min" => 0, "max" => 255 ]
];

// 2) Und hier das Mapping: Original-Key → Alias-Name + welche Meta-Properties mitkommen
$FIELD_MAP_APP = [
    "controller_id" => [ "alias" => "ContrId", "meta" => ["type","min","max","suffix"] ]
];

return [
    "METADATA_APP" => $METADATA_APP,
    "FIELD_MAP_APP" => $FIELD_MAP_APP
];
