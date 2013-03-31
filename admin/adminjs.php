<script type="text/javascript">

    $(document).ready(function(){
        ajax("get_all_emails");
        $("#startdate").datepicker({altFormat: "yy-mm-dd", altField: "#start"});
        $("#enddate").datepicker({altFormat: "yy-mm-dd", altField: "#end"});
    });

    function ajax(action)
    {   
        switch (action)
        {
            case "get_playlist_url":
                $.ajax({
                    type: "POST",
                    url: "../common/ajax.php",
                    datatype: 'json',
                    data:{ action: action, email: $("#email").val(), startDate: $("#start").val(), endDate: $("#end").val() },
                    success: function(result){
                        
                        // parse JSON results
                        var parsed = jQuery.parseJSON(result);
                        console.log(parsed);
                        if (parsed.error !== true) // if no error
                        {
                            $("#message").text(parsed.message);
                        }
                        else
                        {
                            $("#message").text(parsed.message);
                        }
                    }
                });
            break;

            case "get_all_emails":
                $.ajax({
                    type: "POST",
                    url: "../common/ajax.php",
                    datatype: 'json',
                    data:{ action: action, email: $("#email").val() },
                    success: function(result){
                        
                        // parse JSON results
                        var parsed = jQuery.parseJSON(result);
                        if (parsed.error !== true) // if no error
                        {
                            
                            $("#email").autocomplete({source: parsed.data});
                            console.log(parsed.data);

                        }
                        else
                        {
                            console.log("error getting response");
                            console.log(parsed);
                        }
                    }
                });
            break;

            case "get_all_videos":
                $.ajax({
                    type: "POST",
                    url: "../common/ajax.php",
                    datatype: 'json',
                    data:{ action: action, email: $("#email").val(), startDate: $("#start").val(), endDate: $("#end").val() },
                    success: function(result){
                        
                        // parse JSON results
                        var parsed = jQuery.parseJSON(result);
                        if (parsed.error !== true) // if no error
                        {
                            
                            console.log(parsed.data);
                            window.video_list = parsed.data;
                            redraw_table(parsed.data);

                        }
                        else
                        {
                            console.log("error getting response");
                            console.log(parsed);
                        }
                    }
                });
            break;

            default:
                return false;
        }
    }

    function redraw_table(video_list)
    {
        var table = "<pre id='url'> " + build_url(video_list) + "</pre>" 
            + "<table class='table table-bordered table-hover video-table' id='video_list'>"
            + "<tr><th>include</th><th>thumb</th><th>title</th></tr>";

        for (var id in video_list)
        {
            table += "<tr class='" + video_list[id] + "' onclick='toggle_included($(this))';><td class='include'><span class='label info-label'>Included</span></td><td><a target='_blank' href='http://youtube.com/watch?v=" + video_list[id] + "'><img src='http://img.youtube.com/vi/" + video_list[id] + "/default.jpg'/></a></td><td id='title-" + video_list[id] + "'>" + video_list[id] + "</td></tr>";
        }
        
        table += "</table>";

        $("#table").html(table);


        // call out for the titles
        for (var id in video_list)
        {
            fill_yt_title(video_list[id]);
        }
    }

    function toggle_included(video_tr)
    {
        // get the current row's video id
        var id = $(video_tr).attr("class")
        if ($(video_tr).children("td[class='include']").children('span.label').length > 0)
        {
            // the item is included
            $(video_tr).children("td[class='include']").children('span.label').remove();
            toggle_video(id);
        }
        else
        {
            // the video is not included
            $(video_tr).children("td[class='include']").html("<span class='label info-label'>Included</span>");
            toggle_video(id);
        }

        $("#url").fadeOut(function(){
            $(this).html(build_url(window.video_list)).fadeIn();
        });


        // console.log($("tr[class=" + video_id + "]"));
    }

    function toggle_video(id)
    {
        var found = jQuery.inArray(id, window.video_list);
        if (found >= 0) {
            // Element was found, remove it.
            console.log('found');
            window.video_list.splice(found, 1);
        } else {
            // Element was not found, add it.
            window.video_list.push(id);
            console.log('not found');
        }
    }

    function build_url(video_list)
    {
        console.log(video_list);
        url = "http://youtube.googleapis.com/v/" + video_list[0];

        // remove the first video and merge the rest to csv
        var remaining_vids = video_list.slice(1);
        console.log("remaining vids");
        console.log(remaining_vids);
        
        if (remaining_vids.length > 0)
        {
            url += "?version=3&playlist=";
            url += remaining_vids.join(",");
        }
        
        return url;
    }

    function fill_yt_title(video_id) 
    {
        $.ajax({
                url: "http://gdata.youtube.com/feeds/api/videos/" + video_id + "?v=2&alt=json",
                dataType: "jsonp",
                success: function (data) { $("#title-" + video_id).html(data.entry.title.$t); }
        });
    }





</script>