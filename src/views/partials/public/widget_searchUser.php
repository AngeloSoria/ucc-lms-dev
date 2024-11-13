
<?php

class SearchUser
{
    public function getWidget($dataContainerID)
    {
        return 
        <<<HTML
        <!-- Search box  -->
        <div class="system_input-box-container" id="widget_searchUser" data-container-id="$dataContainerID">
            <input class="form-control system_input-box" type="search" name="search_user" id="input_search-$dataContainerID" placeholder="Search Name or User Id">
            <div class="drop-container border shadow" id="drop_container-$dataContainerID">
                <!-- Searched item will be loaded here. -->
            </div>
        </div>

        HTML;
    }
}

?>
