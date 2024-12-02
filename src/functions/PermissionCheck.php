<?php

function isAllowedToProceed($role_perms)
{
    if (isset($role_perms) && is_array($role_perms)) {
        return in_array($_SESSION['role'], $role_perms);
    }
}
