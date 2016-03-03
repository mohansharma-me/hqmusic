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

$(document).ready(function() {
    $("a.dlink").click(function() {
        var ca=$(this);
        var tag=$(this).attr("href");        
        $(ca).parent().find("img.page-loader").remove();
        $(this).append(" <img src='/theme/loader.gif' class='page-loader' />");
        reqPost("/admin/get-module",{page:tag},function(data) {
            if(data==="page not found") {
                alert("Page not found, please try again.");
            } else if(data==="invalid page") {
                alert("No page identifier selected, please try again");
            } else {
                $(".content-holder").html(data);
            }
            $(ca).parent().find("img.page-loader").remove();
        });
        document.location="#"+$(this).attr("href");
        return false;
    });
});