<!-- BEGIN SAMPLE TABLE PORTLET-->
<div class="portlet box green">
    <div class="portlet-body" style="display: block;">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>id</th>
                <th>name</th>
                <th>action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $playlist)
                <tr>
                    <td>{{$playlist['id']}}</td>
                    <td>{{$playlist['name']}}</td>
                    <td><a class="btn disabled playlist-import" href="javascript:void(0);">Import</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- END SAMPLE TABLE PORTLET-->