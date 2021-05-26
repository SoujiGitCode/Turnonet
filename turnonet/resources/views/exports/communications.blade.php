<table style="width: 100%" width="100%">
    <thead>
    <tr>
        <th  width="60" style="width: 60px;"><strong>Título</strong></th>
        <th  width="100" style="width: 100px;"><strong>Contenido</strong></th>
        <th  width="20" style="width: 20px;"><strong>Fecha de Registro</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $rs)
        <tr>
            <td style="vertical-align: middle;">{{ $rs['title'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['content'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['created_at'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>