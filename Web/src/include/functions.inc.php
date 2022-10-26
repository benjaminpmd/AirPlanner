<?php
include_once "./include/utils.inc.php";

function get_register_elements(): array {
    return [
        [
            "name" => "Prénom",
            "id_name" => "register-firstname",
            "type" => "text"
        ],
        [
            "name" => "Nom",
            "id_name" => "register-lastname",
            "type" => "text"
        ],
        [
            "name" => "Date de naissance",
            "id_name" => "register-birthday",
            "type" => "date"
        ],
        [
            "name" => "Adresse email",
            "id_name" => "register-email",
            "type" => "email"
        ],
        [
            "name" => "Téléphone portable",
            "id_name" => "register-phone",
            "type" => "tel"
        ],
        [
            "name" => "Adresse postale",
            "id_name" => "register-address",
            "type" => "text"
        ],
        [
            "name" => "Ville",
            "id_name" => "register-city",
            "type" => "text"
        ],
        [
            "name" => "Code postale",
            "id_name" => "register-postal-code",
            "type" => "number"
        ]
    ];
}