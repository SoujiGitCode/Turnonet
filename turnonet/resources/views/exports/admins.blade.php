<table style="width: 100%" width="100%">
    <thead>
    <tr>
        <th  width="20" style="width: 20px;"><strong>Nombre</strong></th>
        <th  width="40" style="width: 40px;"><strong>Correo electrónico</strong></th>
        <th  width="20" style="width: 20px;"><strong>Rol</strong></th>
        <th  width="20" style="width: 20px;"><strong>Fecha de Registro</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $rs)
        <tr>
            <td style="vertical-align: middle;">{{ $rs['name'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['email'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['rol'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['created_at'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>