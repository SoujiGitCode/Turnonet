<table style="width: 100%" width="100%">
    <thead>
        <tr>
            <th  width="20" style="width: 20px;"><strong>Empresa </strong></th>
            <th  width="40" style="width: 40px;"><strong>Correo electrónico Empresa</strong></th>
            <th  width="20" style="width: 20px;"><strong>Cliente</strong></th>
            <th  width="40" style="width: 40px;"><strong>Correo electrónico Cliente</strong></th>
            <th  width="20" style="width: 20px;"><strong>Fecha de Registro</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $rs)
        <tr>
            <td style="vertical-align: middle;">{{ $rs['business'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['email_business'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['name'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['email'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['created_at'] }}</td>

        </tr>
        @endforeach
    </tbody>
</table>