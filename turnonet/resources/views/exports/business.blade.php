<table style="width: 100%" width="100%">
    <thead>
        <tr>
            <th  width="20" style="width: 20px;"><strong>Usuario</strong></th>
            <th  width="40" style="width: 40px;"><strong>Correo usuario</strong></th>
            <th  width="20" style="width: 20px;"><strong>Empresa </strong></th>
            <th  width="40" style="width: 40px;"><strong>Correo electrónico Empresa</strong></th>
            <th  width="20" style="width: 10px;"><strong>Cant Pres</strong></th>
            <th  width="20" style="width: 10px;"><strong>Paga</strong></th>
            <th  width="20" style="width: 10px;"><strong>Turnos</strong></th>
            <th  width="20" style="width: 10px;"><strong>UM</strong></th>
            <th  width="20" style="width: 10px;"><strong>SMS</strong></th>
            <th  width="20" style="width: 10px;"><strong>MP</strong></th>
            <th  width="20" style="width: 20px;"><strong>Estatus</strong></th>
            <th  width="20" style="width: 20px;"><strong>Fecha de Registro</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $rs)
        <tr>
            <td style="vertical-align: middle;">{{ $rs['name_user'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['email_user'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['name'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['email'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['lenders'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['pay'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['shift'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['um'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['status_sms'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['mp'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['status'] }}</td>
            <td style="vertical-align: middle;">{{ $rs['created_at'] }}</td>

        </tr>
        @endforeach
    </tbody>
</table>