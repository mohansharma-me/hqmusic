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
                        <div class='lefty'>
                            <label>Search:</label>
                            <input type='text' id='song-slug' style='padding:5px 10px' placeholder="by slug, name, album name, sub or main category name" /> <input type='button' id='song-searcher' value='Search' />
                        </div>
                        <input class="righty" type="button" value="NEW" onClick="document.location='/admin/songs/new'" />
                        <input class="righty editCat" onClick="$.editSongs()" type="button" value="EDIT" /> 
                        <input class="righty deleteCat" type="button" onClick="$.deleteSongs()" value="DELETE" />
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