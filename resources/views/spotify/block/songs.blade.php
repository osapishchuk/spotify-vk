    <!-- BEGIN SAMPLE TABLE PORTLET-->
    <div class="portlet box green">
        <div class="portlet-body" style="display: block;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>artists</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $song)
                    <tr>
                        <td>{{$song['id']}}</td>
                        <td>{{$song['name']}}</td>
                        <td>{{$song['artists']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- END SAMPLE TABLE PORTLET-->