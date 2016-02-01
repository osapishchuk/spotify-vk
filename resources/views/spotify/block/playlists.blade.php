<!-- BEGIN SAMPLE TABLE PORTLET-->
<div class="portlet box green">
    <div class="portlet-body" style="display: block;">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>id</th>
                <th>owner_id</th>
                <th>name</th>
                <th>action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $playlist)
                <tr>
                    <td>{{$playlist['id']}}</td>
                    <td>{{$playlist['owner_id']}}</td>
                    <td>{{$playlist['name']}}</td>
                    <td><a class="btn playlist-import" href="{{URL::to('/spotify/set_playlist/'.$playlist['owner_id'].'/'.$playlist['id'])}}">Import</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- END SAMPLE TABLE PORTLET-->