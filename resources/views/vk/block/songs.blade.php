    <!-- BEGIN SAMPLE TABLE PORTLET-->
<div class="row-fluid">
    <div class="span12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="icon-cogs"></i>Spotify-VK</div>
            </div>
            <div class="portlet-body" style="display: block;">
                <table class="table table-striped table-hover" id="vk-table">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>status</th>
                        <th>action</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--*/ $id = 1 /*--}}
                    @foreach($data['songsNameToSearchArray'] as $key => $song)
                        <tr id="{{$key}}">
                            <td>{{$id}}</td>
                            <td class="song_name">{{$song}}</td>
                            <td class="song_status">
                                <a class="btn blue disabled song-searching" href="javascript:void(0);"><i class="icon-search"></i> Searching...</a>
                                <a class="btn green disabled song-found" href="javascript:void(0);" style="display: none"><i class="icon-ok"></i> Found</a>
                                <a class="btn red disabled song-not-found" href="javascript:void(0);" style="display: none"><i class=" icon-remove"></i> Not Found</a>
                                <a class="btn red disabled song-error" href="javascript:void(0);" style="display: none">error</a>
                            </td>
                            <td class="song_action">
                                <input type="hidden" name="oid" value="">
                                <input type="hidden" name="aid" value="">
                                <a class="btn disabled song-import" href="javascript:void(0);">Import</a>
                                <a class="btn song-import-active" href="javascript:void(0);" style="display: none">Import</a>
                                <a class="btn green disabled song-imported" href="javascript:void(0);" style="display: none">Imported</a>
                                <a class="btn red disabled song-not-imported" href="javascript:void(0);" style="display: none">Not imported</a>
                            </td>
                        </tr>
                        {{--*/ $id++ /*--}}
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="vkError" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="vkErrorLabel" aria-hidden="true">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
            <h3 id="vkErrorLabel">Error</h3>
        </div>
        <div class="modal-body">
            <p>One fine body�</p>
        </div>
        <div class="modal-footer">
            <button class="btn red" data-dismiss="modal" aria-hidden="true" type="button">Close</button>
        </div>
    </div>
    <div id="vkCaptcha" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="vkCaptchaLabel" aria-hidden="true">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
            <h3 id="vkErrorLabel">Captcha</h3>
        </div>
        <div class="modal-body">
            <p>One fine body�</p>
        </div>
        <div class="modal-footer">
            <button class="btn red" data-dismiss="modal" aria-hidden="true" type="button">Close</button>
        </div>
    </div>
</div>
    <!-- END SAMPLE TABLE PORTLET-->