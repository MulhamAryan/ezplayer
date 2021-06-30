<?php
    $config->admin = array(
        "git_renderers" => "https://github.com/zpqrtbnk/test-repo.git",
        "rsa_key_file"  => "/home/ezcast/.ssh/id_rsa.pub",
        "rsa_private_key" => "/home/ezcast/.ssh/id_rsa"
    );
    $config->cli = array(
        "ssh" => "/usr/bin/ssh",
        "which" => "/usr/bin/which"
    );