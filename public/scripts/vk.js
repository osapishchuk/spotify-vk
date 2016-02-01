/**
 Core script to handle the entire layout and base functions
 **/
var errorCode = {
    1: 'Not a String',
};

var modalType = {
    1: 'Error',
    2: 'Captcha',
};

songImport = function(spotifySongId, aid, oid, captchaData) {
    $.ajax({
        url: '/vk/ajax/import_song/'+aid+'/'+oid,
        method: 'GET',
        success: function (data) {
            $('tr#'+spotifySongId+' td.song_action .song-import-active').hide();
            if(data.response.status == "error") {
                $('tr#'+spotifySongId+' td.song_action .song-not-imported').show();

                if(data.response.hasOwnProperty('captcha_sid') || data.response.hasOwnProperty('captcha_sid')){
                    modal(2,null,data.response);
                } else {
                    modal(1,data.response.message);
                }
            } else if (data.response.status == "success") {
                $('tr#'+spotifySongId+' td.song_action .song-imported').show();
                $('tr#'+spotifySongId+' td.song_action'+" input[name='oid']").val('');
                $('tr#'+spotifySongId+' td.song_action'+" input[name='aid']").val('');
            } else {
                $('tr#'+spotifySongId+' td.song_action .song-not-imported').show();
                console.log(data);
            }

            return 1;
        }
    });
};

searchSong = function (songSpotifyArrayId) {
    $.ajax({
        url: '/vk/ajax/search_song/'+songSpotifyArrayId,
        async: false,
        method: 'GET',
        success: function (data) {
            $('tr#'+songSpotifyArrayId+' td.song_status a.song-searching').hide();
            var addSongImportData = function (song_info) {
                $('tr#'+songSpotifyArrayId+' td.song_action'+" input[name='oid']").val(song_info.oid);
                $('tr#'+songSpotifyArrayId+' td.song_action'+" input[name='aid']").val(song_info.aid);
            };
            if(data.response.status == "failed") {
                $('tr#'+songSpotifyArrayId+' td.song_status .song-not-found').show();
            } else if (data.response.status == "success") {
                $('tr#'+songSpotifyArrayId+' td.song_action .song-import').hide();
                $('tr#'+songSpotifyArrayId+' td.song_action .song-import-active').show();
                $('tr#'+songSpotifyArrayId+' td.song_status .song-found').show();
                addSongImportData(data.response.song_info);
            } else if (data.response.status == "error"){
                $('tr#'+songSpotifyArrayId+' td.song_status .song-error').show();
                if(data.response.hasOwnProperty('captcha_sid') || data.response.hasOwnProperty('captcha_sid')){
                    modal(2,null,data.response);
                    return 0;
                }
                modal(1,data.response.message);
                return 0;
            } else {
                $('tr#'+songSpotifyArrayId+' td.song_status .song-error').show();
                console.log(data);
            }

            return 1;
        }
    });
};

searchSongs = function () {
    var status;
    $('#vk-table > tbody  > tr').each(function () {
        status = searchSong(($(this).attr('id')));
        if (status == 0) {
            $('#songs-search-restart').show();
            return false;
        }
    })
};

modal = function (type, message, data) {
    if (type != 2) {
        $("#vk" + modalType[type] + " .modal-body").empty().append(message);
        $("#vk" + modalType[type]).modal('show');
    } else if (type == 2) {
        var modalBody = '<img src="'+data.captcha_img+'" alt="VK Captcha" height="42" width="42">'+
            '<input type="hidden" name="vk-captcha-id" value="'+data.captcha_sid+'">'+
            '<input type="text" name="vk-captcha-value">'+
            '<button id="vk-captcha">Ok</button> ';

        $("#vkCaptcha .modal-body").empty().append(modalBody);
        $("#vkCaptcha").modal('show');
    }
};

$( document ).ready(function() {

    $('.song-import-active').on("click", function () {
        var spotifySongId = $(this).closest('tr').attr('id');
        var aid =$('tr#'+spotifySongId+' td.song_action'+" input[name='aid']").val();
        var oid =$('tr#'+spotifySongId+' td.song_action'+" input[name='oid']").val();
        songImport(spotifySongId, aid, oid);
    });

    searchSongs();

    $('#songs-search-restart').on( "click", function() {
        $('#vk-table > tbody  > tr').each(function () {
            var id = $(this).attr('id');
            $('tr#'+id+' td.song_status a.song-not-found').hide();
            $('tr#'+id+' td.song_status a.song-found').hide();
            $('tr#'+id+' td.song_status a.song-searching').show();
            $('tr#'+id+' td.song_action'+" input[name='oid']").val('');
            $('tr#'+id+' td.song_action'+" input[name='aid']").val('');
        });

        searchSongs();
    });
});