<?php

// Status codes
CondDefine('STATUS_OK', 0);
// SQL errors
CondDefine('STATUS_SQL_ERROR', 1000);
// restserver errors
CondDefine('STATUS_INVALID_ACTION', 2000);
// sendmail errors
CondDefine('STATUS_TEMPLATE_CAN_NOT_LOADED', 3000);
// SongContestWeb errors
CondDefine('STATUS_USERNAME_IS_ALREADY_EXISTS', 30000);
CondDefine('STATUS_EMAIL_IS_ALREADY_EXISTS', 30001);
CondDefine('STATUS_EMAIL_IS_ALREADY_ACTIVATED', 30002);
CondDefine('STATUS_EMAIL_NOT_FOUND', 30003);
CondDefine('STATUS_USER_NOT_FOUND', 30004);

// status message language keys
CondDefine('STATUS', array(
    STATUS_OK => 'STATUS_OK',
    STATUS_SQL_ERROR => 'STATUS_SQL_ERROR',
    STATUS_INVALID_ACTION => 'STATUS_INVALID_ACTION',
    STATUS_USERNAME_IS_ALREADY_EXISTS => 'STATUS_USERNAME_IS_ALREADY_EXISTS',
    STATUS_EMAIL_IS_ALREADY_EXISTS => 'STATUS_EMAIL_IS_ALREADY_EXISTS',
    STATUS_EMAIL_IS_ALREADY_ACTIVATED => 'STATUS_EMAIL_IS_ALREADY_ACTIVATED',
    STATUS_EMAIL_NOT_FOUND => 'STATUS_EMAIL_NOT_FOUND',
    STATUS_USER_NOT_FOUND => 'STATUS_USER_NOT_FOUND',
    STATUS_TEMPLATE_CAN_NOT_LOADED => 'STATUS_TEMPLATE_CAN_NOT_LOADED',
));