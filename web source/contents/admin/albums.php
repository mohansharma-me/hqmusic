<div class="window">
    <div class='header'>
        <?php
            include_once "./contents/admin/icon-links.php";
        ?>
    </div>
    <div class='content'>
        <table class="table1">
            <tr class="buttons">
				<td>
					<form class="form1" action='javascript:void' onsubmit="$('#album-searcher').click()">
                        <div class='lefty'>
                            <label>Search:</label>
                            <input type='text' id='album-slug' style='padding:5px 10px' placeholder="by slug, album name, sub or main category name" /> <input type='button' id='album-searcher' value='Search' />
                        </div>
                        <input class="righty" type="button" value="NEW" onClick="document.location='/admin/albums/new'" />
						<input class="righty editCat" onClick="$.editAlbums()" type="button" value="EDIT" /> 
						<input class="righty deleteCat" type="button" onClick="$.deleteAlbums()" value="DELETE" />
                    </form>
                </td>
            </tr>
		</table>
		
        <table class='table1' id="parent_cat_table" style='margin-top:-10px'>
            
        </table>
        
	</div>
</div>