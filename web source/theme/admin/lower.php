            </div>
        </div>
        <script type='text/javascript' src='/theme/jquery.js'></script>
    <script type='text/javascript' src='/theme/jquery-ui.js'></script>
        <script>
        $.checkCount=0;
        $.deleteParentCategories=function() {
            var table=$("#parent_cat_table");
            var ids="";
            $(table).find(".parent_cat_chk").each(function(i,e) {
                if($(e).prop("checked")) {
                    if(ids==="") {
                        ids+=$(e).attr("catid");
                    } else {
                        ids+=","+$(e).attr("catid");
                    }
                }
            });
            if(ids==="") {
                alert("Please select atleast one category to delete it.");
            } else {
                document.location="/admin/categories/delete?ids="+ids;
            }
        };
        
        $.deleteSubCategories=function() {
            var table=$("#parent_cat_table");
            var ids="";
            $(table).find(".parent_cat_chk").each(function(i,e) {
                if($(e).prop("checked")) {
                    if(ids==="") {
                        ids+=$(e).attr("catid");
                    } else {
                        ids+=","+$(e).attr("catid");
                    }
                }
            });
            if(ids==="") {
                alert("Please select atleast one sub-category to delete it.");
            } else {
                document.location="/admin/sub-categories/delete?ids="+ids;
            }
        };
        
        $.deleteAlbums=function() {
            var table=$("#parent_cat_table");
            var ids="";
            $(table).find(".parent_cat_chk").each(function(i,e) {
                if($(e).prop("checked")) {
                    if(ids==="") {
                        ids+=$(e).attr("catid");
                    } else {
                        ids+=","+$(e).attr("catid");
                    }
                }
            });
            if(ids==="") {
                alert("Please select atleast one album to delete it.");
            } else {
                document.location="/admin/albums/delete?ids="+ids;
            }
        };
        
        $.deleteSongs=function() {
            var table=$("#parent_cat_table");
            var ids="";
            $(table).find(".parent_cat_chk").each(function(i,e) {
                if($(e).prop("checked")) {
                    if(ids==="") {
                        ids+=$(e).attr("catid");
                    } else {
                        ids+=","+$(e).attr("catid");
                    }
                }
            });
            if(ids==="") {
                alert("Please select atleast one song to delete it.");
            } else {
                document.location="/admin/songs/delete/?ids="+ids;
            }
        };
        
        $.editParentCategories=function() {
            var table=$("#parent_cat_table");
            var ids="";
            var count=0;
            $(table).find(".parent_cat_chk").each(function(i,e) {
                if($(e).prop("checked")) {
                    count++;
                    if(ids==="") {
                        ids+=$(e).attr("catid");
                    } else {
                        ids+=","+$(e).attr("catid");
                    }
                }
            });
            if(ids==="") {
                alert("Please select atleast one category to edit it.");
            } else {
                if(count>1) {
                    alert("Please select only one category to edit.");
                } else {
                    document.location="/admin/categories/edit?id="+ids;
                }
            }
        };
        
        $.editSubCategories=function() {
            var table=$("#parent_cat_table");
            var ids="";
            var count=0;
            $(table).find(".parent_cat_chk").each(function(i,e) {
                if($(e).prop("checked")) {
                    count++;
                    if(ids==="") {
                        ids+=$(e).attr("catid");
                    } else {
                        ids+=","+$(e).attr("catid");
                    }
                }
            });
            if(ids==="") {
                alert("Please select atleast one sub-category to edit it.");
            } else {
                if(count>1) {
                    alert("Please select only one sub-category to edit.");
                } else {
                    document.location="/admin/sub-categories/edit?id="+ids;
                }
            }
        };
        
        $.editAlbums=function() {
            var table=$("#parent_cat_table");
            var ids="";
            var count=0;
            $(table).find(".parent_cat_chk").each(function(i,e) {
                if($(e).prop("checked")) {
                    count++;
                    if(ids==="") {
                        ids+=$(e).attr("catid");
                    } else {
                        ids+=","+$(e).attr("catid");
                    }
                }
            });
            if(ids==="") {
                alert("Please select atleast one album to edit it.");
            } else {
                if(count>1) {
                    alert("Please select only one album to edit.");
                } else {
                    document.location="/admin/albums/edit?id="+ids;
                }
            }
        };
        
        $.editSongs=function() {
            var table=$("#parent_cat_table");
            var ids="";
            var count=0;
            $(table).find(".parent_cat_chk").each(function(i,e) {
                if($(e).prop("checked")) {
                    count++;
                    if(ids==="") {
                        ids+=$(e).attr("catid");
                    } else {
                        ids+=","+$(e).attr("catid");
                    }
                }
            });
            if(ids==="") {
                alert("Please select atleast one song to edit it.");
            } else {
                if(count>1) {
                    alert("Please select only one song to edit.");
                } else {
                    document.location="/admin/songs/new/?songid="+ids;
                }
            }
        };
        
        (function($) {
            $.fn.toggleDisabled = function(){
                return this.each(function(){
                    if($(this).attr("disabled")) {
                        $(this).removeAttr("disabled");
                    } else {
                        $(this).attr("disabled","true");
                    }
                });
            };
            $.fn.toggleChecked = function(){
                return this.each(function(){
                    if($(this).prop("checked")) {
                        $(this).prop("checked",false);
                        //$(this).attr("checked","false");
                    } else {
                        $(this).prop("checked",true);
                    }
                });
            };
        })(jQuery);
        
        function reqPost(link,data,callback) {
            $.post(link, data).done(callback);
        }

        function loadJS(link,callback) {
            $.ajax({
                url: link,
                dataType: "script",
                success: callback
            });
        }

        function reqAjax(link,data,callback) {
            $.ajax({
                url: link, // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: 'post',
                success: callback
             });
        }

        $.openDialog=function() {
            var comp=$(this);
            var _data="";
            if($(this).attr("data")!==null) {
                _data=$(this).attr("data");
            }
            if($(comp).attr("dialog-loader")==="loading") {
                return;
            }
            $(comp).attr("dialog-loader","loading");
            var page=$(this).attr("page");
            var title=$(this).attr("title");
            reqPost("/admin/get-part",{page:page,data:_data},function(data) {
                if(page==="page not found") {
                    alert("Module not found, please try again.");
                } else if(page==="invalid page") {
                    alert("Module identifier not provided, please try again.");
                } else {
                    $(comp).attr("dialog-loader","");
                    $("#my-dialog").attr("title",title);
                    $("#my-dialog").html(data);
                    $("#my-dialog").dialog({
                        close:function(event, ui) {
                            //$(".dlink[href='categories']").click();
                        }
                    });
                }
            });
        };
        
        $.trClickEvent=function() {
            var tr=$(this);
            if($(tr).find(".parent_cat_chk").toggleChecked().prop("checked")) {
                $.checkCount++;
            } else {
                $.checkCount--;
            }
        };
        
        $(document).ready(function() {		
            $("#parent_cat_table tr").click($.trClickEvent);
            
            $("#btnFetchIMDB").click(function() {
                var th=$(this);
                var imdbid=$("#imdbFetchID").val();
                var link="http://www.omdbapi.com/?i="+imdbid+"&plot=full&r=json";
                $(this).replaceWith("<input type='button' id='btnFetchIMDB' disabled=true value='Fetching...' />");
                reqPost(link,'',function(data) {
                    $("#btnFetchIMDB").replaceWith("<input type='button' id='btnFetchIMDB' value='Fetch' />");
                    $("#btnFetchIMDB").click($(th).click);
                    try {
						var img=new Image();
                        var js=$.parseJSON(data);
                        $("#formAddAlbum input[name='name']").val(js.Title);
                        $("#formAddAlbum input[name='year']").val(js.Year);
                        $("#formAddAlbum input[name='released']").val(js.Released);                        
                        $("#formAddAlbum input[name='genre']").val(js.Genre);
                        $("#formAddAlbum input[name='director']").val(js.Director);
                        $("#formAddAlbum input[name='writer']").val(js.Writer);                        
                        $("#formAddAlbum input[name='actors']").val(js.Actors);
                        $("#formAddAlbum input[name='plot']").val(js.Plot);
						img.src="/imdb_poster.php?imdb_id="+imdbid;
                        $("#formAddAlbum input[name='imdb_poster']").val(img.src);
                        $("#formAddAlbum input[name='uploadedPoster']").prop("checked",false);
                        $("#formAddAlbum input[name='imdb_json']").val(data);
                        $("#posterImageLoader").html("Loading Poster...");
						$(img).load(function() {
							$("#formAddAlbum #posterImage").replaceWith("<img id='posterImage' src='"+img.src+"' style='width:240px;border:1px solid white;' />");
                            $("#posterImageLoader").html("Poster:");
						});
                        
                    } catch(e) {
                        alert("Error while parsong IMDB json.\nError:"+e);
                    }
                });
            });
            
            $("#formAddAlbum input[name='poster']").change(function() {
                if ($(this).prop("files")[0]) {
                    $("#formAddAlbum input[name='uploadedPoster']").prop("checked",true);
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#formAddAlbum #posterImage').replaceWith("<img src='"+e.target.result+"' style='border:1px solid white;width:240px' id='posterImage'/>");
                    }
                    reader.readAsDataURL($(this).prop("files")[0]);
                } else {
                    $("#formAddAlbum input[name='uploadedPoster']").prop("checked",false);   
                    $("#formAddAlbum #posterImage").replaceWith("<img src='"+$("#formAddAlbum input[name='imdb_poster']").val()+"' id='posterImage' style='border:1px solid white;width:240px'/>");
                }
            });
            
            $("#formAddAlbum input[name='uploadedPoster']").change(function() {
                if(!$("#formAddAlbum input[name='uploadedPoster']").prop("checked")) {
                    $("#formAddAlbum #posterImage").replaceWith("<img src='"+$("#formAddAlbum input[name='imdb_poster']").val()+"' id='posterImage' style='border:1px solid white;width:240px'/>");
                } else {
                    if ($("#formAddAlbum input[name='poster']").prop("files")[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#formAddAlbum #posterImage').replaceWith("<img src='"+e.target.result+"' style='border:1px solid white;width:240px' id='posterImage'/>");
                        }
                        reader.readAsDataURL($("#formAddAlbum input[name='poster']").prop("files")[0]);
                    }
                }
            });
            
            $("#song-searcher").click(function() {
                var q=$("#song-slug").val().trim();
                var f=false;
                if(q) {
                    f=true;
                } else {
                    f=confirm("Do you really want to list all songs ?");
                }
                if(f===true) {
                    reqPost("/songs_search.php",{q:q},function(data) {
                        $("#parent_cat_table").html(data);
                        $("#parent_cat_table tr").click($.trClickEvent);
                    });
                }
            });
			
			$("#album-searcher").click(function() {
                var q=$("#album-slug").val().trim();
                var f=false;
                if(q) {
                    f=true;
                } else {
                    f=confirm("Do you really want to list all albums ?");
                }
                if(f===true) {
                    reqPost("/albums_search.php",{q:q},function(data) {
                        $("#parent_cat_table").html(data);
                        $("#parent_cat_table tr").click($.trClickEvent);
                    });
                }
            });
			
            $(".deleteSongFile").click(function() {
                if(confirm("Are you sure to delete this file ?")) {
                    var songid=$(this).attr("songid");
                    reqPost("/deleteSongFile.php",{fileid:$(this).attr("fileid")},function(data) {
                        alert(data);
                        document.location="/admin/songs/new/?songid="+songid;
                    });
                }
            });
			
			$.featuredStatus=false;
			
			$.addToFeatured=function() {
				if($.featuredStatus) return;
				$.featuredStatus=true;
				var t=(this);
				var aid=$(this).attr("data-album-id");
				reqPost("/featured.php",{func:"add",album_id:aid},function(data) {
					try {
						var js=$.parseJSON(data);
						if(js.success) {
							alert("Successfully added to featured list.");
							$(t).replaceWith("<input type='button' class='delFromFeatured' data-album-id='"+aid+"' value='Remove from Featured' />");
							$(".delFromFeatured").click($.delFromFeatured);
						} else {
							alert("Cant add to featured, please try again.");
						}
					} catch(e) {}
					$.featuredStatus=false;
				});
			};
			
			$.delFromFeatured=function() {
				if($.featuredStatus) return;
				$.featuredStatus=true;
				var t=(this);
				var aid=$(this).attr("data-album-id");
				reqPost("/featured.php",{func:"delete",album_id:aid},function(data) {
					try {
						var js=$.parseJSON(data);
						if(js.success) {
							alert("Successfully removed from featured list.");
							$(t).replaceWith("<input type='button' class='addToFeatured' data-album-id='"+aid+"' value='Add to Featured' />");
							$(".addToFeatured").click($.addToFeatured);
						} else {
							alert("Cant add to featured, please try again.");
						}
					} catch(e) {}
					$.featuredStatus=false;
				});
			};
			
			$(".addToFeatured").click($.addToFeatured);
			$(".delFromFeatured").click($.delFromFeatured);
			
			$.albumSearchBlock_Link_Click=function() {
				var album_id=$(this).attr("data-album-id");
				$("#inputAlbumSearch").val($(this).attr("data-album-name"));
				$("#inputAlbum").val(album_id);
				$(".album-search-block").slideUp(100);
				return false;
			};
			
			$("#inputAlbumSearch").keyup(function() {
				var q=$(this).val();
				var asb=$(".album-search-block");
				if(q) {
					if(asb) {
						reqPost("/albums_search.php",{q:q,instantSearch:''},function(data) {
							if($.trim(data).length==0) {
								$(asb).slideUp(100);
							} else {
								$(asb).html(data);
								$(asb).slideDown(100);
								$(".album-search-block a").click($.albumSearchBlock_Link_Click);
							}							
						});
					}
				} else {
					$(asb).slideUp();
				}
			});
        });
    </script>
	<script type="text/javascript" src="/theme/admin.js"></script>
    </body>
</html>