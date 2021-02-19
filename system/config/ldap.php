<?php

    $ldapconfig = array(
        "server_1" => array(
            "enabled"  => true,
            "hostname" => "ldaps://ldap.ulb.ac.be/",
            "bind_rdn" => "cn=podcast,ou=admin,o=bfucc",
            "base_dn"  => "ou=ulb,ou=people,o=bfucc",
            "filter"   => "uid",
            "version"  => 3,
            "password" => "X5_91_zz"
        ),
        "server_2" => array(
            "enabled"  => false,
            "hostname" => "ldap-id.ulb.ac.be",
            "bind_rdn" => "cn=biops,ou=admin,dc=ulb,dc=ac,dc=be",
            "base_dn"  => "ou=people,dc=ulb,dc=ac,dc=be",
            "filter"   => "uid",
            "version"  => 3,
            "password" => '$_b1P0_$'
        )
    );