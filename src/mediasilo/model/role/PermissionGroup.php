<?php

namespace mediasilo\model\role;

class PermissionGroup {

    private $displayName;
    private $groupIdentifier;
    private $permissions;

    function __construct($displayName, $groupIdentifier, array $permissions)
    {
        $this->displayName = $displayName;
        $this->groupIdentifier = $groupIdentifier;
        $this->permissions = $permissions;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function setGroupIdentifier($groupIdentifier)
    {
        $this->groupIdentifier = $groupIdentifier;
    }

    public function getGroupIdentifier()
    {
        return $this->groupIdentifier;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }
}