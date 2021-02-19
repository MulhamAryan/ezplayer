<?php
    /*
    * EZCAST
    * Copyright (C) 2021 Université libre de Bruxelles
    *
    * Written By Mulham ARYAN <Mulham.Aryan@ulb.be>
    *
    * This software is free software; you can redistribute it and/or
    * modify it under the terms of the GNU Lesser General Public
    * License as published by the Free Software Foundation; either
    * version 3 of the License, or (at your option) any later version.
    *
    * This software is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
    * Lesser General Public License for more details.
    *
    * You should have received a copy of the GNU Lesser General Public
    * License along with this software; if not, write to the Free Software
    * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    */

    include 'config.php';
    require_once $config->directory["library"] . "/upload.php";

    $sys  = new System();
    $auth = new Authentication();
    $tmp  = new Templates();
    $ctrl = new Controller();

    //$up = new Upload(queues_submit_uploads);
    /*$file = array( "cam" => array("name" => "test"));
    var_dump($up->uploadFile($file,1));
*/
    if ($auth->isLogged()){
        $ctrl->load("index");
    }
    else{
        $ctrl->load("login");
    }
