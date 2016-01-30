/**
 Core script to handle the entire layout and base functions
 **/
var errorCode = {
    1: 'Not a String',
};

var modalType = {
    1: 'Error'
};

songImport = function(spotifySongId, aid, oid, captchaData) {
    $.ajax({
        url: '/vk/ajax/import_song/'+aid+'/'+oid,
        method: 'GET',
        success: function (data) {
            $('tr#'+spotifySongId+' td.song_action .song-import-active').hide();
            if(data.response.status == "error") {
                $('tr#'+spotifySongId+' td.song_action .song-not-imported').show();
                modal(1,data.response.message);
                if(data.response.hasOwnProperty('captcha_sid') || data.response.hasOwnProperty('captcha_sid')){
                    return 0;
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
                modal(1,data.response.message);
                if(data.response.hasOwnProperty('captcha_sid') || data.response.hasOwnProperty('captcha_sid')){
                    return 0;
                }
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
    $('#vk-table > tbody  > tr').each(function() {
        var _this = this;
        setTimeout(function(){
            status = searchSong(($(_this).attr('id')));
        }, 0);

        if(status==0) {
            $('#songs-search-restart').show();
            return false;
        }
    })
};

modal = function (type, message, data) {
    $("#vk" + modalType[type] + " .modal-body").empty().append(message);
    $("#vk" + modalType[type]).modal('show');
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
        searchSongs();
    });
});