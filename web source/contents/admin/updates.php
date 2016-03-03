<div class="window">
    <div class='header'>
        <?php
            include_once "./contents/admin/icon-links.php";
        ?>
    </div>
    <div class='content'>
        <table class="table1">
            <tr class="buttons">
                <td colspan="3">
                    <form class="form1" action='javascript:void' onsubmit="$('#song-searcher').click()">
                        <input class="lefty" type="button" value="NEW" onClick="document.location='/admin/updates/new'" />
                        <input class="righty editUpdate" onClick="$.editUpdates()" type="button" value="EDIT" /> 
                        <input class="righty deleteUpdate" type="button" onClick="$.deleteUpdates()" value="DELETE" />
                    </form>
                </td>
            </tr>
        </table>
        <form class='form1'>
        <table class='table1' id="parent_cat_table" style='margin-top:-10px'>
            
        </table>
        </form>
    </div>
</div>