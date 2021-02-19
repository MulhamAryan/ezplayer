<?php
/*
    1- Update users table ->
        ALTER TABLE `ezcast_users` ADD `id` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
        ALTER TABLE `ezcast_users` ADD `updated_password` BIT(1) NULL DEFAULT b'0' AFTER `origin`;

    2- Insert enrolement table
        CREATE TABLE `ezcast_enrollment` ( 
        `id` bigint NOT NULL,
        `userid` bigint NOT NULL,
        `courseid` bigint NOT NULL,
        `role` int NOT NULL DEFAULT '1',
        `enrolstart` bigint NOT NULL,
        `enrolend` bigint NOT NULL,
        `timecode` bigint NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ALTER TABLE `ezcast_enrollment` ADD PRIMARY KEY (`id`);
        ALTER TABLE `ezcast_enrollment` MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; COMMIT;

    3- Create cache dirs (ezcastcache/(users_cache,courses_cache))

    4- Update Courses table
        A- To convert date_created to string fields
            ALTER TABLE `ezcast_courses` CHANGE `date_created` `date_created` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
        B- Convert date_created value to timestamp
            Execute 'php upgrade_script/courses_table.php'
        C- Remove old PRIMARY KEY
            ALTER TABLE `ezcast_courses` DROP PRIMARY KEY;
        D- Add token,downloadable,anon_access fields
            ALTER TABLE `ezcast_courses` ADD `token` VARCHAR(10) NOT NULL AFTER `shortname`;
            ALTER TABLE `ezcast_courses` ADD `downloadable` BOOLEAN NOT NULL DEFAULT TRUE AFTER `has_albums`;
            ALTER TABLE `ezcast_courses` ADD `anon_access` BOOLEAN NOT NULL DEFAULT FALSE AFTER `downloadable`;
        E- Create new primary key 'ID'
            ALTER TABLE `ezcast_courses` ADD `id` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);

    5- Creating recorders_course system
        A- Add ezcast_records table
            CREATE TABLE `ezcast_records` (
              `id` bigint NOT NULL,
              `title` varchar(500) NOT NULL,
              `description` text NOT NULL,
              `origin` varchar(20) NOT NULL,
              `user_id` bigint NOT NULL,
              `course_id` bigint NOT NULL,
              `token` varchar(10) NOT NULL,
              `album` tinyint NOT NULL,
              `filepath` varchar(250) NOT NULL,
              `record_type` varchar(10) NOT NULL,
              `status` tinyint NOT NULL,
              `add_title` varchar(250) NOT NULL,
              `downloadable` tinyint(1) NOT NULL,
              `duration` bigint NOT NULL,
              `addtime` bigint NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

            ALTER TABLE `ezcast_records` ADD PRIMARY KEY (`id`);
            ALTER TABLE `ezcast_records` MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
        B- Write script converting old album recorder to new system //TODO

    6- Create comments table 'ezcast_comment'

*/