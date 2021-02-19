<?php
    require_once $this->config->directory["config"] . "/ldap.php";
    foreach ($ldapconfig as $ldap){
        if($ldap["enabled"] == true){
            $ldap_connect = ldap_connect($ldap["hostname"]);
            @ldap_set_option($ldap_connect, LDAP_OPT_PROTOCOL_VERSION, $ldap["version"]);
            @$ldap_bind = ldap_bind($ldap_connect,$ldap["bind_rdn"],$ldap["password"]);
            if($ldap_bind == true){
                @$ldap_bind_userinfo = ldap_bind($ldap_connect,"{$ldap["filter"]}={$username},{$ldap["base_dn"]}",$userpass);
                if($ldap_bind_userinfo == true) {
                    $filter = "({$ldap["filter"]}=$username)";
                    $ldap_search = ldap_search($ldap_connect, $ldap["base_dn"], $filter, array("sn", "supannaliaslogin", "givenname", "mail")) or die("can not search");
                    $entities = ldap_get_entries($ldap_connect, $ldap_search);

                    $forename = $entities[0]["givenname"][0];
                    $surname  = $entities[0]["sn"][0];
                    $usermail = $entities[0]["mail"][0];
                    //Create user if not in DB
                    if($userinfo == false && $ldap_bind_userinfo == true){
                        $userdata = array(
                            "user_ID" => $username,
                            "surname" => $surname,
                            "forename" => $forename,
                            "passwd" => "",
                            "usermail" => $usermail,
                            "recorder_passwd" => "",
                            "permissions" => 0,
                            "origin" => "ldap"
                        );
                        $this->createUser($userdata);
                    }
                    else{
                        //$this->syncEnrollment($username,"ldap");
                    }
                    return true;
                    break;
                }
                else{
                    return false;
                }
            }
        }
    }